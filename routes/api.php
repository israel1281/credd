<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KycController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BvnApiController;
use App\Http\Controllers\BankApiController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\FlutterwaveController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\BlacklistNgApiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwilioController;
use App\Http\Controllers\VtuController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Login with token
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::prefix('bank')->group(function() {
    Route::get('create-account', [BankApiController::class, 'createAccount']);
});
// flutterwave payment callback
Route::get('fw/callback/{user}', [FlutterwaveController::class, 'callback'])->name('fw.callback');
Route::get('fw/callback/{user}/withdraw', [FlutterwaveController::class, 'verifyCardCallback'])->name('fw.callback.withdraw');

// Route::get('test-mail', [DataController::class, 'testMail']);
// Route::get('test-notify', [DataController::class, 'testNotify']);

Route::get('/me', [ DataController::class, 'me'])->middleware('auth:sanctum');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum', 'verified', 'account_status']], function () {
    Route::prefix('wallets')->group(function() {
        Route::get('/default', [ DataController::class, 'walletDefault']);
        Route::get('/deposit', [ WalletController::class, 'deposit']);
    });

    // Bank Api
    Route::post('/bank/create-account', [BankApiController::class, 'createAccount']);

    // Kyc
    Route::post('/kyc', [ KycController::class, 'store' ]);

    // Bvn Verification
    Route::post('bvn/verify', [BvnApiController::class, 'verify']);

    // Blacklist NG Search
    Route::post('blacklistng/search', [BlacklistNgApiController::class, 'search']);

    // Transactions
    Route::get('transactions/{count}/{status?}', [ TransactionsController::class, 'userQuery']);

    // Twilio Verify phone
    Route::post('phone/verify/send-code', [TwilioController::class, 'sendVerificationCode']);
    Route::post('phone/verify', [TwilioController::class, 'verify']);
    Route::put('phone/update', [ProfileController::class, 'updatePhone']);

    // Flutterwave api
    Route::get('fw/generate-reference', [FlutterwaveController::class, 'generateTransactionReference']);
    Route::post('fw/pay', [FlutterwaveController::class, 'initialize']);
    Route::post('fw/card/initialize', [FlutterwaveController::class, 'cardInitialize']);
    Route::post('fw/card/validate-charge', [FlutterwaveController::class, 'validateCharge']);
    Route::post('fw/card/verify', [FlutterwaveController::class, 'verifyPayment']);

    // VTU Api
    Route::prefix('vtu')->group(function() {
        Route::post('/verify-customer/', [VtuController::class, 'verifyCustomer']);

        Route::get('/transactions/{bpType}/{count}/{status?}', [VtuController::class, 'getBPTransactions']);

        Route::get('/airtime', [VtuController::class, 'airtimePackages']);
        Route::post('/airtime/{billPayment}', [VtuController::class, 'payAirtime']);

        Route::prefix('cable')->group(function() {
            Route::get('/', [VtuController::class, 'getCablePackages']);

            Route::get('/gotv', [VtuController::class, 'gotvPackages']);
            Route::get('/dstv', [VtuController::class, 'dstvPackages']);
            Route::get('/startimes', [VtuController::class, 'startimesPackages']);

            Route::post('/{billPayment}', [VtuController::class, 'payCable']);
        });

        Route::get('/electricity', [VtuController::class, 'electricityPackages']);
        Route::post('/electricity/{billPayment}', [VtuController::class, 'payElectricity']);

    });

    Route::prefix('loan')->group(function() {
        Route::post('apply', [LoanController::class, 'apply']);
    });
    Route::prefix('withdraw')->group(function() {
        Route::post('/', [WithdrawController::class, 'withdraw']);
        Route::get('/pending', [WithdrawController::class, 'userPending']);
        Route::post('{withdrawRequest}/cancel', [WithdrawController::class, 'cancel']);
    });
    Route::put('notifications/read', [UsersController::class, 'markNotificationsRead']);
    Route::delete('notifications/clear', [UsersController::class, 'clearNotifications']);

    Route::prefix('admin')->group(function() {
        Route::prefix('loan')->group(function() {
            Route::get('pending/{count}', [LoanController::class, 'adminPending']);
            Route::get('all/{count}/{status?}', [LoanController::class, 'query']);
            Route::post('{loanRequest}/{response}', [LoanController::class, 'respond']);
        });
        Route::prefix('kycs')->group(function() {
            Route::get('all/{count}/{status?}', [KycController::class, 'getKycs']);
            Route::post('respond/{kyc}/{response}', [KycController::class, 'kycRespond']);
        });
        Route::prefix('users')->group(function() {
            Route::put('/{user}/change-role', [UsersController::class, 'changeRole']);
            Route::get('/{count}/{status?}', [UsersController::class, 'query']);
            Route::put('/{user}/clear-loan', [UsersController::class, 'clearLoan']);
            Route::put('/{user}/status/block', [UsersController::class, 'block']);
            Route::put('/{user}/status/active', [UsersController::class, 'active']);
            Route::put('/{user}/blacklist/add', [BlacklistNgApiController::class, 'add']);
            Route::put('/{user}/blacklist/remove', [BlacklistNgApiController::class, 'remove']);
            Route::put('/{user}/update-balance', [UsersController::class, 'updateBalance']);
            Route::put('/{user}/update-loan-balance', [UsersController::class, 'updateLoanBalance']);
        });
        Route::prefix('withdraw')->group(function() {
            Route::get('pending/{count}', [WithdrawController::class, 'adminPending']);
            Route::get('all/{count}/{status?}', [WithdrawController::class, 'query']);
            Route::post('respond/{withdrawRequest}/{response}', [WithdrawController::class, 'respond']);
        });
    });
});
