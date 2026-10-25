<?php

use App\Domain\Catalog\Enums\CourseLevel;
use App\Domain\Catalog\Enums\CourseStatus;
use App\Domain\Catalog\Enums\LessonType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestampsTz();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('instructor_profiles')->restrictOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('summary', 320);
            $table->text('description');
            $table->json('outcomes');
            $table->json('requirements')->nullable();
            $table->json('audience')->nullable();
            $table->unsignedBigInteger('price_idr');
            $table->enum('level', array_column(CourseLevel::cases(), 'value'));
            $table->string('language', 5)->default('id');
            $table->enum('status', array_column(CourseStatus::cases(), 'value'))
                ->default(CourseStatus::Draft->value);
            $table->string('thumbnail_url')->nullable();
            $table->unsignedInteger('total_duration_minutes')->default(0);
            $table->timestampTz('published_at')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['status', 'published_at']);
            $table->index(['instructor_id', 'status']);
            $table->index(['category_id', 'status']);
        });

        Schema::create('course_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestampsTz();
            $table->unique(['course_id', 'sort_order']);
        });

        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('summary', 320)->nullable();
            $table->enum('type', array_column(LessonType::cases(), 'value'));
            $table->longText('content')->nullable();
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->boolean('is_preview')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestampsTz();
            $table->unique(['course_section_id', 'sort_order']);
        });

        $this->addPostgresChecks();
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('course_sections');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('categories');
    }

    private function addPostgresChecks(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('ALTER TABLE courses ADD CONSTRAINT courses_price_idr_nonnegative CHECK (price_idr >= 0)');
        DB::statement('ALTER TABLE courses ADD CONSTRAINT courses_duration_nonnegative CHECK (total_duration_minutes >= 0)');
        DB::statement('ALTER TABLE course_sections ADD CONSTRAINT course_sections_sort_nonnegative CHECK (sort_order >= 0)');
        DB::statement('ALTER TABLE lessons ADD CONSTRAINT lessons_duration_nonnegative CHECK (duration_seconds >= 0)');
        DB::statement('ALTER TABLE lessons ADD CONSTRAINT lessons_sort_nonnegative CHECK (sort_order >= 0)');
    }
};
