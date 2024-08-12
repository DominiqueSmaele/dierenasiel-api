<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnimalResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sex' => $this->sex,
            'birth_date' => $this->birth_date,
            'race' => $this->race,
            'description' => $this->description,
            'image' => ImageResource::make($this->image)->ofModel($this->resource),
            'qualities' => AnimalQualityResource::collection($this->whenLoaded('qualities')),
            'type' => TypeResource::make($this->whenLoaded('type')),
            'shelter' => ShelterResource::make($this->whenLoaded('shelter')),
        ];
    }
}
