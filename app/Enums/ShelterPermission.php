<?php

namespace App\Enums;

use App\Models\Permission as PermissionModel;

enum ShelterPermission : string
{
    case manageShelter = 'manageShelter';

    public function getKey() : int
    {
        return PermissionModel::where('name', $this->value)->firstOrFail()->getKey();
    }
}
