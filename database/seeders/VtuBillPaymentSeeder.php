<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VtuBillPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(VtuBPAirtimeSeeder::class);
        $this->call(VtuBPCableGotvSeeder::class);
        $this->call(VtuBPCableStartimesSeeder::class);
        $this->call(VtuBPCableDstvSeeder::class);
        $this->call(VtuBPElectricitySeeder::class);
    }
}
