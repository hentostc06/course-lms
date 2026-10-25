<?php

namespace App\Http\Controllers\Instructor;

use App\Domain\Identity\Enums\RoleName;
use App\Domain\Identity\Models\Role;
use App\Domain\Instructor\Enums\InstructorStatus;
use App\Domain\Instructor\Models\InstructorProfile;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class InstructorOnboardingController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('instructor/Onboarding', [
            'profile' => $request->user()->instructorProfile?->only(['headline', 'bio', 'status']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'headline' => ['required', 'string', 'min:10', 'max:150'],
            'bio' => ['required', 'string', 'min:50', 'max:5000'],
        ]);

        $user = $request->user();
        $created = DB::transaction(function () use ($user, $data): bool {
            $user->newQuery()->whereKey($user->id)->lockForUpdate()->firstOrFail();
            if ($user->instructorProfile()->exists()) {
                return false;
            }

            InstructorProfile::query()->create([
                'user_id' => $user->id,
                'slug' => Str::slug($user->name).'-'.Str::lower(Str::random(10)),
                'headline' => $data['headline'],
                'bio' => $data['bio'],
                'status' => InstructorStatus::Pending,
            ]);
    
            $instructorRole = Role::query()->where('name', RoleName::Instructor)->firstOrFail();
            $user->roles()->syncWithoutDetaching([$instructorRole->id]);

            return true;
        });

        if (! $created) {
            return to_route('instructor.onboarding')->with('error', 'Pendaftaran instruktur sudah tercatat.');
        }

        return to_route('instructor.onboarding')->with('success', 'Pengajuan instruktur diterima. Menunggu verifikasi admin.');
    }
}
