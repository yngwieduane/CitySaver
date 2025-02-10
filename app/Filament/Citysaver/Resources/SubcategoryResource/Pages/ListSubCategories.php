<?php

namespace App\Filament\Citysaver\Resources\SubCategoryResource\Pages;

use App\Filament\Citysaver\Resources\SubCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubCategories extends ListRecords
{
    protected static string $resource = SubCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
