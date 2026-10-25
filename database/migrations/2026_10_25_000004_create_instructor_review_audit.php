<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructor_review_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_profile_id')->constrained()->restrictOnDelete();
            $table->foreignId('actor_id')->constrained('users')->restrictOnDelete();
            $table->string('from_status', 32);
            $table->string('to_status', 32);
            $table->timestampTz('created_at');
            $table->index(['instructor_profile_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructor_review_events');
    }
};
