<?php

namespace Database\Seeders;

use App\Models\VtuBillPayment;
use Illuminate\Database\Seeder;

class VtuBPAirtimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mtn = [
            'bp_type' => VtuBillPayment::TYPE_AIRTIME,
            'name' => 'MTN NIGERIA',
            'short_name' => 'MTN',
            'service_id' => 'mtn',
            'amount' => 0,
            'fee' => 0,
            'label_name' => 'Mobile Number',
            'image' => "/images/airtime/mtn.png",
            'status_id' => status_active_id()
        ];
        $airtel = [
            'bp_type' => VtuBillPayment::TYPE_AIRTIME,
            'name' => 'AIRTEL NIGERIA',
            'short_name' => 'AIRTEL',
            'service_id' => 'airtel',
            'amount' => 0,
            'fee' => 0,
            'label_name' => 'Mobile Number',
            'image' => "/images/airtime/airtel.png",
            'status_id' => status_active_id()
        ];
        $glo = [
            'bp_type' => VtuBillPayment::TYPE_AIRTIME,
            'name' => 'GLO NIGERIA',
            'short_name' => 'GLO',
            'service_id' => 'glo',
            'amount' => 0,
            'fee' => 0,
            'label_name' => 'Mobile Number',
            'image' => "/images/airtime/glo.png",
            'status_id' => status_active_id()
        ];
        $etisalat = [
            'bp_type' => VtuBillPayment::TYPE_AIRTIME,
            'name' => '9MOBILE NIGERIA',
            'short_name' => '9MOBILE',
            'service_id' => 'etisalat',
            'amount' => 0,
            'fee' => 0,
            'label_name' => 'Mobile Number',
            'image' => "/images/airtime/etisalat.png",
            'status_id' => status_active_id()
        ];

        VtuBillPayment::create($mtn);
        VtuBillPayment::create($airtel);
        VtuBillPayment::create($glo);
        VtuBillPayment::create($etisalat);
    }
}
