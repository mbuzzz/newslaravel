<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PendingComments extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 3;

    protected function getTableQuery(): Builder
    {
        return Comment::query()
            ->where('is_approved', false)
            ->with(['post', 'user'])
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('user.name')
                ->label('User')
                ->placeholder('Anonim'),
            Tables\Columns\TextColumn::make('post.title')
                ->label('Artikel')
                ->limit(40)
                ->wrap()
                ->placeholder('-'),
            Tables\Columns\TextColumn::make('content')
                ->label('Komentar')
                ->limit(50)
                ->wrap(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Masuk')
                ->since(),
        ];
    }
}
