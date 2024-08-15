<?php

namespace App\Observers;

use App\Enums\Role;
use App\Models\User;

class UserObserver
{
    public function creating(User $user) : void
    {
        $user->locale ??= app()->getLocale();
    }

    public function created(User $user) : void
    {
        $user->syncRoles([Role::user]);
    }

    public function deleting(User $user) : void
    {
        $user->anonymize();

        $user->revokeTokens();
    }
}
