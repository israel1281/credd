<?php

namespace Database\Seeders;

use App\Models\FwBillPayment;
use Illuminate\Database\Seeder;

class FwBPAirtimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bpType = FwBillPayment::TYPE_AIRTIME;
        $mtn = [
            'bp_type' => $bpType,
            'name' => 'MTN NIGERIA',
            'short_name' => 'MTN NIGERIA',
            'biller_code' => 'BIL099',
            'biller_name' => 'MTN VTU',
            'commission' => 0.03,
            'country' => 'NG',
            'item_code' => 'AT099',
            'amount' => 0,
            'fee' => 0,
            'commission_on_fee' => false,
            'label_name' => 'Mobile Number',
            'image' => "/images/airtime/mtn.png",
        ];

        $airtel = [
            'bp_type' => $bpType,
            'name' => 'AIRTEL NIGERIA',
            'short_name' => 'AIRTEL NIGERIA',
            'biller_code' => 'BIL100',
            'biller_name' => 'AIRTEL VTU',
            'commission' => 0.03,
            'country' => 'NG',
            'item_code' => 'AT100',
            'amount' => 0,
            'fee' => 0,
            'commission_on_fee' => false,
            'label_name' => 'Mobile Number',
            'image' => "/images/airtime/airtel.png",
        ];

        $glo = [
            'bp_type' => $bpType,
            'name' => 'GLO NIGERIA',
            'short_name' => 'GLO NIGERIA',
            'biller_code' => 'BIL102',
            'biller_name' => 'GLO VTU',
            'commission' => 0.03,
            'country' => 'NG',
            'item_code' => 'AT102',
            'amount' => 0,
            'fee' => 0,
            'commission_on_fee' => false,
            'label_name' => 'Mobile Number',
            'image' => "/images/airtime/glo.png",
        ];

        $etisalat = [
            'bp_type' => $bpType,
            'name' => '9MOBILE NIGERIA',
            'short_name' => '9MOBILE NIGERIA',
            'biller_code' => 'BIL103',
            'biller_name' => '9MOBILE VTU',
            'commission' => 0.03,
            'country' => 'NG',
            'item_code' => 'AT103',
            'amount' => 0,
            'fee' => 0,
            'commission_on_fee' => false,
            'label_name' => 'Mobile Number',
            'image' => "/images/airtime/9mobile.png",
        ];

        FwBillPayment::create($mtn);
        FwBillPayment::create($airtel);
        FwBillPayment::create($glo);
        FwBillPayment::create($etisalat);
    }
}
