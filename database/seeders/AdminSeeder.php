<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $active_status = status_active_id();
        $admin_role = role_admin();
        $admin = User::find(1);
        if (!$admin){
            User::create([
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'email' => 'admin@creditwolfinc.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'phone' => '12345678901',
                'role_id' => $admin_role,
                'status_id' => $active_status,
                'terms' => true
            ]);
        } else {
            echo ('Error! Database must be empty to seed! \n');
        }
    }
}
