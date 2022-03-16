<?php

namespace Database\Seeders;

use App\Models\VtuBillPayment;
use Illuminate\Database\Seeder;

class VtuBPCableDstvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $padi = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'DStv Padi',
            'short_name' => 'DStv Padi',
            'service_id' => 'dstv',
            'variation_id' => 'dstv-padi',
            'amount' => 1850,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/dstv.png",
            'status_id' => status_active_id()
        ];
        $yanga = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'DStv Yanga',
            'short_name' => 'DStv Yanga',
            'service_id' => 'dstv',
            'variation_id' => 'dstv-yanga',
            'amount' => 2565,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/dstv.png",
            'status_id' => status_active_id()
        ];
        $confam = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'DStv Confam',
            'short_name' => 'DStv Confam',
            'service_id' => 'dstv',
            'variation_id' => 'dstv-confam',
            'amount' => 4615,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/dstv.png",
            'status_id' => status_active_id()
        ];

        $asia = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'DStv Asia',
            'short_name' => 'DStv Asia',
            'service_id' => 'dstv',
            'variation_id' => 'dstv6',
            'amount' => 6200,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/dstv.png",
            'status_id' => status_active_id()
        ];

        $compact = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'DStv Compact',
            'short_name' => 'DStv Compact',
            'service_id' => 'dstv',
            'variation_id' => 'dstv79',
            'amount' => 7900,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/dstv.png",
            'status_id' => status_active_id()
        ];

        $compactPlus = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'DStv Compact',
            'short_name' => 'DStv Compact',
            'service_id' => 'dstv',
            'variation_id' => 'dstv7',
            'amount' => 12400,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/dstv.png",
            'status_id' => status_active_id()
        ];

        $premium = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'DStv Premium',
            'short_name' => 'DStv Premium',
            'service_id' => 'dstv',
            'variation_id' => 'dstv3',
            'amount' => 18400,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/dstv.png",
            'status_id' => status_active_id()
        ];

        $premiumAsia = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'DStv Premium Asia',
            'short_name' => 'DStv Premium Asia',
            'service_id' => 'dstv',
            'variation_id' => 'dstv10',
            'amount' => 20500,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/dstv.png",
            'status_id' => status_active_id()
        ];

        $premiumFrench = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'DStv Premium-French',
            'short_name' => 'DStv Premium-French',
            'service_id' => 'dstv',
            'variation_id' => 'dstv9',
            'amount' => 25550,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/dstv.png",
            'status_id' => status_active_id()
        ];

        $padiExtra = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'DStv Padi + ExtraView',
            'short_name' => 'DStv Padi + ExtraView',
            'service_id' => 'dstv',
            'variation_id' => 'padi-extra',
            'amount' => 4350,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/dstv.png",
            'status_id' => status_active_id()
        ];

        $yangaExtra = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'DStv Yanga + ExtraView',
            'short_name' => 'DStv Yanga + ExtraView',
            'service_id' => 'dstv',
            'variation_id' => 'yanga-extra',
            'amount' => 5065,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/dstv.png",
            'status_id' => status_active_id()
        ];

        $confamExtra = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'DStv Confam + ExtraView',
            'short_name' => 'DStv Confam + ExtraView',
            'service_id' => 'dstv',
            'variation_id' => 'confam-extra',
            'amount' => 7115,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/dstv.png",
            'status_id' => status_active_id()
        ];

        $compactAsia = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'DStv Compact + Asia',
            'short_name' => 'DStv Compact + Asia',
            'service_id' => 'dstv',
            'variation_id' => 'com-asia',
            'amount' => 14100,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/dstv.png",
            'status_id' => status_active_id()
        ];

        $compactExtra = [
            'bp_type' => VtuBillPayment::TYPE_CABLE,
            'name' => 'DStv Compact + ExtraView',
            'short_name' => 'DStv Compact + ExtraView',
            'service_id' => 'dstv',
            'variation_id' => 'dstv30',
            'amount' => 10400,
            'fee' => 0,
            'label_name' => 'Smart Card Number',
            'image' => "/images/cables/dstv.png",
            'status_id' => status_active_id()
        ];

        VtuBillPayment::create($padi);
        VtuBillPayment::create($yanga);
        VtuBillPayment::create($confam);
        VtuBillPayment::create($asia);
        VtuBillPayment::create($compact);
        VtuBillPayment::create($compactPlus);
        VtuBillPayment::create($premium);
        VtuBillPayment::create($premiumAsia);
        VtuBillPayment::create($premiumFrench);
        VtuBillPayment::create($padiExtra);
        VtuBillPayment::create($yangaExtra);
        VtuBillPayment::create($confamExtra);
        VtuBillPayment::create($compactAsia);
        VtuBillPayment::create($compactExtra);
    }
}
