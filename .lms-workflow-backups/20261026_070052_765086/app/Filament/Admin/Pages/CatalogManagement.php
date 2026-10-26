<?php
namespace App\Filament\Admin\Pages;

use App\Domain\Catalog\Models\Course;
use App\Domain\Catalog\Actions\SubmitCourseForReview;
use Filament\Notifications\Notification;
use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Enums\CourseLevel;
use App\Domain\Catalog\Enums\CourseStatus;
use App\Domain\Instructor\Models\InstructorProfile;
use App\Domain\Instructor\Enums\InstructorStatus;
use App\Domain\Identity\Models\AdminAuditEvent;
use App\Filament\Admin\AdminPage;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CatalogManagement extends AdminPage implements HasTable
{
    use InteractsWithTable;

    protected static ?string $slug = 'manajemen-katalog';
    protected static ?string $title = 'Manajemen katalog';
    protected static ?int $navigationSort = 1;
    protected static string | \UnitEnum | null $navigationGroup = 'Konten & Kursus';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected string $view = 'filament.admin.pages.table';

    private function fields(): array
    {
        return [
            TextInput::make('title')
                ->label('Judul kursus')->required()->maxLength(180),

            Textarea::make('summary')
                ->label('Ringkasan')->required()->maxLength(320),

            Textarea::make('description')
                ->label('Deskripsi')->required(),

            Select::make('instructor_id')
                ->label('Instruktur')
                ->required()
                ->searchable()
                ->options(fn (): array =>
                    InstructorProfile::query()
                        ->where('status', InstructorStatus::Approved)
                        ->with('user')
                        ->get()
                        ->mapWithKeys(
                            fn ($profile): array =>
                                [$profile->id => $profile->user->name]
                        )->all()
                ),

            Select::make('category_id')
                ->label('Kategori')
                ->required()
                ->searchable()
                ->options(fn (): array =>
                    Category::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all()
                ),

            Select::make('level')
                ->label('Tingkat')
                ->required()
                ->options(
                    collect(CourseLevel::cases())
                        ->mapWithKeys(
                            fn (CourseLevel $level): array =>
                                [$level->value => $level->label()]
                        )->all()
                ),

            TextInput::make('price_idr')
                ->label('Harga (Rp)')
                ->integer()
                ->minValue(0)
                ->required(),

            FileUpload::make('cover_upload')
                ->label('Cover kursus (PNG, JPG, WebP)')
                ->disk('public')
                ->directory('catalog/courses')
                ->visibility('public')
                ->image()
                ->imagePreviewHeight('160')
                ->panelAspectRatio('16:9')
                ->acceptedFileTypes([
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                ])
                ->maxSize(4096)
                ->preventFilePathTampering()
                ->helperText(
                    'Rasio 16:9, maksimal 4 MB. '
                    .'Kosongkan jika tidak mengganti cover.'
                ),
        ];
    }

    private function store(array $input, ?Course $original = null): void
    {
        abort_unless(static::canAccess(), 403);

        $data = Validator::make($input, [
            'title' => ['required','string','min:3','max:180'],
            'summary' => ['required','string','max:320'],
            'description' => ['required','string'],
            'instructor_id' => [
                'required','integer',
                Rule::exists('instructor_profiles','id')
                    ->where('status','approved')
            ],
            'category_id' => [
                'required','integer','exists:categories,id'
            ],
            'level' => [
                'required',
                Rule::in(array_column(CourseLevel::cases(),'value'))
            ],
            'price_idr' => ['required','integer','min:0'],
            'cover_upload' => ['nullable','string'],
        ])->validate();

        $image = $data['cover_upload'] ?? null;

        if ($image !== null && (
            ! preg_match(
                '~^catalog/courses/[a-zA-Z0-9/_-]+\.(png|jpg|jpeg|webp)$~i',
                $image
            )
            || ! Storage::disk('public')->exists($image)
        )) {
            throw ValidationException::withMessages([
                'cover_upload' => 'File cover tidak valid.'
            ]);
        }

        DB::transaction(function () use ($data, $original, $image): void {
            $record = $original
                ? Course::query()->lockForUpdate()->findOrFail($original->id)
                : new Course;

            $before = $record->exists
                ? json_encode($record->only([
                    'title', 'thumbnail_url', 'category_id'
                ]), JSON_THROW_ON_ERROR)
                : null;

            $record->fill([
                'title' => $data['title'],
                'summary' => $data['summary'],
                'description' => $data['description'],
                'instructor_id' => $data['instructor_id'],
                'category_id' => $data['category_id'],
                'level' => $data['level'],
                'price_idr' => (int) $data['price_idr'],
            ]);

            if (! $record->exists) {
                $record->slug = Str::slug($data['title'])
                    .'-'.Str::lower(Str::random(8));

                $record->outcomes = [];
                $record->requirements = [];
                $record->audience = [];
                $record->language = 'id';
                $record->status = CourseStatus::Draft;
                $record->total_duration_minutes = 0;
            }

            if ($image !== null) {
                $record->thumbnail_url =
                    Storage::disk('public')->url($image);
            }

            $record->save();

            AdminAuditEvent::record(
                auth()->user(),
                $original ? 'catalog.updated' : 'catalog.created',
                'course',
                $record->id,
                $before,
                json_encode(
                    $record->only([
                        'title','thumbnail_url','category_id'
                    ]),
                    JSON_THROW_ON_ERROR
                )
            );
        });
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Course::query()->with([
                'instructor.user',
                'category',
            ]))
            ->columns([
                ImageColumn::make('thumbnail_url')
                    ->label('Cover')
                    ->square(),

                TextColumn::make('title')
                    ->label('Kursus')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('instructor.user.name')
                    ->label('Instruktur'),

                TextColumn::make('category.name')
                    ->label('Kategori'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (CourseStatus $state): string =>
                            match ($state) {
                                CourseStatus::Draft => 'Draf',
                                CourseStatus::PendingReview => 'Menunggu review',
                                CourseStatus::Published => 'Terbit',
                                CourseStatus::Rejected => 'Ditolak',
                                CourseStatus::Archived => 'Arsip',
                            }
                    ),

                TextColumn::make('price_idr')
                    ->label('Harga')
                    ->money('IDR', locale: 'id'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draf',
                        'pending_review' => 'Menunggu review',
                        'published' => 'Terbit',
                        'rejected' => 'Ditolak',
                        'archived' => 'Arsip',
                    ]),
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Tambah kursus (draf)')
                    ->schema($this->fields())
                    ->action(
                        fn (array $data) => $this->store($data)
                    ),
            ])
            ->recordActions([
                Action::make('edit')
                    ->modalContent(
                        fn (Course $record): \Illuminate\Contracts\View\View =>
                            view(
                                'filament.admin.course-current-cover',
                                ['course' => $record]
                            )
                    )
                    ->label('Edit / cover')
                    ->schema($this->fields())
                    ->fillForm(
                        fn (Course $record): array => [
                            'title' => $record->title,
                            'summary' => $record->summary,
                            'description' => $record->description,
                            'instructor_id' => $record->instructor_id,
                            'category_id' => $record->category_id,
                            'level' => $record->level->value,
                            'price_idr' => $record->price_idr,
                        ]
                    )
                    ->action(
                        fn (Course $record, array $data) =>
                            $this->store($data, $record)
                    ),
            
                Action::make('submit_review')
                    ->label('Ajukan review')
                    ->color('warning')
                    ->icon('heroicon-o-paper-airplane')
                    ->visible(
                        fn (Course $record): bool =>
                            in_array(
                                $record->status,
                                [
                                    CourseStatus::Draft,
                                    CourseStatus::Rejected,
                                ],
                                true
                            )
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Ajukan kursus ke moderasi?')
                    ->modalDescription(
                        'Status akan menjadi Menunggu review. '
                        .'Kursus diterbitkan setelah disetujui pada menu Moderasi.'
                    )
                    ->action(function (Course $record): void {
                        try {
                            app(SubmitCourseForReview::class)->handle(
                                auth()->user(),
                                $record
                            );

                            Notification::make()
                                ->title('Berhasil diajukan untuk review')
                                ->success()
                                ->send();

                        } catch (\Illuminate\Validation\ValidationException $exception) {
                            $messages = array_merge(
                                ...array_values($exception->errors())
                            );

                            Notification::make()
                                ->title('Kursus belum siap diajukan')
                                ->body(implode(' ', $messages))
                                ->danger()
                                ->send();
                        }
                    }),
])
            ->defaultSort('updated_at', 'desc')
            ->paginated([15,30,50]);
    }
}
