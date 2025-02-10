<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;
use Sushi\Sushi;

use App\Services\GooglePlacesService;

class GoogleBrands extends Model
{
    use HasFactory;
    
    use Sushi;
        /**
     * Model Rows
     *
     * @return void
     */
    public function mount(GooglePlacesService $googlePlacesService)
    {
        return $googlePlacesService->getPlacesByCategory('Dubai', 'beauty_salon', 'UAE', 1);
    }
    
    public function getRows()
    {
        $googlePlacesService = new GooglePlacesService; 
        // //API
        $places = $googlePlacesService->getPlacesByCategory('Dubai', 'beauty_salon', 'UAE', 20);
 
        //filtering some attributes
        $places = Arr::map($places[0], function ($item) {

            //fix naming
            $item['displayNameText'] = $item['displayName']['text'];

            return Arr::only($item,
                [
                    'id',
                    'name',
                    'displayNameText',
                ]
            );
        });
        
        return $places;
    }
    
    protected function sushiShouldCache()
    {
        return true;
    }
}
