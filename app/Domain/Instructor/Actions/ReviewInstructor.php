<?php

namespace App\Domain\Instructor\Actions;

use App\Domain\Identity\Models\AdminAuditEvent;
use App\Domain\Instructor\Enums\InstructorStatus;
use App\Domain\Instructor\Models\InstructorProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReviewInstructor
{
    public function handle(User $actor, InstructorProfile $profile, string $decision, ?string $reason = null): void
    {
        abort_unless($actor->hasVerifiedEmail() && $actor->hasRole('admin'), 403);
        $data = Validator::make(['decision' => $decision, 'reason' => $reason === null ? null : trim($reason)], [
            'decision' => ['required', Rule::in(['approve', 'suspend', 'restore'])],
            'reason' => ['required_unless:decision,approve', 'nullable', 'string', 'min:10', 'max:2000'],
        ])->validate();

        DB::transaction(function () use ($actor, $profile, $decision, $data): void {
            $locked = InstructorProfile::query()->lockForUpdate()->findOrFail($profile->id);
            $expected = match ($decision) {
                'approve' => InstructorStatus::Pending,
                'suspend' => InstructorStatus::Approved,
                default => InstructorStatus::Suspended,
            };
            abort_unless($locked->status === $expected, 409, 'Status instruktur sudah berubah. Muat ulang daftar.');
            $target = $decision === 'suspend' ? InstructorStatus::Suspended : InstructorStatus::Approved;
            $locked->update(['status' => $target, 'approved_at' => $decision === 'suspend' ? $locked->approved_at : now()]);
            DB::table('instructor_review_events')->insert([
                'instructor_profile_id' => $locked->id, 'actor_id' => $actor->id,
                'from_status' => $expected->value, 'to_status' => $target->value,
                'note' => $data['reason'] ?? 'Pengajuan instruktur disetujui setelah peninjauan.', 'created_at' => now(),
            ]);
            AdminAuditEvent::record($actor, 'instructor.'.$decision, 'instructor', $locked->id,
                $expected->value, $target->value,
                $data['reason'] ?? 'Pengajuan instruktur disetujui setelah peninjauan.');
        });
    }
}
