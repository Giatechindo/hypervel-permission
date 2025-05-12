<?php

declare(strict_types=1);

use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

return new class {
    public function up(): void
    {
        if (config('permission.use_uuid', false)) {
            Schema::table('permissions', function (Blueprint $table) {
                if (!Schema::hasColumn('permissions', 'uuid')) {
                    $table->char('uuid', 36)->nullable()->unique()->after('id');
                }
            });

            Schema::table('roles', function (Blueprint $table) {
                if (!Schema::hasColumn('roles', 'uuid')) {
                    $table->char('uuid', 36)->nullable()->unique()->after('id');
                }
            });
        }
    }

    public function down(): void
    {
        if (config('permission.use_uuid', false)) {
            Schema::table('permissions', function (Blueprint $table) {
                if (Schema::hasColumn('permissions', 'uuid')) {
                    $table->dropColumn('uuid');
                }
            });

            Schema::table('roles', function (Blueprint $table) {
                if (Schema::hasColumn('roles', 'uuid')) {
                    $table->dropColumn('uuid');
                }
            });
        }
    }
};