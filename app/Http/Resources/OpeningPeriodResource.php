<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OpeningPeriodResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'day' => $this->day,
            'open' => $this->open,
            'close' => $this->close,
        ];
    }
}
