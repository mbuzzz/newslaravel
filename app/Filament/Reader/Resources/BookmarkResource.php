<?php

namespace App\Filament\Reader\Resources;

use App\Filament\Reader\Resources\BookmarkResource\Pages;
use App\Models\Bookmark;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookmarkResource extends Resource
{
    protected static ?string $model = Bookmark::class;
    protected static ?string $navigationIcon = 'heroicon-o-bookmark';
    protected static ?string $navigationLabel = 'Artikel Disimpan';
    protected static ?string $slug = 'bookmarks';

    // Filter agar Reader HANYA melihat bookmark miliknya sendiri
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]); // Reader tidak perlu edit bookmark via form, cukup delete
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('post.thumbnail')
                    ->label('Cover')
                    ->square(),
                
                Tables\Columns\TextColumn::make('post.title')
                    ->label('Judul Artikel')
                    ->searchable()
                    ->weight('bold')
                    ->url(fn (Bookmark $record) => route('post.show', ['category' => $record->post->category->slug, 'slug' => $record->post->slug]))
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Disimpan Pada')
                    ->date(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookmarks::route('/'),
        ];
    }
}