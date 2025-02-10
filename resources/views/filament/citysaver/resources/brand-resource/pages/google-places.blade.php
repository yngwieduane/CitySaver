<x-filament-panels::page>
<h2>Top 100 Beauty Salons in Dubai, UAE</h2>
@php 
dd($places);
@endphp
    @foreach ($places as $place)
        @foreach ($place as $brand)
        <div class="mb-5">
            <ul class="mb-3">
                <li>{{ $brand['displayName']['text'] }}</li>
                <li>{{ $brand['formattedAddress'] }} </li>
                <li>{{ $brand['rating'] }} </li>
                <li>{{ $brand['userRatingCount'] ?? 0 }} reviews</li>
                <li>{{ $brand['internationalPhoneNumber'] }} </li>
                <li>latitude: {{ $brand['location']['latitude'] }} longitude: {{ $brand['location']['longitude'] }} </li>
                <li>{{ $brand['websiteUri'] }} </li>
            </ul>
            @foreach ($brand['photos'] as $photo)
            <ul class="mb-2">
                <li>https://places.googleapis.com/v1/{{ $photo['name'] }}/media?maxHeightPx=1000&maxWidthPx=400&key=AIzaSyCjjDm5NhjfoMP-c1WIUbovhoDID6XX3Js</li>
                <li>{{ $photo['authorAttributions'][0]['displayName'] }} </li>
                <li>{{ $photo['authorAttributions'][0]['uri'] }} </li>
                <li>{{ $photo['authorAttributions'][0]['photoUri'] }}</li>
            </ul>
            @endforeach
            
            @foreach ($brand['reviews'] as $rev)
            <ul class="mb-2">
                <li>{{ $rev['authorAttribution']['displayName'] }} </li>
                <li>{{ $rev['authorAttribution']['uri'] }} </li>
                <li>{{ $rev['authorAttribution']['photoUri'] }}</li>
                <li>{{ $rev['originalText']['text'] }} </li>
                <li>{{ $rev['publishTime'] }} </li>
            </ul>
            @endforeach
        </div>
        @endforeach
    @endforeach
</x-filament-panels::page>
