<?php
namespace App\Filament\Citysaver\Resources\BrandResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Citysaver\Resources\BrandResource;
use Illuminate\Routing\Router;


class BrandApiService extends ApiService
{
    protected static string | null $resource = BrandResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
