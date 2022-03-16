<?php

namespace Database\Seeders;

use App\Models\VtuBillPayment;
use Illuminate\Database\Seeder;

class VtuBPElectricitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bpType = VtuBillPayment::TYPE_ELECTRICITY;
        $aedc = [
            'bp_type' => $bpType,
            'name' => 'Abuja Electricity Distribution Company (AEDC)',
            'short_name' => 'AEDC',
            'service_id' => 'abuja-electric',
            'variation_id' => 'prepaid',
            'amount' => 0,
            'fee' => 0,
            'label_name' => 'Meter Number',
            'image' => "/images/{$bpType}/aedc.png",
            'status_id' => status_active_id()
        ];

        $ekedc = [
            'bp_type' => $bpType,
            'name' => 'Eko Electricity Distribution Company (EKEDC)',
            'short_name' => 'EKEDC',
            'service_id' => 'eko-electric',
            'variation_id' => 'prepaid',
            'amount' => 0,
            'fee' => 0,
            'label_name' => 'Meter Number',
            'image' => "/images/{$bpType}/ekedc.png",
            'status_id' => status_active_id()
        ];

        $ibedc = [
            'bp_type' => $bpType,
            'name' => 'Ibadan Electricity Distribution Company (IBEDC)',
            'short_name' => 'IBEDC',
            'service_id' => 'ibadan-electric',
            'variation_id' => 'prepaid',
            'amount' => 0,
            'fee' => 0,
            'label_name' => 'Meter Number',
            'image' => "/images/{$bpType}/ibedc.png",
            'status_id' => status_active_id()
        ];

        $ikedc = [
            'bp_type' => $bpType,
            'name' => 'Ikeja Electricity Distribution Company (IKEDC)',
            'short_name' => 'IKEDC',
            'service_id' => 'ikeja-electric',
            'variation_id' => 'prepaid',
            'amount' => 0,
            'fee' => 0,
            'label_name' => 'Meter Number',
            'image' => "/images/{$bpType}/ikedc.png",
            'status_id' => status_active_id()
        ];

        $jedplc = [
            'bp_type' => $bpType,
            'name' => 'Jos Electricity Distribution PLC (JEDplc)',
            'short_name' => 'JEDplc',
            'service_id' => 'jos-electric',
            'variation_id' => 'prepaid',
            'amount' => 0,
            'fee' => 0,
            'label_name' => 'Meter Number',
            'image' => "/images/{$bpType}/jedplc.png",
            'status_id' => status_active_id()
        ];

        $kaedco = [
            'bp_type' => $bpType,
            'name' => 'Kaduna Electricity Distribution Company (KAEDCO)',
            'short_name' => 'KAEDCO',
            'service_id' => 'kaduna-electric',
            'variation_id' => 'prepaid',
            'amount' => 0,
            'fee' => 0,
            'label_name' => 'Meter Number',
            'image' => "/images/{$bpType}/kaedco.png",
            'status_id' => status_active_id()
        ];

        $kedco = [
            'bp_type' => $bpType,
            'name' => 'Kano Electricity Distribution Company (KEDCO)',
            'short_name' => 'KEDCO',
            'service_id' => 'kano-electric',
            'variation_id' => 'prepaid',
            'amount' => 0,
            'fee' => 0,
            'label_name' => 'Meter Number',
            'image' => "/images/{$bpType}/kedco.png",
            'status_id' => status_active_id()
        ];

        $phed = [
            'bp_type' => $bpType,
            'name' => 'Port Harcourt Electricity Distribution Company (PHED)',
            'short_name' => 'PHED',
            'service_id' => 'portharcourt-electric',
            'variation_id' => 'prepaid',
            'amount' => 0,
            'fee' => 0,
            'label_name' => 'Meter Number',
            'image' => "/images/{$bpType}/phed.png",
            'status_id' => status_active_id()
        ];

        VtuBillPayment::create($aedc);
        VtuBillPayment::create($ekedc);
        VtuBillPayment::create($ibedc);
        VtuBillPayment::create($ikedc);
        VtuBillPayment::create($kaedco);
        VtuBillPayment::create($phed);
        VtuBillPayment::create($jedplc);
        VtuBillPayment::create($kedco);
    }
}
