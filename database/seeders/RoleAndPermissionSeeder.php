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
        $permissionsAdmin = array_merge([
            'categories.index',
            'categories.store',
            'categories.update',
            'categories.destroy',
            'categories.show',
            'products.index',
            'products.store',
            'products.update',
            'products.destroy',
            'users.index',
            'users.store',
            'users.update',
            'users.destroy',
        ]);

        Role::create(['name' => 'client']);
        $admin = Role::create(['name' => 'admin']);

        foreach ($permissionsAdmin as $permission) {
            $permission = Permission::create(['name' => $permission]);
            $admin->givePermissionTo($permission);
        }
    }
}
