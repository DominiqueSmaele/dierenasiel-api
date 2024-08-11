<?php

namespace App\Models\Values;

use GeoJson\Geometry\Point as GeometryPoint;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

class Point implements Jsonable, JsonSerializable
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
        protected ?int $srid = 0
    ) {
    }

    public function setSrid(int $srid) : self
    {
        $this->srid = $srid;

        return $this;
    }

    public function __get($property) : mixed
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }
    }

    public function jsonSerialize() : GeometryPoint
    {
        return new GeometryPoint([$this->longitude, $this->latitude]);
    }

    public function toJson($options = 0) : string
    {
        return json_encode($this, $options);
    }
}
