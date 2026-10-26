<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Instructor\CourseSubmissionController;
use App\Http\Controllers\Instructor\CurriculumController;
use App\Http\Controllers\Instructor\InstructorCourseController;
use App\Http\Controllers\Instructor\InstructorOnboardingController;
use App\Http\Controllers\Marketplace\CourseCatalogController;
use App\Http\Controllers\Moderation\CategoryManagementController;
use App\Http\Controllers\Moderation\CourseReviewController;
use App\Http\Controllers\Moderation\InstructorReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CourseCatalogController::class, 'index'])->name('home');
Route::get('/courses', [CourseCatalogController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseCatalogController::class, 'show'])->name('courses.show');
Route::get('/courses/{slug}/preview/{lesson}', [CourseCatalogController::class, 'preview'])->name('courses.preview');
Route::get('/courses/{slug}/join', [CourseCatalogController::class, 'join'])->name('courses.join');

Route::middleware('guest')->group(function () {
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('instructor')->name('instructor.')->group(function () {
        Route::redirect('/', '/instructor/courses');
        Route::get('onboarding', [InstructorOnboardingController::class, 'show'])->name('onboarding');
        Route::post('onboarding', [InstructorOnboardingController::class, 'store'])->name('onboarding.store');
        Route::resource('courses', InstructorCourseController::class)->only(['index', 'create', 'store', 'edit', 'update']);
        Route::get('courses/{course}/curriculum', [CurriculumController::class, 'show'])->name('courses.curriculum');
        Route::post('courses/{course}/curriculum/sections', [CurriculumController::class, 'storeSection'])->name('sections.store');
        Route::post('courses/{course}/curriculum/sections/{section}/lessons', [CurriculumController::class, 'storeLesson'])->name('lessons.store');
        Route::patch('courses/{course}/curriculum/sections/{section}/lessons/{lesson}', [CurriculumController::class, 'updateLesson'])->name('lessons.update');
        Route::delete('courses/{course}/curriculum/sections/{section}/lessons/{lesson}', [CurriculumController::class, 'deleteLesson'])->name('lessons.destroy');
        Route::post('courses/{course}/submit', CourseSubmissionController::class)->name('courses.submit');
    });

    Route::prefix('moderation')->name('moderation.')->group(function () {
        Route::get('courses', [CourseReviewController::class, 'index'])->name('courses.index');
        Route::post('courses/{course}/decide', [CourseReviewController::class, 'decide'])->name('courses.decide');
        Route::get('instructors', [InstructorReviewController::class, 'index'])->name('instructors.index');
        Route::post('instructors/{profile}/approve', [InstructorReviewController::class, 'approve'])->name('instructors.approve');
        Route::get('categories', [CategoryManagementController::class, 'index'])->name('categories.index');
        Route::post('categories', [CategoryManagementController::class, 'store'])->name('categories.store');
    });

    Route::get('dashboard', function () {
        if (auth()->user()?->hasRole('admin')) {
            return redirect('/admin');
        }

        return \Inertia\Inertia::render('Dashboard');
    })->name('dashboard');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get(
        '/my-learning',
        [\App\Http\Controllers\Learning\LearningController::class, 'index']
    )->name('learning.index');

    Route::post(
        '/courses/{course:slug}/enroll',
        [\App\Http\Controllers\Learning\LearningController::class, 'enroll']
    )->name('learning.enroll');

    Route::get(
        '/learn/{course:slug}/{lesson}',
        [\App\Http\Controllers\Learning\LearningController::class, 'show']
    )->name('learning.show');

    Route::put(
        '/learn/{course:slug}/{lesson}/progress',
        [\App\Http\Controllers\Learning\LearningController::class, 'complete']
    )->name('learning.complete');
});

require __DIR__.'/settings.php';
