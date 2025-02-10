<?php

namespace App\Filament\Citysaver\Resources\ServiceResource\Pages;

use App\Filament\Citysaver\Resources\ServiceResource;
use App\Filament\Imports\ServiceImporter;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->importer(ServiceImporter::class), 
            Actions\CreateAction::make(),
        ];
    }
}
