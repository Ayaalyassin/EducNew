<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin=User::create([
            'name' => 'admin',
            'email' => 'admin0@gmail.com',
            'password' => bcrypt('12341234'),
            'address' => 'malke',
            'governorate' => 'Damascus',
            'birth_date' => '1999-9-9',
        ]);

        $role = Role::where('name','admin')->first();
        $admin->assignRole($role);
    }
}
