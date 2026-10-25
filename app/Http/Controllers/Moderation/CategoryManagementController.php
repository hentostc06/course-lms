<?php

namespace App\Http\Controllers\Moderation;

use App\Domain\Catalog\Models\Category;
use App\Domain\Identity\Enums\RoleName;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        app(\App\Domain\Catalog\Actions\SaveCategory::class)->handle($request->user(), $request->only(['name', 'description']));

        return back()->with('success', 'Kategori tersedia untuk instruktur.');
    }

    private function ensureAdmin(Request $request): void
    {
        abort_unless($request->user()?->hasRole(RoleName::Admin), 403);
    }
}
