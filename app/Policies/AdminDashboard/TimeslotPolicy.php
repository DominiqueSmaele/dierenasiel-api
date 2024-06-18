<?php

namespace App\Policies\AdminDashboard;

use App\Enums\Permission;
use App\Enums\ShelterPermission;
use App\Models\Shelter;
use App\Models\Timeslot;
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

    public function create(User $user, Shelter $shelter) : Response | bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters) &&
            ! $user->hasPermission(ShelterPermission::manageShelter, $shelter)
        ) {
            return $this->deny(
                __('policies.admin_dashboard.timeslot.create.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }

    public function update(User $user, Timeslot $timeslot) : Response | bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters) &&
            ! $user->hasPermission(ShelterPermission::manageShelter, $timeslot->shelter)
        ) {
            return $this->deny(
                __('policies.admin_dashboard.timeslot.update.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }

    public function delete(User $user, Timeslot $timeslot) : Response | bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters) &&
            ! $user->hasPermission(ShelterPermission::manageShelter, $timeslot->shelter)
        ) {
            return $this->deny(
                __('policies.admin_dashboard.timeslot.delete.no_permission'),
                'no_permission'
            );
        }

        if ($timeslot->shelter->trashed()) {
            return $this->deny(
                __('policies.admin_dashboard.timeslot.delete.shelter_deleted'),
                'shelter_deleted'
            );
        }

        return $this->allow();
    }
}
