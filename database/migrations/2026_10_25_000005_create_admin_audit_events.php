<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_audit_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('actor_id')->constrained('users')->restrictOnDelete();
            $table->string('event_type', 80)->index();
            $table->string('subject_type', 40);
            $table->unsignedBigInteger('subject_id');
            $table->text('from_status')->nullable();
            $table->text('to_status')->nullable();
            $table->text('note')->nullable();
            $table->uuid('request_id');
            $table->timestampTz('created_at')->index();
            $table->index(['subject_type', 'subject_id']);
        });

        Schema::table('instructor_review_events', function (Blueprint $table): void {
            $table->text('note')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('instructor_review_events', function (Blueprint $table): void {
            $table->dropColumn('note');
        });
        Schema::dropIfExists('admin_audit_events');
    }
};
