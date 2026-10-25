<?php
namespace App\Filament\Admin\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class UserMonitoring extends Page
{
    use WithPagination;

    protected static ?string $slug = 'monitoring-pengguna';
    protected string $view = 'filament.admin.pages.user-monitoring';
    public string $search = '';

    public static function getNavigationLabel(): string { return 'Monitoring pengguna'; }
    public function getTitle(): string { return 'Monitoring pengguna · Superadmin'; }
    public static function canAccess(): bool { return auth()->user()?->hasRole('super_admin') === true; }
    public function mount(): void { abort_unless(static::canAccess(), 403); }
    public function updatedSearch(): void { $this->resetPage(); }

    public function getTotals(): array
    {
        abort_unless(static::canAccess(), 403);
        $cutoff = now()->subMinutes(15)->timestamp;
        return [
            'users' => User::query()->count(),
            'active' => DB::table('sessions')->whereNotNull('user_id')->where('last_activity', '>=', $cutoff)->distinct()->count('user_id'),
            'sessions' => DB::table('sessions')->where('last_activity', '>=', $cutoff)->count(),
        ];
    }

    public function getUsers()
    {
        abort_unless(static::canAccess(), 403);
        $sessions = DB::table('sessions')->select('user_id')
            ->selectRaw('MAX(last_activity) as last_seen, COUNT(*) as session_count')
            ->whereNotNull('user_id')->groupBy('user_id');
        return User::query()->select('users.id', 'users.name', 'users.email', 'users.created_at', 'usage.last_seen', 'usage.session_count')
            ->leftJoinSub($sessions, 'usage', fn ($join) => $join->on('users.id', '=', 'usage.user_id'))
            ->with('roles:id,name')
            ->when(trim($this->search) !== '', function ($query) {
                $query->where(function ($sub) {
                    $sub->where('users.name', 'ilike', '%'.addcslashes(trim($this->search), '%_\\').'%')
                        ->orWhere('users.email', 'ilike', '%'.addcslashes(trim($this->search), '%_\\').'%');
                });
            })
            ->orderByDesc('usage.last_seen')->orderBy('users.id')
            ->paginate(15);
    }
}
