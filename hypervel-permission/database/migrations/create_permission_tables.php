<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionTables extends Migration
{
    public function up()
    {
        $identifierType = config('hypervel-permission.identifier_type', 'id') === 'uuid' ? 'uuid' : 'bigIncrements';

        Schema::create(config('hypervel-permission.table_names.roles'), function (Blueprint $table) use ($identifierType) {
            $table->$identifierType('id')->primary();
            $table->string('name')->unique();
            $table->string('guard_name')->default('web');
            $table->timestamps();
        });

        Schema::create(config('hypervel-permission.table_names.permissions'), function (Blueprint $table) use ($identifierType) {
            $table->$identifierType('id')->primary();
            $table->string('name')->unique();
            $table->string('guard_name')->default('web');
            $table->timestamps();
        });

        Schema::create(config('hypervel-permission.table_names.model_has_permissions'), function (Blueprint $table) use ($identifierType) {
            $table->$identifierType('permission_id');
            $table->unsignedBigInteger('model_id');
            $table->string('model_type');
            $table->foreign('permission_id')->references('id')->on(config('hypervel-permission.table_names.permissions'))->onDelete('cascade');
            $table->index(['model_id', 'model_type']);
        });

        Schema::create(config('hypervel-permission.table_names.model_has_roles'), function (Blueprint $table) use ($identifierType) {
            $table->$identifierType('role_id');
            $table->unsignedBigInteger('model_id');
            $table->string('model_type');
            $table->foreign('role_id')->references('id')->on(config('hypervel-permission.table_names.roles'))->onDelete('cascade');
            $table->index(['model_id', 'model_type']);
        });

        Schema::create(config('hypervel-permission.table_names.role_has_permissions'), function (Blueprint $table) use ($identifierType) {
            $table->$identifierType('permission_id');
            $table->$identifierType('role_id');
            $table->foreign('permission_id')->references('id')->on(config('hypervel-permission.table_names.permissions'))->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on(config('hypervel-permission.table_names.roles'))->onDelete('cascade');
            $table->primary(['permission_id', 'role_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists(config('hypervel-permission.table_names.role_has_permissions'));
        Schema::dropIfExists(config('hypervel-permission.table_names.model_has_permissions'));
        Schema::dropIfExists(config('hypervel-permission.table_names.model_has_roles'));
        Schema::dropIfExists(config('hypervel-permission.table_names.permissions'));
        Schema::dropIfExists(config('hypervel-permission.table_names.roles'));
    }
}