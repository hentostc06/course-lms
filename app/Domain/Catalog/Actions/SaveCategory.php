<?php

namespace App\Domain\Catalog\Actions;

use App\Domain\Catalog\Models\Category;
use App\Domain\Identity\Models\AdminAuditEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SaveCategory
{
    /** @param array<string, mixed> $input */
    public function handle(User $actor, array $input, ?Category $category = null): Category
    {
        abort_unless($actor->hasVerifiedEmail() && $actor->hasRole('admin'), 403);
        $data = Validator::make($input, [
            'name' => ['required', 'string', 'min:3', 'max:120', Rule::unique('categories', 'name')->ignore($category)],
            'description' => ['nullable', 'string', 'max:1000'],
            'image_path' => ['nullable','string','max:255'],
            'is_active' => ['sometimes', 'required', 'boolean'],
            'sort_order' => ['sometimes', 'required', 'integer', 'min:0', 'max:10000'],
        ])->validate();

        if (! empty($data['image_path']) && (
            ! preg_match(
                '~^catalog/categories/[a-zA-Z0-9/_-]+\.(jpg|jpeg|png|webp)$~i',
                $data['image_path']
            )
            || ! \Illuminate\Support\Facades\Storage::disk('public')
                ->exists($data['image_path'])
        )) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'image_path' => 'Gambar kategori tidak valid.',
            ]);
        }
        return DB::transaction(function () use ($actor, $data, $category): Category {
            $record = $category ? Category::query()->lockForUpdate()->findOrFail($category->id) : new Category;
            $fields = ['name', 'is_active', 'sort_order', 'description', 'image_path'];
            $before = $record->exists ? json_encode($record->only($fields), JSON_THROW_ON_ERROR) : null;
            if (! $record->exists) {
                $record->slug = Str::slug($data['name']).'-'.Str::lower(Str::random(8));
                $record->is_active = true;
            }
            $record->fill($data)->save();
            AdminAuditEvent::record($actor, $category ? 'category.updated' : 'category.created', 'category', $record->id,
                $before, json_encode($record->only($fields), JSON_THROW_ON_ERROR));

            return $record;
        });
    }
}
