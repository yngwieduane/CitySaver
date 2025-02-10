<?php
namespace App\Filament\Citysaver\Resources\CategoryResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->toArray();
    }
}
