<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $active_status = status_active_id();
        $user_role = role_user();
        $user = User::find(2);
        if (!$user){
            User::create([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'johndoe@example.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'phone' => '12345678901',
                'role_id' => $user_role,
                'status_id' => $active_status,
                'terms' => true
            ]);
        } else {
            echo ('Error! Database must be empty to seed! \n');
        }
    }
}
