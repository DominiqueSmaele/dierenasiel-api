<?php

namespace App\Policies\AdminDashboard;

use App\Enums\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class QualityPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user) : Response | bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters)) {
            return $this->deny(
                __('policies.admin_dashboard.quality.view_any.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }

    public function create(User $user) : Response | bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters)) {
            return $this->deny(
                __('policies.admin_dashboard.quality.create.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }

    public function update(User $user) : Response | bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters)) {
            return $this->deny(
                __('policies.admin_dashboard.quality.update.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }

    public function delete(User $user) : Response | bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters)) {
            return $this->deny(
                __('policies.admin_dashboard.quality.delete.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }
}
