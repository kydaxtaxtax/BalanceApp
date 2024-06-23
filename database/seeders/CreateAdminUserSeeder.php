<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@demo.com',
            'password' => bcrypt('12345678')
        ]);

        $user1 = User::create([
            'name' => 'test user 1',
            'email' => 'user1@demo.com',
            'password' => bcrypt('12345678')
        ]);

        $user2 = User::create([
            'name' => 'test user 2',
            'email' => 'user2@demo.com',
            'password' => bcrypt('12345678')
        ]);

        $role_admin = Role::create(['name' => 'admin']);
        $role_user = Role::create(['name' => 'user']);

        $permissions_admin = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'operation-index',
            'operation-all'
        ];

        $permissions_user = [
            'operation-index',
            'account-index'
            ];

        $role_user->syncPermissions($permissions_user);
        $role_admin->syncPermissions($permissions_admin);

        $admin->assignRole([$role_admin->id]);
        $user1->assignRole([$role_user->id]);
        $user2->assignRole([$role_user->id]);


    }
}
