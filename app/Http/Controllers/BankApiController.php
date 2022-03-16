<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BankApiController extends Controller
{
    public function createAccount(Request $request) {
        $request->validate([
            'data' => ['required', 'string']
        ]);
        $bankResponse = Http::withHeaders([
            'applicationId' => '9750dbb7-f800-4f45-ac2e-57d874eb946c',
            'Ocp-Apim-Subscription-Key' => '894487a9ca5c4b02986702076467550f'
        ])
        ->post('https://api-sandbox.accessbankplc.com/acc-gateway/v1/vnuban/account', $request->all());
        // [
        //     "customerName" => "Obinna",
        //     "customerEmail" => "paul@gmail.com",
        //     "customerPhone" => "08026978647",
        //     "accountName" => "Obinna Elvis",
        //     "paymentCurrency" => "NGN",
        //     "paymentAmount" => 0,
        //     "expirationInMin" => 50,
        //     "paymentReference" => "VNUBAN88738949494300",
        //     "auditId" => "1000",
        //     "merchantId" => "56"
        // ]

        return apiSuccess($bankResponse->json());
    }
}
