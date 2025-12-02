<?php

namespace App\Livewire;

use App\Settings\GeneralSettings;
use Livewire\Component;

class Register extends Component
{
    public function render()
    {
        $settings = app(GeneralSettings::class);

        return view('livewire.register', [
            'settings' => $settings,
        ])->layout('components.layouts.mobile', [
            'title' => 'Daftar',
            'description' => 'Buat akun baru',
            'headerTitle' => 'Daftar',
            'showBackButton' => true,
            'backUrl' => route('home'),
        ]);
    }
}
