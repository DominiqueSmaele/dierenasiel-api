<?php

namespace App\Enums;

use App\Enums\Concerns\HasTranslation;
use App\Models\Role as RoleModel;

enum ShelterRole : string
{
    use HasTranslation;

    case admin = 'administrator';

    public function getKey() : int
    {
        return RoleModel::where('name', $this->value)->firstOrFail()->getKey();
    }

    public function syncPermissions($permissions) : void
    {
        RoleModel::findByName($this)->syncPermissions($permissions);
    }
}
