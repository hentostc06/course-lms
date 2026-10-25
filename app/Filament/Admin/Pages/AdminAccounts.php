<?php
namespace App\Filament\Admin\Pages;

use App\Models\User;
use App\Domain\Identity\Models\Role;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AdminAccounts extends Page
{
    protected static ?string $slug = 'kelola-admin';
    protected string $view = 'filament.admin.pages.admin-accounts';

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?int $selectedAdminId = null;
    public string $resetPassword = '';
    public string $resetPasswordConfirmation = '';
    public string $currentPassword = '';
    public string $ownPassword = '';
    public string $ownPasswordConfirmation = '';
    public string $notice = '';

    public static function canAccess(): bool { return auth()->user()?->hasRole('super_admin') === true; }
    public static function getNavigationLabel(): string { return 'Kelola administrator'; }
    public function getTitle(): string { return 'Pengelolaan administrator'; }
    public function mount(): void { abort_unless(static::canAccess(), 403); }
    private function guard(): void { abort_unless(static::canAccess(), 403); }

    public function getAdmins()
    {
        $this->guard();
        return User::query()->whereHas('roles', fn ($q) => $q->whereIn('name', ['admin', 'super_admin']))
            ->with('roles:id,name')->orderBy('name')->limit(100)->get();
    }

    public function createAdmin(): void
    {
        $this->guard();
        $data = Validator::make([
            'name' => $this->name, 'email' => $this->email, 'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ], [
            'name' => ['required','string','min:3','max:120'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','confirmed',Password::min(12)->mixedCase()->numbers()],
        ])->validate();
        $user = DB::transaction(function () use ($data) {
            $role = Role::query()->where('name', 'admin')->firstOrFail();
            $created = User::query()->create([
                'name' => $data['name'], 'email' => $data['email'],
                'password' => $data['password'],
            ]);
            $created->forceFill(['email_verified_at' => now()])->save();
            $created->roles()->syncWithoutDetaching([$role->id]);
            return $created;
        });
        Log::notice('admin.created', ['actor' => auth()->id(), 'target' => $user->id]);
        $this->reset('name','email','password','password_confirmation');
        $this->notice = 'Akun admin baru berhasil dibuat.';
    }

    public function selectAdmin(int $id): void
    {
        $this->guard();
        abort_unless(User::query()->whereKey($id)->whereHas('roles', fn ($q) => $q->where('name','admin'))
            ->whereDoesntHave('roles', fn ($q) => $q->where('name','super_admin'))->exists(), 403);
        $this->selectedAdminId = $id;
        $this->reset('resetPassword','resetPasswordConfirmation');
    }

    public function resetAdminPassword(): void
    {
        $this->guard();
        $data = Validator::make([
            'password' => $this->resetPassword,
            'password_confirmation' => $this->resetPasswordConfirmation,
        ], ['password' => ['required','confirmed',Password::min(12)->mixedCase()->numbers()]])->validate();
        DB::transaction(function () use ($data) {
            $admin = User::query()->lockForUpdate()->findOrFail($this->selectedAdminId);
            abort_unless($admin->id !== auth()->id()
                && $admin->hasRole('admin') && ! $admin->hasRole('super_admin'), 403);
            $admin->forceFill(['password' => $data['password']])->save();
            if (config('session.driver') === 'database') {
                DB::table('sessions')->where('user_id', $admin->id)->delete();
            }
            Log::warning('admin.password_reset', ['actor' => auth()->id(), 'target' => $admin->id]);
        });
        $this->reset('selectedAdminId','resetPassword','resetPasswordConfirmation');
        $this->notice = 'Password admin diperbarui. Sesi database admin tersebut dicabut.';
    }

    public function updateOwnPassword(): void
    {
        $this->guard();
        $user = auth()->user();
        if (! Hash::check($this->currentPassword, $user->password)) { $this->addError('currentPassword', 'Password saat ini salah.'); return; }
        $data = Validator::make([
            'password' => $this->ownPassword,
            'password_confirmation' => $this->ownPasswordConfirmation,
        ], ['password' => ['required','confirmed',Password::min(12)->mixedCase()->numbers()]])->validate();
        $user->forceFill(['password' => $data['password']])->save();
        if (config('session.driver') === 'database') {
            DB::table('sessions')->where('user_id', $user->id)
                ->where('id', '<>', session()->getId())->delete();
        }
        Log::notice('superadmin.password_changed', ['actor' => $user->id]);
        $this->reset('currentPassword','ownPassword','ownPasswordConfirmation');
        $this->notice = 'Password akun Anda diperbarui.';
    }
}
