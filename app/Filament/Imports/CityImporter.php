<?php

namespace App\Filament\Imports;

use App\Models\City;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Str;

class CityImporter extends Importer
{
    protected static ?string $model = City::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping(), 
            ImportColumn::make('wiki_url')
                ->requiredMapping(), 
        ];
    }

    public function resolveRecord(): ?City
    {
        $city = City::firstOrNew([
            // Update existing records, matching them by `$this->data['column_name']`
            'slug' => Str::slug($this->data['name']),
        ]);
 
        $city->country()->associate($this->options['country_id']);
            
        return $city;
        //return new City();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your city import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
