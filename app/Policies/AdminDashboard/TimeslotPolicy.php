<?php

namespace App\Policies\AdminDashboard;

use App\Enums\Permission;
use App\Enums\ShelterPermission;
use App\Models\Shelter;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TimeslotPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Shelter $shelter) : Response | bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters) &&
            ! $user->hasPermission(ShelterPermission::manageShelter, $shelter)
        ) {
            return $this->deny(
                __('policies.admin_dashboard.timeslot.view_any.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }
}
