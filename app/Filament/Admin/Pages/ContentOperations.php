<?php

namespace App\Filament\Admin\Pages;

use App\Domain\Catalog\Actions\ReviewCourse;
use App\Domain\Catalog\Enums\CourseStatus;
use App\Domain\Catalog\Models\Course;
use App\Filament\Admin\AdminPage;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;

class ContentOperations extends AdminPage implements HasTable
{
    use InteractsWithTable;

    protected static ?string $slug = 'operasional';
    protected static string | \UnitEnum | null $navigationGroup = 'Konten & Kursus';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $title = 'Kursus & moderasi';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.admin.pages.table';

    public function table(Table $table): Table
    {
        return $table->query(Course::query()->with(['instructor.user', 'category']))
            ->columns([
                TextColumn::make('title')->label('Kursus')->searchable()->sortable()->wrap(),
                TextColumn::make('instructor.user.name')->label('Instruktur')->searchable(),
                TextColumn::make('category.name')->label('Kategori'),
                TextColumn::make('status')->label('Status')->badge()->formatStateUsing(fn (CourseStatus $state): string => match ($state) {
                    CourseStatus::Draft => 'Draf', CourseStatus::PendingReview => 'Menunggu review',
                    CourseStatus::Published => 'Terbit', CourseStatus::Rejected => 'Ditolak', CourseStatus::Archived => 'Diarsipkan',
                }),
                TextColumn::make('price_idr')->label('Harga (IDR)')->numeric(decimalPlaces: 0)->sortable(),
                TextColumn::make('updated_at')->label('Diperbarui (WIB)')->dateTime('d M Y H:i', 'Asia/Jakarta')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Status')->options([
                    'draft' => 'Draf', 'pending_review' => 'Menunggu review', 'published' => 'Terbit',
                    'rejected' => 'Ditolak', 'archived' => 'Diarsipkan',
                ])->default('pending_review'),
                SelectFilter::make('category')->label('Kategori')->relationship('category', 'name')->searchable()->preload(),
            ])
            ->recordActions([
                Action::make('inspect')->label('Periksa konten')->modalHeading('Detail & kurikulum')
                    ->modalContent(fn (Course $record): View => view('filament.admin.course-review', ['course' => $record->load('sections.lessons')]))
                    ->modalSubmitAction(false)->modalCancelActionLabel('Tutup'),
                Action::make('approve')->label('Terbitkan')->color('success')->requiresConfirmation()
                    ->visible(fn (Course $record): bool => $record->status === CourseStatus::PendingReview)
                    ->schema([Textarea::make('reason')->label('Catatan peninjauan')->required()->minLength(10)->maxLength(2000)])
                    ->action(fn (Course $record, array $data) => app(ReviewCourse::class)->handle(auth()->user(), $record, 'approve', $data['reason'])),
                Action::make('reject')->label('Tolak')->color('danger')->requiresConfirmation()
                    ->visible(fn (Course $record): bool => $record->status === CourseStatus::PendingReview)
                    ->schema([Textarea::make('reason')->label('Alasan penolakan')->required()->minLength(10)->maxLength(2000)])
                    ->action(fn (Course $record, array $data) => app(ReviewCourse::class)->handle(auth()->user(), $record, 'reject', $data['reason'])),
                Action::make('archive')->label('Arsipkan')->color('warning')->requiresConfirmation()
                    ->modalDescription('Kursus akan berhenti tampil di katalog publik. Riwayat moderasi tetap disimpan.')
                    ->visible(fn (Course $record): bool => $record->status === CourseStatus::Published)
                    ->schema([Textarea::make('reason')->label('Alasan pengarsipan')->required()->minLength(10)->maxLength(2000)])
                    ->action(fn (Course $record, array $data) => app(ReviewCourse::class)->handle(auth()->user(), $record, 'archive', $data['reason'])),
            ])->defaultSort('updated_at', 'desc')->emptyStateHeading('Tidak ada kursus sesuai filter')->paginated([15, 30, 50]);
    }
}
