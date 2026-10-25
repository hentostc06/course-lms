<?php

namespace App\Filament\Admin\Pages;

use App\Domain\Catalog\Actions\SaveCategory;
use App\Domain\Catalog\Models\Category;
use App\Filament\Admin\AdminPage;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class Categories extends AdminPage implements HasTable
{
    use InteractsWithTable;

    protected static ?string $slug = 'kategori';
    protected static string | \UnitEnum | null $navigationGroup = 'Konten & Kursus';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-tag';
    protected static ?string $title = 'Kategori';
    protected static ?int $navigationSort = 3;
    protected string $view = 'filament.admin.pages.table';

    public function table(Table $table): Table
    {
        $schema = [
            TextInput::make('name')->label('Nama kategori')->required()->minLength(3)->maxLength(120),
            Textarea::make('description')->label('Deskripsi')->maxLength(1000),
            TextInput::make('sort_order')->label('Urutan tampil')->integer()->minValue(0)->maxValue(10000)->default(0)->required(),
            Toggle::make('is_active')->label('Aktif')->default(true)
                ->helperText('Kategori nonaktif tidak tampil di pilihan katalog dan formulir kursus baru.'),
        ];

        return $table->query(Category::query()->withCount('courses'))
            ->columns([
                TextColumn::make('name')->label('Kategori')->searchable()->sortable(),
                TextColumn::make('description')->label('Deskripsi')->limit(80)->wrap(),
                TextColumn::make('courses_count')->label('Kursus')->sortable(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                TextColumn::make('sort_order')->label('Urutan')->sortable(),
            ])
            ->filters([TernaryFilter::make('is_active')->label('Status kategori')])
            ->headerActions([
                Action::make('create')->label('Tambah kategori')->schema($schema)
                    ->action(fn (array $data) => app(SaveCategory::class)->handle(auth()->user(), $data)),
            ])
            ->recordActions([
                Action::make('edit')->label('Ubah')->fillForm(fn (Category $record): array => $record->only(['name', 'description', 'sort_order', 'is_active']))
                    ->schema($schema)->action(fn (Category $record, array $data) => app(SaveCategory::class)->handle(auth()->user(), $data, $record)),
            ])->defaultSort('sort_order')->emptyStateHeading('Belum ada kategori')->paginated([15, 30, 50]);
    }
}
