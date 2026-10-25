<?php
namespace App\Filament\Admin\Pages;

use App\Models\User;
use App\Filament\Admin\AdminPage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class UserMonitoring extends AdminPage
{
    use WithPagination;

    protected static ?string $slug = 'monitoring-pengguna';
    protected static string | \UnitEnum | null $navigationGroup = 'Audit & Monitoring';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?int $navigationSort = 7;
    protected string $view = 'filament.admin.pages.user-monitoring';
    public string $search = '';

    public static function getNavigationLabel(): string { return 'Monitoring pengguna'; }
    public function getTitle(): string { return 'Monitoring pengguna · Superadmin'; }
    public static function canAccess(): bool { return parent::canAccess() && auth()->user()->hasRole('super_admin'); }
    public function mount(): void { abort_unless(static::canAccess(), 403); }
    public function updatedSearch(): void { $this->resetPage(); }

    public function getTotals(): array
    {
        abort_unless(static::canAccess(), 403);
        $databaseSessions = config('session.driver') === 'database';
        $cutoff = now()->subMinutes(15)->timestamp;
        return [
            'users' => User::query()->count(),
            'active' => $databaseSessions ? DB::table('sessions')->whereNotNull('user_id')->where('last_activity', '>=', $cutoff)->distinct()->count('user_id') : 0,
            'sessions' => $databaseSessions ? DB::table('sessions')->where('last_activity', '>=', $cutoff)->count() : 0,
        ];
    }

    /** @return LengthAwarePaginator<int, User> */
    public function getUsers(): LengthAwarePaginator
    {
        abort_unless(static::canAccess(), 403);
        $query = User::query()->select('users.id', 'users.name', 'users.email', 'users.created_at')->with('roles:id,name');
        if (config('session.driver') === 'database') {
            $sessions = DB::table('sessions')->select('user_id')
                ->selectRaw('MAX(last_activity) as last_seen, COUNT(*) as session_count')
                ->whereNotNull('user_id')->groupBy('user_id');
            $query->addSelect('usage.last_seen', 'usage.session_count')
                ->leftJoinSub($sessions, 'usage', fn ($join) => $join->on('users.id', '=', 'usage.user_id'));
        } else {
            $query->selectRaw('NULL AS last_seen, 0 AS session_count');
        }
        return $query
            ->when(trim($this->search) !== '', function ($query) {
                $query->where(function ($sub) {
                    $sub->whereLike('users.name', '%'.trim($this->search).'%')
                        ->orWhereLike('users.email', '%'.trim($this->search).'%');
                });
            })
            ->when(config('session.driver') === 'database', fn ($q) => $q->orderByDesc('usage.last_seen'))
            ->orderBy('users.id')
            ->paginate(15);
    }
}
