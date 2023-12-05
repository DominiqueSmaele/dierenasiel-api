<?php

namespace App\Enums;

use App\Models\Permission as PermissionModel;

enum Permission : string
{
    case viewAdminDashboard = 'viewAdminDashboard';

    public function getKey() : int
    {
        return PermissionModel::where('name', $this->value)->firstOrFail()->getKey();
    }
}
