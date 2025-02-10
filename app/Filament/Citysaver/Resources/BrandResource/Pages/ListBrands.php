<?php

namespace App\Filament\Citysaver\Resources\BrandResource\Pages;

use App\Filament\Imports\BrandImporter;
use App\Filament\Citysaver\Resources\BrandResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBrands extends ListRecords
{
    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->importer(BrandImporter::class), 
            Actions\CreateAction::make(),
        ];
    }
}
