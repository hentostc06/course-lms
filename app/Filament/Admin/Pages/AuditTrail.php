<?php
namespace App\Filament\Admin\Pages;

use App\Domain\Identity\Models\AdminAuditEvent;
use App\Filament\Admin\AdminPage;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AuditTrail extends AdminPage implements HasTable
{
    use InteractsWithTable;

    protected static ?string $slug = 'riwayat-audit';
    protected static string | \UnitEnum | null $navigationGroup = 'Audit & Monitoring';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $title = 'Riwayat audit admin';
    protected static ?int $navigationSort = 5;
    protected string $view = 'filament.admin.pages.table';

    public function table(Table $table): Table
    {
        return $table->query(
            AdminAuditEvent::query()->with('actor')
        )
        ->columns([
            TextColumn::make('created_at')
                ->label('Waktu (WIB)')
                ->dateTime('d M Y H:i:s', 'Asia/Jakarta')
                ->sortable(),

            TextColumn::make('actor.name')
                ->label('Admin')
                ->searchable(),

            TextColumn::make('event_type')
                ->label('Peristiwa')
                ->badge()
                ->searchable(),

            TextColumn::make('subject_type')
                ->label('Entitas'),

            TextColumn::make('subject_id')
                ->label('ID')
                ->sortable(),

            TextColumn::make('from_status')
                ->label('Sebelum')
                ->limit(80)
                ->wrap(),

            TextColumn::make('to_status')
                ->label('Sesudah')
                ->limit(80)
                ->wrap(),

            TextColumn::make('note')
                ->label('Alasan')
                ->limit(160)
                ->wrap(),

            TextColumn::make('request_id')
                ->label('ID audit')
                ->copyable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            SelectFilter::make('event_type')
                ->label('Peristiwa')
                ->options([
                    'course.approve' => 'Kursus disetujui',
                    'course.reject' => 'Kursus ditolak',
                    'course.archive' => 'Kursus diarsipkan',
                    'instructor.approve' => 'Instruktur disetujui',
                    'instructor.suspend' => 'Instruktur ditangguhkan',
                    'instructor.restore' => 'Instruktur dipulihkan',
                    'category.created' => 'Kategori dibuat',
                    'category.updated' => 'Kategori diubah',
                    'admin.created' => 'Admin dibuat',
                    'admin.password_reset' => 'Password admin direset',
                    'superadmin.password_changed' => 'Password superadmin diubah',
                ]),
        ])
        ->defaultSort('created_at', 'desc')
        ->paginated([15, 30, 50]);
    }
}
