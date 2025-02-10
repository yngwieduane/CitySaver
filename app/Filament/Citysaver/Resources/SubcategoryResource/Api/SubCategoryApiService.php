<?php
namespace App\Filament\Citysaver\Resources\SubCategoryResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Citysaver\Resources\SubCategoryResource;
use Illuminate\Routing\Router;


class SubCategoryApiService extends ApiService
{
    protected static string | null $resource = SubCategoryResource::class;

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
