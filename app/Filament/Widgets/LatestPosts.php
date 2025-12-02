<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestPosts extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2;

    protected function getTableQuery(): Builder
    {
        return Post::query()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->take(10);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title')
                ->label('Judul')
                ->limit(40)
                ->wrap()
                ->sortable(),
            Tables\Columns\TextColumn::make('category.name')
                ->label('Kategori')
                ->badge()
                ->color('primary'),
            Tables\Columns\TextColumn::make('author.name')
                ->label('Penulis')
                ->placeholder('—'),
            Tables\Columns\IconColumn::make('is_editor_pick')
                ->label('Editorial')
                ->boolean(),
            Tables\Columns\TextColumn::make('view_count')
                ->label('Views')
                ->formatStateUsing(fn ($state) => number_format($state)),
            Tables\Columns\TextColumn::make('published_at')
                ->label('Tayang')
                ->dateTime('d M Y H:i'),
        ];
    }
}
