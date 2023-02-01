<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_role = Role::create(['name' => 'admin']);
        $user_role = Role::create(['name' => 'user']);
        $permission = Permission::create(['name' => 'delect']);

        $admin_role->givePermissionTo($permission);
        $admin=User::create(["name"=>"Allcine@gmail.com","email"=>"Allcine@gmail.com","password"=>bcrypt("Roi12345")]);
        $user=User::create(["name"=>"Gerant","email"=>"gerant@gmail.com","password"=>bcrypt("Ger74740")]);
        $admin->assignRole($admin_role);
        $user->assignRole($user_role);
    }
}
