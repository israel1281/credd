<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::first();
        if (!$role) {
            $roles = [
                ['title' => 'admin'],
                ['title' => 'user'],
            ];
            foreach($roles as $role) {
                Role::create($role);
            }
        } else {
            echo ('Error! Database must be empty to seed! \n');
        }
    }
}
