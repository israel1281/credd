<?php

namespace App\Http\Controllers;

use App\Http\Resources\WalletResource;
use App\Mail\LoanRequestAdminMail;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Notifications\AdminDefaultNotify;
use App\Notifications\DebugEmailNotify;
use App\Notifications\LoanNotify;
use App\Notifications\UserDepositNotify;
use App\Notifications\WithdrawRequestAdminNotify;
use App\Notifications\WithdrawStatusNotify;
use Illuminate\Support\Facades\Mail;

class DataController extends Controller
{
    // Get User
    public function me() {
        if (!auth()->user()->wallet) {
            auth()->user()->wallet()->create([
                'acc_no' => generateAccountNumber(),
                'is_default' => true,
                'status_id' => status_inactive_id()
            ]);
        }
        return apiSuccess(auth()->user()->load(['bvn', 'notifications']));
    }

    // Wallets
    public function walletDefault() {
        return apiSuccess(
            new WalletResource(auth()->user()->wallet)
        );
    }

    public function testMail() {
        $loanRequest = new LoanRequestAdminMail(\App\Models\LoanRequest::find(1));
        $welcomeMail = new WelcomeMail(\App\Models\User::find(2));
        Mail::to('dreamor47@gmail.com')->send(new WelcomeMail(\App\Models\User::find(2)));
        return $welcomeMail;
    }

    public function testNotify() {
        $user = User::find(2);
        $adminUser = User::find(1);
        $loanNotify = (new LoanNotify(LoanNotify::ACCEPT, \App\Models\LoanRequest::find(1)->amount_string))
                ->toMail($user);
        $withdrawRequestNotify = (new WithdrawStatusNotify(WithdrawStatusNotify::REJECT, \App\Models\WithdrawRequest::find(1)->amount_string))
                ->toMail($user);
        $withdrawAdminNotify = (new WithdrawRequestAdminNotify($user, config('app.currency').'5000'))
        ->toMail($user);
        $depositNotify = (new UserDepositNotify(\App\Models\Transaction::find(1), 'Flutterwave'))
        ->toMail($user);
        $adminDefault = (new AdminDefaultNotify('I wn notify', 'Default admin default'))
        ->toMail($user);
        $emailDebug = (new DebugEmailNotify('I wn notify', 'Default admin default'))
        ->toMail($user);
        return $emailDebug;
    }
}
