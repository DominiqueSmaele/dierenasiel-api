<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'street' => $this->street,
            'number' => $this->number,
            'box_number' => $this->box_number,
            'zipcode' => $this->zipcode,
            'city' => $this->city,
            'country' => CountryResource::make($this->whenLoaded('country')),
        ];
    }
}
