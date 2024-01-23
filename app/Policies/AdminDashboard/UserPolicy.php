<?php

namespace App\Policies\AdminDashboard;

use App\Enums\Permission;
use App\Enums\Role;
use App\Enums\ShelterPermission;
use App\Models\Shelter;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAnyDeveloper(User $user) : Response | bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters) || ! $user->hasRole(Role::developer)) {
            return $this->deny(
                __('policies.admin_dashboard.user.view_any_developer.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }

    public function viewAnyAdmin(User $user, Shelter $shelter) : Response | bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters) &&
            ! $user->hasPermission(ShelterPermission::manageShelter, $shelter)) {
            return $this->deny(
                __('policies.admin_dashboard.user.view_any_admin.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }

    public function createAdmin(User $user, Shelter $shelter) : Response | bool
    {
        if (! $user->hasPermission(Permission::manageAllShelters) &&
            ! $user->hasPermission(ShelterPermission::manageShelter, $shelter)) {
            return $this->deny(
                __('policies.admin_dashboard.user.create_admin.no_permission'),
                'no_permission'
            );
        }

        return $this->allow();
    }
}
