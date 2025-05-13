<?php

declare(strict_types=1);

use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        if (config('permission.use_uuid', false)) {
            if (Schema::hasTable('permissions') && !Schema::hasColumn('permissions', 'uuid')) {
                Schema::table('permissions', function (Blueprint $table) {
                    $table->char('uuid', 36)->nullable()->unique()->after('id');
                });
            }

            if (Schema::hasTable('roles') && !Schema::hasColumn('roles', 'uuid')) {
                Schema::table('roles', function (Blueprint $table) {
                    $table->char('uuid', 36)->nullable()->unique()->after('id');
                });
            }
        }
    }

    public function down(): void
    {
        if (config('permission.use_uuid', false)) {
            if (Schema::hasTable('permissions') && Schema::hasColumn('permissions', 'uuid')) {
                Schema::table('permissions', function (Blueprint $table) {
                    $table->dropColumn('uuid');
                });
            }

            if (Schema::hasTable('roles') && Schema::hasColumn('roles', 'uuid')) {
                Schema::table('roles', function (Blueprint $table) {
                    $table->dropColumn('uuid');
                });
            }
        }
    }
};