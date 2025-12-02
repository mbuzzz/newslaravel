<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\ColorPicker; // Import ColorPicker
use Filament\Forms\Components\Section;

class ManageSiteSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $title = 'Pengaturan Website';
    protected static ?int $navigationSort = 3;

    protected static string $settings = GeneralSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        // TAB 1: IDENTITAS & WARNA
                        Tabs\Tab::make('Tampilan & Brand')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Section::make('Identitas')
                                    ->schema([
                                        TextInput::make('site_name')->label('Nama Website')->required(),
                                        TextInput::make('site_tagline')->label('Tagline'),
                                        Textarea::make('site_description')->label('Deskripsi')->rows(2),
                                    ]),

                                Section::make('Tema Warna')
                                    ->description('Sesuaikan warna website dengan identitas brand Anda.')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            ColorPicker::make('site_theme_primary')
                                                ->label('Warna Utama')
                                                ->helperText('Untuk Header, Tombol, dan Link.'),
                                            
                                            ColorPicker::make('site_theme_secondary')
                                                ->label('Warna Sekunder')
                                                ->helperText('Untuk Aksen, Badge, dan Highlight.'),

                                            ColorPicker::make('site_theme_footer')
                                                ->label('Warna Footer')
                                                ->helperText('Warna latar belakang bagian paling bawah.'),
                                        ]),
                                    ]),

                                Section::make('Logo & Icon')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            FileUpload::make('site_logo')->image()->directory('settings')->visibility('public'),
                                            FileUpload::make('site_favicon')->image()->directory('settings')->visibility('public'),
                                            FileUpload::make('sidenav_background')
                                                ->label('Background Sidebar')
                                                ->image()
                                                ->directory('settings')
                                                ->visibility('public')
                                                ->helperText('Gambar latar untuk menu samping mobile.'),
                                        ]),
                                    ]),
                            ]),

                        // TAB 2: KONTAK
                        Tabs\Tab::make('Kontak')
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                TextInput::make('organization_email')->email(),
                                TextInput::make('organization_phone')->tel(),
                                Textarea::make('organization_address')->rows(3),
                                Textarea::make('google_maps_embed_link')->label('Embed Map HTML')->rows(3),
                            ]),

                        // TAB 3: SOSMED
                        Tabs\Tab::make('Sosial Media')
                            ->icon('heroicon-o-share')
                            ->schema([
                                TextInput::make('social_instagram')->prefixIcon('heroicon-o-camera'),
                                TextInput::make('social_youtube')->prefixIcon('heroicon-o-video-camera'),
                                TextInput::make('social_tiktok')->prefixIcon('heroicon-o-musical-note'),
                                TextInput::make('social_facebook')->prefixIcon('heroicon-o-users'),
                                TextInput::make('social_twitter')->prefixIcon('heroicon-o-hashtag'),
                            ]),

                        // TAB 4: MONETISASI / ADS
                        Tabs\Tab::make('Monetisasi')
                            ->icon('heroicon-o-banknotes')
                            ->schema([
                                Section::make('Google AdSense')
                                    ->schema([
                                        TextInput::make('adsense_client_id')
                                            ->label('AdSense Client ID')
                                            ->placeholder('ca-pub-XXXXXXXXXXXXXXXX')
                                            ->helperText('Tempel ID klien AdSense Anda sesuai ketentuan Google.'),
                                    ]),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }
}
