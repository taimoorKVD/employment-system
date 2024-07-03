<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
        ]);

        $user = User::create([
            'name' => 'Admin',
            'email' => env('ADMIN_EMAIL'),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_admin' => true
        ]);
        $role = Role::create(['name' => 'Admin']);
        Permission::insert([
            ['name' => 'create-city', 'guard_name' => 'web'],
            ['name' => 'create-country', 'guard_name' => 'web'],
            ['name' => 'create-department', 'guard_name' => 'web'],
            ['name' => 'create-employee', 'guard_name' => 'web'],
            ['name' => 'create-state', 'guard_name' => 'web'],
            ['name' => 'create-user', 'guard_name' => 'web'],
            ['name' => 'create-task', 'guard_name' => 'web'],
            ['name' => 'delete-city', 'guard_name' => 'web'],
            ['name' => 'delete-country', 'guard_name' => 'web'],
            ['name' => 'delete-department', 'guard_name' => 'web'],
            ['name' => 'delete-employee', 'guard_name' => 'web'],
            ['name' => 'delete-state', 'guard_name' => 'web'],
            ['name' => 'delete-user', 'guard_name' => 'web'],
            ['name' => 'delete-task', 'guard_name' => 'web'],
            ['name' => 'update-city', 'guard_name' => 'web'],
            ['name' => 'update-country', 'guard_name' => 'web'],
            ['name' => 'update-department', 'guard_name' => 'web'],
            ['name' => 'update-employee', 'guard_name' => 'web'],
            ['name' => 'update-state', 'guard_name' => 'web'],
            ['name' => 'update-user', 'guard_name' => 'web'],
            ['name' => 'update-task', 'guard_name' => 'web'],
            ['name' => 'view-city', 'guard_name' => 'web'],
            ['name' => 'view-country', 'guard_name' => 'web'],
            ['name' => 'view-department', 'guard_name' => 'web'],
            ['name' => 'view-employee', 'guard_name' => 'web'],
            ['name' => 'view-state', 'guard_name' => 'web'],
            ['name' => 'view-user', 'guard_name' => 'web'],
            ['name' => 'view-task', 'guard_name' => 'web'],
            ['name' => 'view-department-chart', 'guard_name' => 'web'],
            ['name' => 'view-department-table', 'guard_name' => 'web'],
            ['name' => 'view-employee-chart', 'guard_name' => 'web'],
            ['name' => 'view-employee-table', 'guard_name' => 'web'],
            ['name' => 'view-modules-counter', 'guard_name' => 'web'],
        ]);
        $user->assignRole($role);
        $role->permissions()->sync(Permission::all());
    }
}
