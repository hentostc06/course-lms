<?php

namespace App\Http\Controllers\Moderation;

use App\Domain\Identity\Enums\RoleName;
use App\Domain\Instructor\Enums\InstructorStatus;
use App\Domain\Instructor\Models\InstructorProfile;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        DB::transaction(function () use ($profile, $request): void {
            $locked = InstructorProfile::query()->lockForUpdate()->findOrFail($profile->id);
            abort_unless($locked->status === InstructorStatus::Pending, 409, 'Pengajuan sudah diproses.');

            $locked->update([
                'status' => InstructorStatus::Approved,
                'approved_at' => now(),
            ]);
            DB::table('instructor_review_events')->insert([
                'instructor_profile_id' => $locked->id,
                'actor_id' => $request->user()->id,
                'from_status' => InstructorStatus::Pending->value,
                'to_status' => InstructorStatus::Approved->value,
                'created_at' => now(),
            ]);
        });

        return back()->with('success', 'Instruktur berhasil diverifikasi.');
    }

    private function ensureAdmin(Request $request): void
    {
        abort_unless($request->user()?->hasRole(RoleName::Admin), 403);
    }
}
