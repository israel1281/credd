<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = Status::first();
        if (!$status) {
            $statuses = [
                ['title' => 'active', 'colour' => 'primary,success'],
                ['title' => 'inactive', 'colour' => 'muted,secondary,light'],
                ['title' => 'pending', 'colour' => 'info,warning'],
                ['title' => 'cancelled', 'colour' => 'warning'],
                ['title' => 'processing', 'colour' => 'info'],
                ['title' => 'completed', 'colour' => 'success'],
                ['title' => 'blocked', 'colour' => 'danger'],
                ['title' => 'failed', 'colour' => 'danger'],
                ['title' => 'invalid', 'colour' => 'danger'],
                ['title' => 'success', 'colour' => 'success'],
                ['title' => 'accepted', 'colour' => 'success'],
                ['title' => 'rejected', 'colour' => 'danger'],
            ];

            foreach($statuses as $status) {
                Status::create($status);
            }
        } else {
            echo('Error! Database must be empty to seed!\n');
        }
    }
}
