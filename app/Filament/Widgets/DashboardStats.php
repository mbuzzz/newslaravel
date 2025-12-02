<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalPosts = Post::count();
        $publishedPosts = Post::where('is_published', true)->count();
        $totalViews = Post::sum('view_count');
        $totalUsers = User::count();
        $pendingComments = Comment::where('is_approved', false)->count();

        return [
            Stat::make('Artikel', $totalPosts)
                ->description("{$publishedPosts} terbit")
                ->color('primary')
                ->icon('heroicon-o-document-text')
                ->extraAttributes(['class' => 'shadow-sm border-0']),
            Stat::make('Views', number_format($totalViews))
                ->description('Total pembacaan')
                ->color('success')
                ->icon('heroicon-o-eye')
                ->extraAttributes(['class' => 'shadow-sm border-0']),
            Stat::make('Pengguna', $totalUsers)
                ->description('Pengguna terdaftar')
                ->color('info')
                ->icon('heroicon-o-user-group')
                ->extraAttributes(['class' => 'shadow-sm border-0']),
            Stat::make('Komentar pending', $pendingComments)
                ->description('Perlu ditinjau')
                ->color('danger')
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->extraAttributes(['class' => 'shadow-sm border-0']),
        ];
    }
}
