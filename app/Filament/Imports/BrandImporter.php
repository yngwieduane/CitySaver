<?php

namespace App\Filament\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\SubCategory;
use App\Models\Country;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;
use Filament\Forms;

class BrandImporter extends Importer
{
    protected static ?string $model = Brand::class;

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
                
            Select::make('country_id')
                ->label('Country')
                ->options(Country::pluck('name','id'))
                ->live()
                ->required(),

            Select::make('city_id')
                ->label('City')
                ->options(fn(Forms\Get $get) => City::where('country_id', (int) $get('country_id'))->pluck('name','id'))
                ->disabled(fn(Forms\Get $get) : bool => ! filled($get('country_id')) ),
        ];
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Name (qBF1Pd)')
                ->guess(['qBF1Pd'])
                ->requiredMapping(), 
            ImportColumn::make('google_map_link')
                ->label('Google Link (hfpxzc href)')
                ->guess(['hfpxzc href'])
                ->requiredMapping(), 
            ImportColumn::make('website_link')
                ->label('Website Link (lcr4fd href)')
                ->guess(['lcr4fd href'])
                ->castStateUsing(function (?string  $state)  {
                    if (blank($state)) {
                        return 0;
                    }else{
                        $retext = explode("?", $state);
                        return $retext[0];
                    }
                }), 
            ImportColumn::make('facebook_link'), 
            ImportColumn::make('x_link'),
            ImportColumn::make('instagram_link'), 
            ImportColumn::make('youtube_link'), 
            ImportColumn::make('image_url')
                ->label('Image Url (FQ2IWe src)')
                ->guess(['FQ2IWe src']), 
            ImportColumn::make('rating')
                ->label('Rating (MW4etd)')
                ->guess(['MW4etd']), 
            ImportColumn::make('no_of_rate')
                ->castStateUsing(function (?string  $state): ?float  {
                    if (blank($state)) {
                        return 0;
                    }else{
                        return floatval($state)*-1;
                    }
                })
                ->label('No of Rating (UY7F9)')
                ->guess(['UY7F9']), 
            ImportColumn::make('address')
                ->label('Address 1 (qBF1Pd)')
                ->guess(['qBF1Pd']), 
            ImportColumn::make('address2')
                ->label('Address 2 (W4Efsd 4)')
                ->guess(['W4Efsd 4']), 
            ImportColumn::make('phone_number')
                ->label('Phone Number (UsdlK)')
                ->guess(['UsdlK']), 
            ImportColumn::make('description')
                ->label('Other description'), 
            ImportColumn::make('open'), 
            ImportColumn::make('close'), 
        ];
    }

    public function resolveRecord(): ?Brand
    {
        $brand = Brand::firstOrNew([
            // Update existing records, matching them by `$this->data['column_name']`
            'slug' => Str::slug($this->data['name']),
        ]);
        
        $brand->category()->associate($this->options['category_id']);

        $brand->sub_category()->associate($this->options['sub_category_id']);

        $brand->country()->associate($this->options['country_id']);

        $brand->city()->associate($this->options['city_id']);

        return $brand;

        //return new Brand();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your brand import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
