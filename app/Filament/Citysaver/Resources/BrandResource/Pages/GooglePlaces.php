<?php

namespace App\Filament\Citysaver\Resources\BrandResource\Pages;

use App\Filament\Citysaver\Resources\BrandResource;
use Filament\Resources\Pages\Page;

use App\Services\GooglePlacesService;

class GooglePlaces extends Page
{
    protected static string $resource = BrandResource::class;

    protected static string $view = 'filament.citysaver.resources.brand-resource.pages.google-places';

    public $places = [];

    public function mount(GooglePlacesService $googlePlacesService)
    {
        $this->places = $googlePlacesService->getPlacesByCategory('Dubai', 'beauty_salon', 'UAE', 1);
    }
}
