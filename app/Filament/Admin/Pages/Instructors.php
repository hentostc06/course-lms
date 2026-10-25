<?php

namespace App\Filament\Admin\Pages;

use App\Domain\Instructor\Actions\ReviewInstructor;
use App\Domain\Instructor\Enums\InstructorStatus;
use App\Domain\Instructor\Models\InstructorProfile;
use App\Filament\Admin\AdminPage;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;

class Instructors extends AdminPage implements HasTable
{
    use InteractsWithTable;

    protected static ?string $slug = 'instruktur';
    protected static string | \UnitEnum | null $navigationGroup = 'Konten & Kursus';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $title = 'Instruktur';
    protected static ?int $navigationSort = 2;
    protected string $view = 'filament.admin.pages.table';

    public function table(Table $table): Table
    {
        return $table->query(InstructorProfile::query()->with('user')->withCount('courses'))
            ->columns([
                TextColumn::make('user.name')->label('Nama')->searchable()->sortable(),
                TextColumn::make('user.email')->label('Email')->searchable(),
                TextColumn::make('headline')->label('Keahlian')->wrap(),
                TextColumn::make('status')->label('Status')->badge()->formatStateUsing(fn (InstructorStatus $state): string => match ($state) {
                    InstructorStatus::Pending => 'Menunggu review', InstructorStatus::Approved => 'Disetujui', InstructorStatus::Suspended => 'Ditangguhkan',
                }),
                TextColumn::make('courses_count')->label('Kursus')->sortable(),
                TextColumn::make('created_at')->label('Diajukan (WIB)')->dateTime('d M Y H:i', 'Asia/Jakarta')->sortable(),
            ])
            ->filters([SelectFilter::make('status')->label('Status')->options([
                'pending' => 'Menunggu review', 'approved' => 'Disetujui', 'suspended' => 'Ditangguhkan',
            ])])
            ->recordActions([
                Action::make('inspect')->label('Profil')->modalSubmitAction(false)->modalCancelActionLabel('Tutup')
                    ->modalContent(fn (InstructorProfile $record): View => view('filament.admin.instructor-review', ['profile' => $record])),
                Action::make('approve')->label('Setujui')->color('success')->requiresConfirmation()
                    ->visible(fn (InstructorProfile $record): bool => $record->status === InstructorStatus::Pending)
                    ->schema([Textarea::make('reason')->label('Catatan peninjauan')->required()->minLength(10)->maxLength(2000)])
                    ->action(fn (InstructorProfile $record, array $data) => app(ReviewInstructor::class)->handle(auth()->user(), $record, 'approve', $data['reason'])),
                Action::make('suspend')->label('Tangguhkan')->color('danger')->requiresConfirmation()
                    ->modalDescription('Instruktur tidak dapat mengubah kursus. Kursusnya tidak tampil di katalog publik selama penangguhan.')
                    ->visible(fn (InstructorProfile $record): bool => $record->status === InstructorStatus::Approved)
                    ->schema([Textarea::make('reason')->label('Alasan penangguhan')->required()->minLength(10)->maxLength(2000)])
                    ->action(fn (InstructorProfile $record, array $data) => app(ReviewInstructor::class)->handle(auth()->user(), $record, 'suspend', $data['reason'])),
                Action::make('restore')->label('Aktifkan kembali')->color('warning')->requiresConfirmation()
                    ->modalDescription('Kursus berstatus terbit akan tampil kembali di katalog publik.')
                    ->visible(fn (InstructorProfile $record): bool => $record->status === InstructorStatus::Suspended)
                    ->schema([Textarea::make('reason')->label('Alasan pemulihan')->required()->minLength(10)->maxLength(2000)])
                    ->action(fn (InstructorProfile $record, array $data) => app(ReviewInstructor::class)->handle(auth()->user(), $record, 'restore', $data['reason'])),
            ])->defaultSort('created_at', 'desc')->emptyStateHeading('Belum ada instruktur sesuai filter')->paginated([15, 30, 50]);
    }
}
