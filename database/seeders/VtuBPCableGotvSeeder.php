<?php

namespace Database\Seeders;

use App\Models\VtuBillPayment;
use Illuminate\Database\Seeder;

class VtuBPCableGotvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $smallie = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'GOtv Smallie',
            'short_name' => 'Gotv Smallie',
            'service_id' => 'gotv',
            'variation_id' => 'gotv-smallie',
            'amount' => 800,
            'fee' => 0,
            'label_name' => 'IUC Number',
            'image' => "/images/cables/gotv.png",
            'status_id' => status_active_id()
        ];
        $jinja = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'GOtv Jinja',
            'short_name' => 'Gotv Jinja',
            'service_id' => 'gotv',
            'variation_id' => 'gotv-jinja',
            'amount' => 1640,
            'fee' => 0,
            'label_name' => 'IUC Number',
            'image' => "/images/cables/gotv.png",
            'status_id' => status_active_id()
        ];
        $jolli = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'GOtv Jolli',
            'short_name' => 'Gotv Jolli',
            'service_id' => 'gotv',
            'variation_id' => 'gotv-jolli',
            'amount' => 2460,
            'fee' => 0,
            'label_name' => 'IUC Number',
            'image' => "/images/cables/gotv.png",
            'status_id' => status_active_id()
        ];
        $max = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'GOtv Max',
            'short_name' => 'Gotv Max',
            'service_id' => 'gotv',
            'variation_id' => 'gotv-max',
            'amount' => 3600,
            'fee' => 0,
            'label_name' => 'IUC Number',
            'image' => "/images/cables/gotv.png",
            'status_id' => status_active_id()
        ];
        $supa = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'GOtv Supa',
            'short_name' => 'Gotv Supa',
            'service_id' => 'gotv',
            'variation_id' => 'gotv-supa',
            'amount' => 5500,
            'fee' => 0,
            'label_name' => 'IUC Number',
            'image' => "/images/cables/gotv.png",
            'status_id' => status_active_id()
        ];

        VtuBillPayment::create($smallie);
        VtuBillPayment::create($jinja);
        VtuBillPayment::create($jolli);
        VtuBillPayment::create($max);
        VtuBillPayment::create($supa);
    }
}
