<?php

namespace App\Livewire;

use App\Settings\GeneralSettings;
use Livewire\Component;

class Login extends Component
{
    public function render()
    {
        $settings = app(GeneralSettings::class);

        return view('livewire.login', [
            'settings' => $settings,
        ])->layout('components.layouts.mobile', [
            'title' => 'Masuk',
            'description' => 'Masuk ke dashboard',
            'headerTitle' => 'Masuk',
            'showBackButton' => true,
            'backUrl' => route('home'),
        ]);
    }
}
