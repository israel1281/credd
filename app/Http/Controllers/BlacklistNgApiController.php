<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BlacklistNgApiController extends Controller
{
    public function search(Request $request) {
        if (auth()->user()->is_blacklist_ng === true) {
            return apiSuccess(auth()->user()->is_blacklist_ng, "Sorry, but you're not eligible for a loan!");
        } else if (auth()->user()->is_blacklist_ng === false) {
            return apiSuccess(auth()->user()->is_blacklist_ng, "You are eligible for a loan. Please complete your KYC in the loan application page to continue.");
        }
        $request->validate([
            'bvn' => 'size:11'
        ], [
            'bvn.size' => 'Your bvn must be 11 characters'
        ]);
        $bvn = $request->bvn;
        return $bvn;
        $sk = config('blacklistng.sk');
        $response = Http::withToken($sk, '')->get(config('blacklistng.url')."/bvn-boolean-search/".$bvn);

        if ($response->ok()) {
            $isBlacklist = $response->object()->data;
            if ($isBlacklist) {
                $message = "Sorry, but you are not eligible to get a loan";
                $isBlacklist = true;
            } else {
                $message = "Congratulations, you are eligible for a loan. Please complete your KYC in the loan application page to continue.";
            }
            auth()->user()->is_blacklist_ng = $isBlacklist;
            auth()->user()->save();
            return apiSuccess($isBlacklist, $message);
        } else {
            return apiError('Error checking loan eligibility. Please try again later!');
        }
    }

    public function add(User $user) {
        if (!$user->bvn_verified) {
            return apiError("You have to verify this user's bvn before adding to blacklist");
        }
        if (!$user->has_pending_loan) {
            return apiError("Sorry, this user does not have an active loan");
        }
        if (!$user->loanRequests()->active()->first()) {
            return apiError("Sorry, this user does not have an active loan request");
        }
        if ($user->is_blacklist_ng === true) {
            return apiSuccess(null, "This user has already being blacklisted");
        }
        $sk = config('blacklistng.sk');
        $response = Http::withToken($sk, '')->post(config('blacklistng.url').'/add', [
            'name' => $user->name,
            'bvn' => $user->bvn->bvn,
            'phone' => $user->phone,
            'email' => $user->email,
            'loan_amount' => $user->wallet->loan_amt,
            'amount_paid' => 0,
            'due_date' => $user->loanRequests()->active()->first()->expire_at->format('d-m-Y')
        ]);

        if ($response->ok()) {
            $user->is_blacklist_ng = true;
            $user->save();
            return apiSuccess(null, $response->object()->message);
        } else {
            return apiError('Error adding user to blacklist. Please check your blacklist ng credits or try again later!');
        }
    }

    public function remove(User $user) {
        if (!$user->bvn_verified) {
            return apiError("You have to verify this user's bvn before removing from blacklist");
        }
        if (!$user->is_blacklist_ng) {
            return apiSuccess(null, "This user is not blacklisted!");
        }
        $sk = config('blacklistng.sk');
        $response = Http::withToken($sk, '')->post(config('blacklistng.url').'/delete', [
            'bvn' => $user->bvn->bvn,
        ]);

        if ($response->ok()) {
            $user->is_blacklist_ng = false;
            $user->save();
            return apiSuccess(null, 'User removed from blacklist!');
        } else {
            return apiError('Error removing user from blacklist: '.$response->object()->message);
        }
    }
}
