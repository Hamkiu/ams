<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {

        $roles = [
            'User',
            'Admin'
        ];

        $noPekerja = 10001;
        foreach ($roles as $roleName) {

            // Create Role
            $role = Role::create(['name' => $roleName]);

            // Create User
            $user = User::create([
                'name' => $roleName,
                'jawatan' => $roleName,
                'jabatan' => match($roleName) {
                    'Admin' => 'Jabatan A',
                    'User' => 'Jabatan B',
                },
                'no_telefon' => '012' . rand(1000000,9999999),
                'no_pekerja' => $noPekerja,
                'email' => strtolower(str_replace(' ', '', $roleName)) . '@example.com',
                'password' => Hash::make('password'),
            ]);

            // Assign Role
            $user->assignRole($role);
            $noPekerja++;
        }
    }
}