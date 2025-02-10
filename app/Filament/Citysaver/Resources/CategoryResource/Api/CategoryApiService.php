<?php
namespace App\Filament\Citysaver\Resources\CategoryResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Citysaver\Resources\CategoryResource;
use Illuminate\Routing\Router;


class CategoryApiService extends ApiService
{
    protected static string | null $resource = CategoryResource::class;

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
