<?php

namespace App\Http\Controllers\Moderation;

use App\Domain\Identity\Enums\RoleName;
use App\Domain\Instructor\Enums\InstructorStatus;
use App\Domain\Instructor\Models\InstructorProfile;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InstructorReviewController extends Controller
{
    public function index(Request $request): Response
    {
        $this->ensureAdmin($request);

        $profiles = InstructorProfile::query()
            ->where('status', InstructorStatus::Pending)
            ->with('user:id,name,email')
            ->oldest('created_at')->get();

        return Inertia::render('moderation/InstructorReviews', [
            'instructors' => $profiles->map(fn (InstructorProfile $profile) => [
                'id' => $profile->id,
                'name' => $profile->user->name,
                'email' => $profile->user->email,
                'headline' => $profile->headline,
                'bio' => $profile->bio,
            ])->all(),
        ]);
    }

    public function approve(Request $request, InstructorProfile $profile): RedirectResponse
    {
        $this->ensureAdmin($request);

        app(\App\Domain\Instructor\Actions\ReviewInstructor::class)->handle($request->user(), $profile, 'approve');

        return back()->with('success', 'Instruktur berhasil diverifikasi.');
    }

    private function ensureAdmin(Request $request): void
    {
        abort_unless($request->user()?->hasRole(RoleName::Admin), 403);
    }
}
