<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Actions; // Import Actions Form
use Filament\Forms\Components\Actions\Action as FormAction; // Alias untuk Action Form
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Filament\Actions\Action;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\HtmlString;

class EditProfile extends BaseEditProfile
{
    // Judul lebih umum
    public function getHeading(): string
    {
        return 'Edit Profil';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // 1. TOMBOL KEMBALI
                Actions::make([
                    FormAction::make('back_to_dashboard')
                        ->label('Kembali ke Dashboard')
                        ->icon('heroicon-m-arrow-left')
                        ->color('gray')
                        ->url(filament()->getUrl())
                        ->link()
                        ->tooltip('Klik untuk kembali ke halaman utama'),
                ])->alignment(Alignment::Right)->fullWidth(),

                Grid::make(['default' => 1, 'md' => 3])
                    ->schema([

                        // KOLOM 1: KARTU FOTO
                        Section::make()
                            ->columnSpan(1)
                            ->extraAttributes([
                                // Menggunakan warna Primary (Cyan/Blue) yang lebih general
                                'class' => 'transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl border-t-4 border-cyan-500 bg-white dark:bg-gray-800',
                            ])
                            ->schema([
                                FileUpload::make('avatar_url')
                                    ->label('Avatar')
                                    ->image()
                                    ->avatar()
                                    ->imageEditor()
                                    ->circleCropper()
                                    ->directory('avatars')
                                    ->visibility('public')
                                    ->alignCenter(),

                                // Badge Status General (Verified User)
                                \Filament\Forms\Components\Placeholder::make('status_badge')
                                    ->label('')
                                    ->content(fn () => new HtmlString('
                                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin-top: 1rem;">
                                            <!-- Badge Gradient Biru/Cyan (General Modern) -->
                                            <div style="
                                                display: inline-flex;
                                                align-items: center;
                                                padding: 0.5rem 1.25rem;
                                                border-radius: 9999px;
                                                background: linear-gradient(90deg, #06b6d4 0%, #3b82f6 100%);
                                                color: white;
                                                font-weight: bold;
                                                font-size: 0.875rem;
                                                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                                            ">
                                                <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Verified User</span>
                                            </div>

                                            <!-- Deskripsi -->
                                            <p style="margin-top: 0.75rem; font-size: 0.75rem; font-family: monospace; color: #6B7280; text-align: center;">
                                                Status Akun: Aktif
                                            </p>
                                        </div>
                                    ')),
                            ]),

                        // KOLOM 2: FORM DATA DIRI
                        Section::make('Informasi Akun') // Judul lebih umum
                            ->description('Perbarui informasi profil dan biodata Anda.')
                            ->icon('heroicon-o-user-circle')
                            ->columnSpan(2)
                            ->schema([
                                $this->getNameFormComponent()
                                    ->label('Nama Lengkap')
                                    ->prefixIcon('heroicon-o-user'),

                                $this->getEmailFormComponent()
                                    ->label('Email')
                                    ->prefixIcon('heroicon-o-envelope'),

                                Textarea::make('bio')
                                    ->label('Bio / Deskripsi Diri')
                                    ->placeholder('Tuliskan sedikit tentang diri Anda...')
                                    ->rows(4)
                                    ->maxLength(500)
                                    ->columnSpanFull(),
                            ]),
                    ]),

                // SECTION KEAMANAN
                Section::make('Keamanan Akun')
                    ->icon('heroicon-o-lock-closed')
                    ->description('Perbarui kata sandi Anda jika diperlukan.')
                    ->collapsed()
                    ->schema([
                        $this->getPasswordFormComponent()
                            ->label('Password Baru'),
                        $this->getPasswordConfirmationFormComponent()
                            ->label('Konfirmasi Password Baru'),
                    ]),
            ]);
    }
}