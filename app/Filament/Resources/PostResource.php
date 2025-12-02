<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder; // Import Wajib untuk Query Builder

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    // --- KONFIGURASI MENU ---
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Manajemen Berita';
    protected static ?string $navigationLabel = 'Artikel';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'title';
    // ------------------------

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Group::make()
                            ->columnSpan(['lg' => 2])
                            ->schema([
                                Section::make('Konten Utama')
                                    ->description('Tulis judul yang menarik dan isi berita yang informatif.')
                                    ->icon('heroicon-o-document-text')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Judul Artikel')
                                            ->placeholder('Masukkan judul utama...')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => 
                                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                                            )
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('slug')
                                            ->label('Permalink')
                                            ->prefix(function (Get $get) {
                                                $catId = $get('category_id');
                                                $categorySlug = 'uncategorized';
                                                
                                                if ($catId) {
                                                    $category = Category::find($catId);
                                                    if ($category) {
                                                        $categorySlug = $category->slug;
                                                    }
                                                }
                                                return url('/') . '/' . $categorySlug . '/';
                                            })
                                            ->disabled()
                                            ->dehydrated()
                                            ->required()
                                            ->unique(Post::class, 'slug', ignoreRecord: true),

                                        Forms\Components\RichEditor::make('content')
                                            ->label('Isi Berita')
                                            ->fileAttachmentsDirectory('posts-content')
                                            ->required()
                                            ->toolbarButtons([
                                                'attachFiles', 'blockquote', 'bold', 'bulletList', 'codeBlock', 'h2', 'h3', 'italic', 'link', 'orderedList', 'redo', 'strike', 'underline', 'undo',
                                            ]),
                                    ]),

                                Tabs::make('Detail Berita')
                                    ->tabs([
                                        Tabs\Tab::make('Redaksi')
                                            ->icon('heroicon-o-users')
                                            ->schema([
                                                Grid::make(2)->schema([
                                                    Forms\Components\Select::make('user_id')
                                                        ->label('Penulis Internal')
                                                        ->relationship('author', 'name')
                                                        ->default(auth()->id())
                                                        ->searchable()
                                                        ->preload(),
                                                    Forms\Components\TextInput::make('custom_author')
                                                        ->label('Penulis Tamu'),
                                                ]),
                                                Grid::make(2)->schema([
                                                    Forms\Components\TextInput::make('editor')
                                                        ->label('Editor'),
                                                    Forms\Components\TextInput::make('narasumber')
                                                        ->label('Narasumber'),
                                                ]),
                                            ]),
                                        Tabs\Tab::make('SEO')
                                            ->icon('heroicon-o-globe-alt')
                                            ->schema([
                                                Forms\Components\TextInput::make('meta_title')->maxLength(60),
                                                Forms\Components\Textarea::make('meta_description')->maxLength(160),
                                                Forms\Components\TagsInput::make('meta_keywords'),
                                            ]),
                                    ]),
                            ]),

                        Group::make()
                            ->columnSpan(['lg' => 1])
                            ->schema([
                                Section::make('Status & Kategori')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_published')
                                            ->label('Terbitkan')
                                            ->default(true)
                                            ->onColor('success')
                                            ->offColor('danger'),
                                        
                                        Forms\Components\Toggle::make('is_editor_pick')
                                            ->label('Editorial Pick')
                                            ->helperText('Tandai untuk masuk ke sorotan editorial.')
                                            ->default(false)
                                            ->onColor('primary')
                                            ->offColor('gray'),
                                        
                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->label('Jadwal Tayang')
                                            ->default(now()),

                                        Forms\Components\Select::make('category_id')
                                            ->label('Kategori')
                                            ->relationship('category', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->required()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn (Forms\Set $set, $state) => $set('slug', Str::slug($state))),
                                                Forms\Components\TextInput::make('slug')->required(),
                                            ]),

                                        Forms\Components\Select::make('tags')
                                            ->relationship('tags', 'name')
                                            ->multiple()
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn (Forms\Set $set, $state) => $set('slug', Str::slug($state))),
                                                Forms\Components\TextInput::make('slug')->required(),
                                            ]),

                                        Forms\Components\FileUpload::make('thumbnail')
                                            ->label('Sampul')
                                            ->image()
                                            ->imageEditor()
                                            ->directory('posts-thumbnails')
                                            ->required(),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // PERBAIKAN TYPE HINT DI SINI
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount(['comments', 'likes', 'bookmarks']))
            ->columns([
                // Kolom Sampul
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Sampul')
                    ->square()
                    ->size(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                
                // Kolom Artikel & Penulis
                Tables\Columns\TextColumn::make('title')
                    ->label('Artikel')
                    ->searchable()
                    ->limit(30)
                    ->description(fn (Post $record): string => Str::limit($record->slug, 30))
                    ->weight('bold')
                    ->wrap(),

                Tables\Columns\TextColumn::make('author_display')
                    ->label('Penulis')
                    ->state(fn (Post $record) => $record->custom_author ?? $record->author?->name)
                    ->icon('heroicon-m-user')
                    ->toggleable(),

                // Kolom Kategori
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->toggleable(),

                // --- KOLOM INTERAKSI BARU ---
                Tables\Columns\TextColumn::make('views')
                    ->label('Views')
                    ->state(fn (Post $record) => number_format($record->view_count))
                    ->icon('heroicon-m-eye')
                    ->sortable()
                    ->color('gray')
                    ->alignCenter()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('comments_count')
                    ->label('Komen')
                    ->counts('comments')
                    ->icon('heroicon-m-chat-bubble-bottom-center')
                    ->color('info')
                    ->alignCenter()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('likes_count')
                    ->label('Suka')
                    ->counts('likes')
                    ->icon('heroicon-m-heart')
                    ->color('danger')
                    ->alignCenter()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('bookmarks_count')
                    ->label('Simpan')
                    ->counts('bookmarks')
                    ->icon('heroicon-m-bookmark')
                    ->color('warning')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),
                // ---------------------------

                // Kolom Status & Tanggal
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('is_editor_pick')
                    ->label('Editorial')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-shield-exclamation')
                    ->trueColor('primary')
                    ->falseColor('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Tayang')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Filter Kategori'),
                
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Status Terbit'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
