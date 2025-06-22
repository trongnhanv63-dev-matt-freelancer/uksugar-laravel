<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // Shortcut for bigIncrements('id')
            $table->string('name')->unique()->comment('The unique name of the role, e.g., super-admin, editor');
            $table->string('display_name')->nullable()->comment('A human-readable name for the role, e.g., Super Admin');
            $table->text('description')->nullable()->comment('A short description of the role\'s purpose');
            $table->timestamps(); // Creates created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
