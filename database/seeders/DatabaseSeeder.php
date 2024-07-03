<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'email' => config('constants.admin_email'),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_admin' => true
        ]);

        $role = Role::create(['name' => 'Admin']);
        $user->assignRole($role);

        $permissions = Permission::insert([
            ['name' => 'Create City', 'guard_name' => 'web'],
            ['name' => 'Create Country', 'guard_name' => 'web'],
            ['name' => 'Create Department', 'guard_name' => 'web'],
            ['name' => 'Create Employee', 'guard_name' => 'web'],
            ['name' => 'Create State', 'guard_name' => 'web'],
            ['name' => 'Create User', 'guard_name' => 'web'],
            ['name' => 'Delete City', 'guard_name' => 'web'],
            ['name' => 'Delete Country', 'guard_name' => 'web'],
            ['name' => 'Delete Department', 'guard_name' => 'web'],
            ['name' => 'Delete Employee', 'guard_name' => 'web'],
            ['name' => 'Delete State', 'guard_name' => 'web'],
            ['name' => 'Delete User', 'guard_name' => 'web'],
            ['name' => 'List City', 'guard_name' => 'web'],
            ['name' => 'List Country', 'guard_name' => 'web'],
            ['name' => 'List Department', 'guard_name' => 'web'],
            ['name' => 'List Employee', 'guard_name' => 'web'],
            ['name' => 'List State', 'guard_name' => 'web'],
            ['name' => 'List User', 'guard_name' => 'web'],
            ['name' => 'Update City', 'guard_name' => 'web'],
            ['name' => 'Update Country', 'guard_name' => 'web'],
            ['name' => 'Update Department', 'guard_name' => 'web'],
            ['name' => 'Update Employee', 'guard_name' => 'web'],
            ['name' => 'Update State', 'guard_name' => 'web'],
            ['name' => 'Update User', 'guard_name' => 'web'],
            ['name' => 'View City', 'guard_name' => 'web'],
            ['name' => 'View Country', 'guard_name' => 'web'],
            ['name' => 'View Dashboard Department Chart', 'guard_name' => 'web'],
            ['name' => 'View Dashboard Department Table', 'guard_name' => 'web'],
            ['name' => 'View Dashboard Employee Chart', 'guard_name' => 'web'],
            ['name' => 'View Dashboard Employee Table', 'guard_name' => 'web'],
            ['name' => 'View Dashboard Modules Counter', 'guard_name' => 'web'],
            ['name' => 'View Department', 'guard_name' => 'web'],
            ['name' => 'View Employee', 'guard_name' => 'web'],
            ['name' => 'View State', 'guard_name' => 'web'],
            ['name' => 'View User', 'guard_name' => 'web'],
        ]);
        $role->permissions()->sync(Permission::all());
    }
}
