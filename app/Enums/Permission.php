<?php

namespace App\Enums;

use App\Models\Permission as PermissionModel;

enum Permission : string
{
    case manageAllShelters = 'manageAllShelters';

    public function getKey() : int
    {
        return PermissionModel::where('name', $this->value)->firstOrFail()->getKey();
    }
}
