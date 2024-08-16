<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShelterResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'tiktok' => $this->tiktok,
            'address' => AddressResource::make($this->whenLoaded('address')),
            'image' => ImageResource::make($this->image)->ofModel($this->resource),
            'opening_periods' => OpeningPeriodResource::collection($this->whenLoaded('openingPeriods')),
            'timeslots' => TimeslotResource::collection($this->whenLoaded('timeslots')),
        ];
    }
}
