<?php

namespace App\Filament\Imports;

use App\Models\Subcategory;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class SubcategoryImporter extends Importer
{
    protected static ?string $model = Subcategory::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping(), 
        ];
    }

    public function resolveRecord(): ?Subcategory
    {
        $subcategory =  Subcategory::firstOrNew([
            // Update existing records, matching them by `$this->data['column_name']`
            'name' => $this->data['name'],
        ]);

        $subcategory->category()->associate($this->options['category_id']);

        return $subcategory;
        //return new Subcategory();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your subcategory import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
