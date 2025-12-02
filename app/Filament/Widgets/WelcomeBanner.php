<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class WelcomeBanner extends Widget
{
    protected static string $view = 'filament.widgets.welcome-banner';

    protected int|string|array $columnSpan = 'full';

    public function getUserName(): string
    {
        return Auth::user()?->name ?? 'Admin';
    }
}
