<?php

namespace App\Enums;

use App\Enums\Concerns\HasTranslation;
use App\Models\Role as RoleModel;

enum Role : string
{
    use HasTranslation;

    case developer = 'developer';
    case user = 'user';

    public function getKey() : int
    {
        return RoleModel::where('name', $this->value)->firstOrFail()->getKey();
    }

    public function syncPermissions($permissions) : void
    {
        RoleModel::findByName($this)->syncPermissions($permissions);
    }
}
