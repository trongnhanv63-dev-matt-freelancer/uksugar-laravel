<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (!Schema::hasColumn('permissions', 'status')) {
                $table->string('status', 20)->default('active')->after('description');
            }
        });
    }
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (Schema::hasColumn('permissions', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
