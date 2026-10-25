<?php

namespace App\Domain\Identity\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use LogicException;

class AdminAuditEvent extends Model
{
    public const UPDATED_AT = null;

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::updating(fn () => throw new LogicException('Riwayat audit tidak dapat diubah.'));
        static::deleting(fn () => throw new LogicException('Riwayat audit tidak dapat dihapus.'));
    }

    /** @return BelongsTo<User, $this> */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public static function record(User $actor, string $event, string $subject, int $id, ?string $from = null, ?string $to = null, ?string $note = null): void
    {
        static::query()->create([
            'actor_id' => $actor->id,
            'event_type' => $event,
            'subject_type' => $subject,
            'subject_id' => $id,
            'from_status' => $from,
            'to_status' => $to,
            'note' => $note,
            'request_id' => (string) Str::uuid(),
        ]);
    }
}
