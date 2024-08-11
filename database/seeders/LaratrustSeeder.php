<?php

namespace Database\Seeders;

use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use App\Enums\ShelterPermission as ShelterPermissionEnum;
use App\Enums\ShelterRole as ShelterRoleEnum;
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
                ->merge(ShelterPermissionEnum::cases())
                ->map(fn ($permission) => ['name' => $permission->value])
                ->all(),
            ['name']
        );

        Role::upsert(
            collect(RoleEnum::cases())
                ->merge(ShelterRoleEnum::cases())
                ->map(fn ($role) => ['name' => $role->value])
                ->all(),
            ['name']
        );

        Role::findByName(RoleEnum::developer)->syncPermissions(
            Permission::whereIn('name', [
                PermissionEnum::manageAllShelters->value,
            ])->pluck('id')->all()
        );

        Role::findByName(RoleEnum::user)->syncPermissions([]);

        Role::findByName(ShelterRoleEnum::admin)->syncPermissions(
            Permission::whereIn('name', [
                ShelterPermissionEnum::manageShelter->value,
            ])->pluck('id')->all()
        );

        User::doesntHave('roles')->each(function ($user) {
            $user->syncRoles([RoleEnum::user]);
        }, 50);
    }
}
