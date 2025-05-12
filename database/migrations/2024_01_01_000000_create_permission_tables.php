<?php

declare(strict_types=1);

use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

return new class {
    public function up(): void
    {
        $useUuid = config('permission.use_uuid', false);

        Schema::create('permissions', function (Blueprint $table) use ($useUuid) {
            $table->bigIncrements('id');
            if ($useUuid) {
                $table->char('uuid', 36)->nullable()->unique();
            }
            $table->string('name')->unique();
            $table->string('guard_name')->nullable();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) use ($useUuid) {
            $table->bigIncrements('id');
            if ($useUuid) {
                $table->char('uuid', 36)->nullable()->unique();
            }
            $table->string('name')->unique();
            $table->string('guard_name')->nullable();
            $table->timestamps();
        });

        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->morphs('model');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });

        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->morphs('model');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->primary(['permission_id', 'role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
    }
};