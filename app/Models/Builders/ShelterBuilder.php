<?php

namespace App\Models\Builders;

use App\Models\Address;
use App\Models\Values\Point;
use Illuminate\Database\Eloquent\Builder;

class ShelterBuilder extends Builder
{
    public function search(string $searchValue) : self
    {
        return $this->where('name', 'LIKE', "%{$searchValue}%");
    }

    public function orderByDistance(Point $location) : self
    {
        return $this->joinSub(
            Address::query()
                ->select(['id as address_id'])
                ->selectRaw(
                    'ROUND(ST_Distance_Sphere(coordinates, ST_GeomFromText(?, 4326)), 3) as distance',
                    ["Point({$location->latitude} {$location->longitude})"]
                ),
            'address_distance',
            fn ($query) => $query->on('address_distance.address_id', 'shelters.address_id')
        )->addSelect(['distance'])->orderBy('distance');
    }
}
