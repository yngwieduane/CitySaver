<?php

namespace App\Filament\Imports;

use App\Models\Category;
use App\Models\Service;
use App\Models\SubCategory;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;
use Filament\Forms;

class ServiceImporter extends Importer
{
    protected static ?string $model = Service::class;

    public static function getOptionsFormComponents(): array
    {
        return [
            Select::make('category_id')
                ->label('Category')
                ->options(Category::pluck('name','id'))
                ->live()
                ->required(),

            Select::make('sub_category_id')
                ->label('Sub Category')
                ->options(fn(Forms\Get $get) => SubCategory::where('category_id', (int) $get('category_id'))->pluck('name','id'))
                ->disabled(fn(Forms\Get $get) : bool => ! filled($get('category_id')) ),

        ];
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Name')
                ->requiredMapping(), 
            ImportColumn::make('description')
                ->label('Description') 
        ];
    }

    public function resolveRecord(): ?Service
    {
        $service = Service::firstOrNew([
            // Update existing records, matching them by `$this->data['column_name']`
            'slug' => Str::slug($this->data['name']),
        ]);

        $service->category()->associate($this->options['category_id']);

        $service->sub_category()->associate($this->options['sub_category_id']);

        return $service;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your service import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
