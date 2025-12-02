<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone'; // Ikon Megaphone
    protected static ?string $navigationGroup = 'Marketing'; // Grup Baru
    protected static ?string $navigationLabel = 'Banner & Iklan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        // KOLOM KIRI (UTAMA)
                        Section::make('Konten Visual')
                            ->columnSpan(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Nama Kampanye / Banner')
                                    ->required()
                                    ->placeholder('Contoh: Promo PPDB 2025'),
                                
                                Forms\Components\FileUpload::make('image')
                                    ->label('Upload Gambar')
                                    ->image()
                                    ->directory('banners')
                                    ->imageEditor()
                                    ->required()
                                    ->columnSpanFull()
                                    ->helperText('Untuk Hero (Slider): Gunakan Landscape (1920x600). Untuk Sidebar: Gunakan Kotak (Square).'),
                                
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('url')
                                            ->label('Link Tujuan (URL)')
                                            ->prefixIcon('heroicon-o-link')
                                            ->placeholder('https://...'),
                                            
                                        Forms\Components\Toggle::make('open_in_new_tab')
                                            ->label('Buka di Tab Baru?')
                                            ->default(true)
                                            ->inline(false),
                                    ]),
                            ]),

                        // KOLOM KANAN (SETTING)
                        Section::make('Pengaturan Tayang')
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Posisi / Jenis')
                                    ->options([
                                        'hero' => 'Slider Utama (Homepage)',
                                        'popup' => 'Popup Modal (Iklan Melayang)',
                                        'sidebar' => 'Sidebar Widget (Samping Berita)',
                                        'inline' => 'Inline Artikel (Dalam Berita)',
                                    ])
                                    ->required()
                                    ->native(false),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Status Aktif')
                                    ->default(true)
                                    ->onColor('success')
                                    ->offColor('danger'),

                                Forms\Components\DateTimePicker::make('start_date')
                                    ->label('Mulai Tayang')
                                    ->helperText('Kosongkan jika ingin langsung tayang.'),

                                Forms\Components\DateTimePicker::make('end_date')
                                    ->label('Selesai Tayang')
                                    ->helperText('Kosongkan jika tayang selamanya.'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Preview')
                    ->height(50),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Posisi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hero' => 'info',
                        'popup' => 'warning',
                        'sidebar' => 'success',
                        'inline' => 'primary',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Jadwal')
                    ->date('d M Y')
                    ->placeholder('Selalu Tayang'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'hero' => 'Slider Utama',
                        'popup' => 'Popup',
                        'sidebar' => 'Sidebar',
                        'inline' => 'Inline Artikel',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}