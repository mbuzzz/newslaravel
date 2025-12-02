<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;

class Register extends BaseRegister
{
    // Tambahkan field custom jika perlu (misal: No HP)
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ])
            ->statePath('data');
    }

    // Logic saat tombol "Daftar" ditekan
    protected function handleRegistration(array $data): Model
    {
        // 1. Jalankan proses registrasi standar (Create User)
        $user = parent::handleRegistration($data);

        // 2. Otomatis berikan role "reader"
        $user->assignRole('reader');

        return $user;
    }
}