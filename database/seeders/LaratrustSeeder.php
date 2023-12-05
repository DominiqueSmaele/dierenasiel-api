<?php

namespace Database\Seeders;

use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class LaratrustSeeder extends Seeder
{
    public function run() : void
    {
        Permission::upsert(
            collect(PermissionEnum::cases())
                ->map(fn ($permission) => ['name' => $permission->value])
                ->all(),
            ['name']
        );

        Role::upsert(
            collect(RoleEnum::cases())
                ->map(fn ($role) => ['name' => $role->value])
                ->all(),
            ['name']
        );

        Role::findByName(RoleEnum::developer)->syncPermissions(
            Permission::whereIn('name', [
                PermissionEnum::viewAdminDashboard->value,
            ])->pluck('id')->all()
        );

        Role::findByName(RoleEnum::admin)->syncPermissions(
            Permission::whereIn('name', [
                PermissionEnum::viewAdminDashboard->value,
            ])->pluck('id')->all()
        );

        Role::findByName(RoleEnum::user)->syncPermissions([]);

        User::doesntHave('roles')->each(function ($user) {
            $user->syncRoles([RoleEnum::user]);
        }, 50);
    }
}
