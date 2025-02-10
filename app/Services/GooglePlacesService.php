<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class GooglePlacesService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.google_places.api_key');
    }
    
    public function getCoordinatesFromUrl($url)
    {
        preg_match('/@([0-9.]+),([0-9.]+),/', $url, $matches);
        return [
            'lat' => $matches[1] ?? null,
            'lng' => $matches[2] ?? null,
        ];
    }

    public function getPlacesByCategory($city, $category, $country, $limit = 100)
    {
        $url = 'https://places.googleapis.com/v1/places:searchText';
        $params = [
            'textQuery' => "{$category} in {$city}, {$country}",
            'pageSize' => 5

        ];

        $places = [];
        do {
            $response = Http::withHeaders([
                'X-Goog-Api-Key' =>  $this->apiKey,
                'Content-Type' => 'application/json',
                'X-Goog-FieldMask' => '*'
           ])->post($url, $params)->json();
            $places = array_merge($places, $response);
            $nextPageToken = $response['next_page_token'] ?? null; 
            $params['pagetoken'] = $nextPageToken;
            sleep(2); // Pause to respect API rate limits
        } while ($nextPageToken && count($places) < $limit);

        usort($places, fn($a, $b) => ($b['userRatingCount'] ?? 0) - ($a['userRatingCount'] ?? 0));
        return array_slice($places, 0, $limit);
    }
}
