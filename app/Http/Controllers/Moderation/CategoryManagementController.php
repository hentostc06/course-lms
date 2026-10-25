<?php

namespace App\Http\Controllers\Moderation;

use App\Domain\Catalog\Models\Category;
use App\Domain\Identity\Enums\RoleName;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class CategoryManagementController extends Controller
{
    public function index(Request $request): Response
    {
        $this->ensureAdmin($request);

        return Inertia::render('moderation/Categories', [
            'categories' => Category::query()->orderBy('name')->get(['id', 'name', 'slug', 'is_active']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:120', Rule::unique('categories', 'name')],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Category::query()->create([
            ...$data,
            'slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(5)),
            'is_active' => true,
        ]);

        return back()->with('success', 'Kategori tersedia untuk instruktur.');
    }

    private function ensureAdmin(Request $request): void
    {
        abort_unless($request->user()?->hasRole(RoleName::Admin), 403);
    }
}
