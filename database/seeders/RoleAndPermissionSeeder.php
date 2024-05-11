<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissionsUser = [
            'products.index',
            'products.show',
            'categories.index',
            // 'categories.get-all',
            // 'categories.create',
            // 'categories.edit',
        ];

        $permissionsAdmin = array_merge([
            'categories.store',
            'categories.update',
            'categories.destroy',
            'products.store',
            'products.update',
            'products.destroy',
            'users.index',
            // 'users.create',
            'users.store',
            // 'users.edit',
            'users.update',
            'users.destroy',
        ], $permissionsUser);

        $user = Role::create(['name' => 'user']);
        $admin = Role::create(['name' => 'admin']);

        foreach ($permissionsAdmin as $permission) {
            $permission = Permission::create(['name' => $permission]);
            $admin->givePermissionTo($permission);
        }

        foreach ($permissionsUser as $permission) {
            $permission = Permission::where(['name' => $permission])->first();
            $user->givePermissionTo($permission);
        }
    }
}
