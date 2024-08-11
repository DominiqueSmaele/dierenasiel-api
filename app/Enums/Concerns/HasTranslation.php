<?php

namespace App\Enums\Concerns;

use Illuminate\Support\Str;

trait HasTranslation
{
    public function getTranslation() : string
    {
        $className = Str::of(self::class)->classBasename()->snake();

        return __("enums.{$className}.{$this->value}");
    }
}
