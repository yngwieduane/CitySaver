<?php

namespace App\Filament\Citysaver\Resources\ProductResource\Pages;

use App\Filament\Citysaver\Resources\ProductResource;
use App\Filament\Imports\ProductImporter;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->importer(ProductImporter::class), 
            Actions\CreateAction::make(),
        ];
    }
}
