<?php

namespace App\Filament\Citysaver\Resources\UserResource\Pages;

use App\Filament\Citysaver\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
