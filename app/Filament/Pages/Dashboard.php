<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardStats;
use App\Filament\Widgets\LatestPosts;
use App\Filament\Widgets\PendingComments;
use App\Filament\Widgets\WelcomeBanner;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected function getHeaderWidgets(): array
    {
        return [
            WelcomeBanner::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            DashboardStats::class,
            LatestPosts::class,
            PendingComments::class,
        ];
    }
}
