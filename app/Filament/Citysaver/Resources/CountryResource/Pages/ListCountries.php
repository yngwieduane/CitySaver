<?php

namespace App\Filament\Citysaver\Resources\CountryResource\Pages;

use App\Filament\Imports\CountryImporter;
use App\Filament\Citysaver\Resources\CountryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCountries extends ListRecords
{
    protected static string $resource = CountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make() 
                ->importer(CountryImporter::class),
            Actions\CreateAction::make(),
        ];
    }
}
