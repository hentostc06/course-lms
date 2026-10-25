<?php

namespace App\Domain\Identity\Models;

use App\Domain\Identity\Enums\RoleName;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = ['name'];

    /** @return BelongsToMany<User, $this> */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['name' => RoleName::class];
    }
}
