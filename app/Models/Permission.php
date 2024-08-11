<?php

namespace App\Models;

use Laratrust\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    public static function findByName($name) : Permission
    {
        return self::where('name', $name->value)->first();
    }
}
