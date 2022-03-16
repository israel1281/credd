<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class BvnApiController extends Controller
{
    public function verify(Request $request) {
        if (auth()->user()->bvn_verified) {
            return apiSuccess(null, 'Your BVN has already been verified!');
        }
        $request->validate([
            'bvn' => 'size:11|unique:bvn_verifications,bvn'
        ], [
            'bvn.size' => 'Your bvn must be 11 characters',
            'bvn.unique' => 'This bvn has already been linked to another account'
        ]);
        $bvn = $request->bvn;
        $sk = App::environment('production') ? config('verifyme.sk_live') : config('verifyme.sk_test');
        $response = Http::withToken($sk)
            ->post(config('verifyme.bvn_url').$bvn, [
                'firstname' => 'Obinna',
                'lastname' => 'Okechukwu',
            ]);

        $status = $response['status'];

        if ($status == 'success') {
            $data = $response['data'];
            auth()->user()->bvn()->create([
                'bvn' => $bvn,
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'middlename' => $data['middlename'],
                'gender' => $data['gender'],
                'phone' => $data['phone'],
                'birthdate' => $data['birthdate'],
                'valid_lastname' => $data['fieldMatches']['lastname'],
            ]);
            auth()->user()->wallet()->update([
                'status_id' => status_active_id()
            ]);
            auth()->user()->update([
                'status_id' => status_active_id()
            ]);
            return apiSuccess(null, 'BVN verification successful!');
        } else {
            $errorCode = $response['code'];
            if (($response->status() == 404) && ($errorCode == 'NOT_FOUND_ERROR'))
                return apiError('BVN provided is not found. Please provide a valid bvn!', Response::HTTP_NOT_FOUND);
            else
                return apiError('Sorry but your bvn cannot be verified at the moment. Please try again!', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        dd();
    }
}
