<?php

namespace App\Models\Casts\Support;

use App\Models\Values\Point;
use GeoIO\Factory;
use RuntimeException;

class GeoSpatialFactory implements Factory
{
    public function createPoint($dimension, array $coordinates, $srid = null) : Point
    {
        return new Point($coordinates['y'], $coordinates['x'], $srid);
    }

    public function createLineString($dimension, array $points, $srid = null) : void
    {
        throw new RuntimeException('Method not implemented');
    }

    public function createLinearRing($dimension, array $points, $srid = null) : void
    {
        throw new RuntimeException('Method not implemented');
    }

    public function createPolygon($dimension, array $lineStrings, $srid = null) : void
    {
        throw new RuntimeException('Method not implemented');
    }

    public function createMultiPoint($dimension, array $points, $srid = null) : void
    {
        throw new RuntimeException('Method not implemented');
    }

    public function createMultiLineString($dimension, array $lineStrings, $srid = null) : void
    {
        throw new RuntimeException('Method not implemented');
    }

    public function createMultiPolygon($dimension, array $polygons, $srid = null) : void
    {
        throw new RuntimeException('Method not implemented');
    }

    public function createGeometryCollection($dimension, array $geometries, $srid = null) : void
    {
        throw new RuntimeException('Method not implemented');
    }
}
