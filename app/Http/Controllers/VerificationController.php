<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify($user_id, Request $request) {
        if (!$request->hasValidSignature()) {
            abort(403, "Invalid/Expired url provided.");
        }

        $user = User::findOrFail($user_id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->to('/');
    }

    public function resend() {
        if (auth()->user()->hasVerifiedEmail()) {
            return apiError("Email already verified. You can proceed to login to your dashboard", 400);
        }

        auth()->user()->sendEmailVerificationNotification();

        return apiSuccess(null, "Email verification link sent successfully! Please check your email before sending another one.");
    }
}
