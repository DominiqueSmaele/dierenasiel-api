<?php

namespace App\Models\Casts;

use App\Models\Casts\Support\GeoSpatialFactory;
use App\Models\Values\Point;
use GeoIO\WKB\Parser\Parser;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

class AsPoint implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes) : ?Point
    {
        if ($value === null) {
            return null;
        }

        $srid = substr($value, 0, 4);
        $srid = unpack('L', $srid)[1];

        $wkb = substr($value, 4);
        $parser = new Parser(new GeoSpatialFactory());

        $point = $parser->parse($wkb);

        if ($srid > 0) {
            $point->setSrid($srid);
        }

        return $point;
    }

    public function set($model, string $key, $value, array $attributes) : ?Expression
    {
        if ($value === null) {
            return null;
        }

        $point = "POINT({$value->longitude} {$value->latitude})";

        return DB::raw("ST_GeomFromText('{$point}', {$value->srid}, 'axis-order=long-lat')");
    }
}
