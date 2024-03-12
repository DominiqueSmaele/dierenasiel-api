<?php

namespace App\Policies\AdminDashboard;

use App\Enums\Permission;
use App\Enums\ShelterPermission;
use App\Models\Shelter;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class OpeningPeriodPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Shelter $shelter) : Response |  bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters) &&
            ! $user->hasPermission(ShelterPermission::manageShelter, $shelter)
        ) {
            return $this->deny(
                __('policies.admin_dashboard.opening_period.view.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }

    public function create(User $user, Shelter $shelter) : Response |  bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters) &&
            ! $user->hasPermission(ShelterPermission::manageShelter, $shelter)
        ) {
            return $this->deny(
                __('policies.admin_dashboard.opening_period.create.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }

    public function update(User $user, Shelter $shelter) : Response |  bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters) &&
            ! $user->hasPermission(ShelterPermission::manageShelter, $shelter)
        ) {
            return $this->deny(
                __('policies.admin_dashboard.opening_period.update.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }
}
