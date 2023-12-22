<?php

namespace App\Policies\AdminDashboard;

use App\Enums\Permission;
use App\Enums\ShelterPermission;
use App\Models\Shelter;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ShelterPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user) : Response | bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters)) {
            return $this->deny(
                __('policies.admin_dashboard.shelter.view_any.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }

    public function create(User $user) : Response | bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters)) {
            return $this->deny(
                __('policies.admin_dashboard.shelter.create.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }

    public function update(User $user, Shelter $shelter) : Response | bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters) &&
            ! $user->hasPermission(ShelterPermission::manageShelter, $shelter)) {
            return $this->deny(
                __('policies.admin_dashboard.shelter.update.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }
}
