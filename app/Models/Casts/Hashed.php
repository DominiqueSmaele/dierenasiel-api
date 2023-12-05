<?php

namespace App\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash as HashFacade;

class Hashed implements CastsInboundAttributes
{
    public function set(Model $model, string $key, mixed $value, array $attributes) : string
    {
        return $value !== null && HashFacade::needsRehash($value) ? HashFacade::make($value) : $value;
    }
}
