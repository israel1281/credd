<?php

namespace App\Http\Controllers;

use App\Models\FwBillPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FwBillPaymentController extends Controller
{
    public function payAirtime(FwBillPayment $billPayment, Request $request) {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/0[7-9][01]\d{8}$/i']
        ]);
        $cardResponse = Http::withToken(config('flutterwave.secretKey'));
        $bpRequest = [
            
        ];
        try {
            $cardResponse = $cardResponse->post('https://api.flutterwave.com/v3/charges?type=card', $request->all());
        } catch (\Throwable $th) {
            return apiError('An error was encountered while processing this request.');
        }

        if ($cardResponse != null) {
            $data = $cardResponse->json();
            if ($data['status'] == 'success') {
                return $data;
            } else {
                return apiError($data['message']);
            }
        } else {
            return apiError('An error was encountered while processing this request.');
        }
    }
}
