<?php

namespace App\Filament\Citysaver\Resources\GoogleBrandsResource\Pages;

use App\Filament\Citysaver\Resources\GoogleBrandsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageGoogleBrands extends ManageRecords
{
    protected static string $resource = GoogleBrandsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
