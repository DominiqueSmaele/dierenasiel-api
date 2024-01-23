<?php

namespace App\Policies\AdminDashboard;

use App\Enums\Permission;
use App\Enums\ShelterPermission;
use App\Models\Shelter;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AnimalPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Shelter $shelter) : Response |  bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters) &&
            ! $user->hasPermission(ShelterPermission::manageShelter, $shelter)
        ) {
            return $this->deny(
                __('policies.admin_dashboard.animal.view_any.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }

    public function create(User $user, Shelter $shelter) : Response | bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters) &&
            ! $user->hasPermission(ShelterPermission::manageShelter, $shelter)
        ) {
            return $this->deny(
                __('policies.admin_dashboard.animal.create.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }
}
