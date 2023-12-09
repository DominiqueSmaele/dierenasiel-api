<?php

namespace App\Policies\AdminDashboard;

use App\Enums\Permission;
use App\Enums\Role;
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
}
