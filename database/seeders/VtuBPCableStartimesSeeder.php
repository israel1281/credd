<?php

namespace Database\Seeders;

use App\Models\VtuBillPayment;
use Illuminate\Database\Seeder;

class VtuBPCableStartimesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nova = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'Startimes Nova',
            'short_name' => 'Startimes Nova',
            'service_id' => 'startimes',
            'variation_id' => 'startimes-nova',
            'amount' => 900,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/startimes.png",
            'status_id' => status_active_id()
        ];

        $basic = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'Startimes Basic',
            'short_name' => 'Startimes Basic',
            'service_id' => 'startimes',
            'variation_id' => 'startimes-basic',
            'amount' => 1700,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/startimes.png",
            'status_id' => status_active_id()
        ];

        $smart = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'Startimes Smart',
            'short_name' => 'Startimes Smart',
            'service_id' => 'startimes',
            'variation_id' => 'startimes-smart',
            'amount' => 1700,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/startimes.png",
            'status_id' => status_active_id()
        ];

        $classic = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'Startimes Classic',
            'short_name' => 'Startimes Classic',
            'service_id' => 'startimes',
            'variation_id' => 'startimes-classic',
            'amount' => 1700,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/startimes.png",
            'status_id' => status_active_id()
        ];

        $super = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'Startimes Super',
            'short_name' => 'Startimes Super',
            'service_id' => 'startimes',
            'variation_id' => 'startimes-super',
            'amount' => 1700,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/startimes.png",
            'status_id' => status_active_id()
        ];

        VtuBillPayment::create($nova);
        VtuBillPayment::create($basic);
        VtuBillPayment::create($smart);
        VtuBillPayment::create($classic);
        VtuBillPayment::create($super);
    }
}
