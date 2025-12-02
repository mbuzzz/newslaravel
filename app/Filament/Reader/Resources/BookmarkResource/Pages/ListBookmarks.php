<?php

namespace App\Filament\Reader\Resources\BookmarkResource\Pages;

use App\Filament\Reader\Resources\BookmarkResource;
use Filament\Resources\Pages\ListRecords;

class ListBookmarks extends ListRecords
{
    protected static string $resource = BookmarkResource::class;
}