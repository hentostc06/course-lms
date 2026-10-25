<?php
namespace App\Filament\Admin\Pages;

use App\Filament\Admin\AdminPage;
use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersDirectory extends AdminPage implements HasTable
{
    use InteractsWithTable;

    protected static ?string $slug = 'pengguna';
    protected static string | \UnitEnum | null $navigationGroup = 'Pengguna & Akses';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';
    protected static ?string $title = 'Direktori pengguna';
    protected static ?int $navigationSort = 4;
    protected string $view = 'filament.admin.pages.table';

    public function table(Table $table): Table
    {
        return $table->query(
            User::query()->with('roles')
        )
        ->columns([
            TextColumn::make('name')
                ->label('Nama')
                ->searchable()
                ->sortable(),

            TextColumn::make('email')
                ->label('Email')
                ->searchable()
                ->copyable(),

            TextColumn::make('roles.name')
                ->label('Peran')
                ->badge()
                ->separator(', '),

            TextColumn::make('verification')
                ->label('Email')
                ->state(
                    fn (User $record): string =>
                    $record->hasVerifiedEmail()
                        ? 'Terverifikasi'
                        : 'Belum diverifikasi'
                )
                ->badge()
                ->color(
                    fn (string $state): string =>
                    $state === 'Terverifikasi'
                        ? 'success'
                        : 'warning'
                ),

            TextColumn::make('created_at')
                ->label('Terdaftar')
                ->dateTime('d M Y H:i', 'Asia/Jakarta')
                ->sortable(),
        ])
        ->filters([
            SelectFilter::make('role')
                ->label('Peran')
                ->relationship('roles', 'name')
                    ->getOptionLabelFromRecordUsing(
                        fn (\App\Domain\Identity\Models\Role $record): string => match ($record->name) {
                            \App\Domain\Identity\Enums\RoleName::SuperAdmin => 'Superadmin',
                            \App\Domain\Identity\Enums\RoleName::Admin => 'Admin',
                            \App\Domain\Identity\Enums\RoleName::Instructor => 'Instruktur',
                            \App\Domain\Identity\Enums\RoleName::Student => 'Siswa',
                        }
                    )
                ->multiple()
                ->preload(),
        ])
        ->defaultSort('created_at', 'desc')
        ->paginated([15, 30, 50]);
    }
}
