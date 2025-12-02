<?php

namespace App\Filament\Pages;

use App\Settings\SeoSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;

class ManageSeoSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $title = 'SEO & Metadata';
    protected static ?int $navigationSort = 4;

    protected static string $settings = SeoSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('SEO Halaman Depan (Home)')
                    ->description('Pengaturan meta tags khusus untuk halaman utama website.')
                    ->schema([
                        TextInput::make('meta_title_home')
                            ->label('Meta Title (Home)')
                            ->required()
                            ->maxLength(60),
                        
                        Textarea::make('meta_description_home')
                            ->label('Meta Description (Home)')
                            ->required()
                            ->rows(3)
                            ->maxLength(160),
                            
                        TagsInput::make('meta_keywords_global')
                            ->label('Global Keywords')
                            ->separator(','),
                    ]),

                Section::make('Open Graph (Social Share)')
                    ->description('Tampilan default saat link website dibagikan ke WhatsApp/Facebook.')
                    ->schema([
                        FileUpload::make('og_image_default')
                            ->label('Default OG Image')
                            ->image()
                            ->directory('seo')
                            ->visibility('public')
                            ->helperText('Gambar ini akan muncul jika halaman/artikel tidak memiliki gambar khusus. Ukuran rekomen: 1200x630px.'),
                    ]),
            ]);
    }
}