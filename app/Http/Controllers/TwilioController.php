<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class TwilioController extends Controller
{
    public function sendVerificationCode() {
        if (auth()->user()->phone_verified_at) {
            return apiError('Phone number already verified. Please go back to dashboard!');
        }
        if (!auth()->user()->bvn_verified) {
            return apiError("BVN not verified. Please verify your bvn to proceed.");
        }

        // Send verification Code
        $twilio = $this->getTwilio();
        $vid = config('twilio.verification_sid');

        $phone = "+".formatPhoneNumber(auth()->user()->bvn->phone);

        if ($phone) {
            try {
                $verification = $twilio->verify->v2->services($vid)
                                ->verifications->create($phone, "sms");
                return apiSuccess(
                            $verification, "Verification code sent successfully!\nPlease check your mobile phone before sending another one.");
            } catch (\Exception $e) {
                reportError('Phone Verification Error: Could not send OTP. \n'.$e->getMessage());
                // return $e->getMessage();
                return apiError("Something went wrong!\nPlease try again later.");
            }
        } else {
            return apiError('Invalid phone number');
        }

    }
    public function verify(Request $request) {
        if (auth()->user()->phone_verified_at) {
            return apiError('Phone number already verified. Please go back to dashboard!');
        }
        if (!auth()->user()->bvn_verified) {
            return apiError("BVN not verified. Please verify your bvn to proceed.");
        }
        $request->validate([
            'token' => ['required', 'string', 'size:6']
        ]);
        $phone = "+".formatPhoneNumber(auth()->user()->bvn->phone);
        if ($phone && $request->token) {
            $twilio = $this->getTwilio();
            $vid = config('twilio.verification_sid');
            try {
                $verificationCheck = $twilio->verify->v2->services($vid)
                                        ->verificationChecks
                                        ->create($request->token,
                                                ["to" => $phone]
                                        );
            } catch (\Exception $e) {
                reportError("Twilio Verification Error: ".$e->getMessage());
                // return $e->getMessage();
                return apiError('Invalid verification, please try again!');
            }
        } else {
            return apiError('Invalid token or phone number');
        }
        // Check verification status
        if ($verificationCheck->status === 'approved') {
            auth()->user()->phone_verified_at = now();
            auth()->user()->phone = auth()->user()->bvn->phone;
            auth()->user()->save();
            return apiSuccess(null, 'Verification successful!');
        } else {
            return apiError('Invalid verification token, please try again!');
        }
    }

    public function getTwilio() {
        $sid = config('twilio.account_sid');
        $token = config('twilio.auth_token');
        return new Client($sid, $token);
    }
}
