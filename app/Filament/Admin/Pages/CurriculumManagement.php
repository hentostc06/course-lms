<?php

namespace App\Filament\Admin\Pages;

use App\Domain\Catalog\Enums\CourseStatus;
use App\Domain\Catalog\Enums\LessonType;
use App\Domain\Catalog\Models\Course;
use App\Domain\Catalog\Models\CourseSection;
use App\Domain\Catalog\Models\Lesson;
use App\Domain\Identity\Models\AdminAuditEvent;
use App\Filament\Admin\AdminPage;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/** Edit/remove course lessons in the admin panel, without bypassing moderation. */
class CurriculumManagement extends AdminPage implements HasTable
{
    use InteractsWithTable;

    protected static ?string $slug = 'editor-materi';
    protected static ?string $title = 'Editor materi';
    protected static ?int $navigationSort = 2;
    protected static string | \UnitEnum | null $navigationGroup = 'Konten & Kursus';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected string $view = 'filament.admin.pages.table';

    private function editable(Lesson $lesson): bool
    {
        return in_array($lesson->section->course->status,
            [CourseStatus::Draft, CourseStatus::Rejected], true);
    }

    private function saveArticle(Lesson $lesson, array $input): void
    {
        abort_unless(static::canAccess(), 403);
        $data = Validator::make($input, [
            'title' => ['required', 'string', 'min:3', 'max:180'],
            'content' => ['required', 'string', 'max:20000'],
            'duration_seconds' => ['required', 'integer', 'min:0', 'max:86400'],
        ])->validate();
        if (trim($data['content']) === '') {
            throw ValidationException::withMessages(['content' => 'Konten artikel tidak boleh kosong.']);
        }

        DB::transaction(function () use ($lesson, $data): void {
            $courseId = $lesson->section->course_id;
            $course = Course::query()->lockForUpdate()->findOrFail($courseId);
            abort_unless(in_array($course->status,
                [CourseStatus::Draft, CourseStatus::Rejected], true), 409,
                'Kursus sudah terkunci oleh alur moderasi.');
            $locked = Lesson::query()->lockForUpdate()->findOrFail($lesson->id);
            abort_unless($locked->section->course_id === $course->id, 404);
            $before = json_encode($locked->only(['title', 'type', 'duration_seconds']), JSON_THROW_ON_ERROR);
            $locked->update([
                'title' => $data['title'],
                'type' => LessonType::Article,
                'content' => trim($data['content']),
                'duration_seconds' => $data['duration_seconds'],
            ]);
            $this->recalculateDuration($course);
            AdminAuditEvent::record(auth()->user(), 'catalog.article_updated', 'course', $course->id,
                $before, 'lesson:'.$locked->id);
        });
    }

    private function removeArticle(Lesson $lesson): void
    {
        abort_unless(static::canAccess(), 403);
        DB::transaction(function () use ($lesson): void {
            $course = Course::query()->lockForUpdate()->findOrFail($lesson->section->course_id);
            abort_unless(in_array($course->status,
                [CourseStatus::Draft, CourseStatus::Rejected], true), 409,
                'Kursus sudah terkunci oleh alur moderasi.');
            $locked = Lesson::query()->lockForUpdate()->findOrFail($lesson->id);
            abort_unless($locked->section->course_id === $course->id, 404);
            if (DB::table('lesson_progress')->where('lesson_id', $locked->id)->exists()) {
                throw ValidationException::withMessages([
                    'lesson' => 'Materi memiliki riwayat progres siswa dan tidak boleh dihapus.',
                ]);
            }
            $locked->delete();
            $this->recalculateDuration($course);
            AdminAuditEvent::record(auth()->user(), 'catalog.article_deleted', 'course', $course->id,
                'lesson:'.$locked->id, null);
        });
    }

    private function recalculateDuration(Course $course): void
    {
        $seconds = $course->sections()->with('lessons')->get()
            ->sum(fn (CourseSection $section) => $section->lessons->sum('duration_seconds'));
        $course->update(['total_duration_minutes' => (int) ceil($seconds / 60)]);
    }

    public function table(Table $table): Table
    {
        return $table->query(Lesson::query()->with('section.course'))
            ->columns([
                TextColumn::make('title')->label('Materi')->searchable()->wrap(),
                TextColumn::make('section.course.title')->label('Kursus')->searchable()->wrap(),
                TextColumn::make('section.title')->label('Bab')->wrap(),
                TextColumn::make('type')->label('Jenis')->badge()
                    ->formatStateUsing(fn (LessonType $state): string => $state->value),
                TextColumn::make('section.course.status')->label('Status kursus')->badge()
                    ->formatStateUsing(fn (CourseStatus $state): string => $state->value),
                TextColumn::make('duration_seconds')->label('Durasi (detik)')->numeric(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Perbaiki artikel')
                    ->visible(fn (Lesson $record): bool => $this->editable($record))
                    ->schema([
                        TextInput::make('title')->label('Judul')->required()->minLength(3)->maxLength(180),
                        Textarea::make('content')->label('Isi artikel')->required()->rows(10)->maxLength(20000)
                            ->helperText('Materi Video/Dokumen placeholder akan dikonversi menjadi Artikel setelah disimpan.'),
                        TextInput::make('duration_seconds')->label('Durasi (detik)')->integer()
                            ->required()->minValue(0)->maxValue(86400),
                    ])
                    ->fillForm(fn (Lesson $record): array => [
                        'title' => $record->title,
                        'content' => $record->content ?? '',
                        'duration_seconds' => $record->duration_seconds,
                    ])
                    ->action(fn (Lesson $record, array $data) => $this->saveArticle($record, $data)),
                Action::make('delete')
                    ->label('Hapus materi')
                    ->color('danger')->requiresConfirmation()
                    ->visible(fn (Lesson $record): bool => $this->editable($record))
                    ->action(fn (Lesson $record) => $this->removeArticle($record)),
            ])
            ->defaultSort('updated_at', 'desc')
            ->paginated([15, 30, 50]);
    }
}
