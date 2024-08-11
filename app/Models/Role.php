<?php

namespace App\Models;

use Laratrust\Models\Role as BaseRole;

class Role extends BaseRole
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    public static function findByName($name) : Role
    {
        return self::where('name', $name->value)->first();
    }
}
