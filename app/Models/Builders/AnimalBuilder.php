<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class AnimalBuilder extends Builder
{
    public function search(string $searchValue) : self
    {
        $searchValue = "%{$searchValue}%";

        return $this->where(function ($query) use ($searchValue) {
            $query->where('name', 'LIKE', $searchValue)
                ->orWhere('race', 'LIKE', $searchValue);
        });
    }
}
