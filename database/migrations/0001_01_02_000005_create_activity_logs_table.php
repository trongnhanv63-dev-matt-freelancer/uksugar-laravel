<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('description'); // e.g., "Assigned role 'editor' to user 'nhan'."

            // The user who performed the action (the "causer")
            $table->nullableMorphs('causer');

            // The model that was acted upon (the "subject")
            $table->nullableMorphs('subject');

            $table->json('properties')->nullable(); // Store extra data, like old/new values
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
