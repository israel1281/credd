<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\AdminDefaultNotify;
use App\Notifications\LoanClearedNotify;
use App\Notifications\UserDepositNotify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Rave;
use Symfony\Component\HttpFoundation\Response;

class FlutterwaveController extends Controller
{
    public function initialize(Request $request) {
        $request->validate([
            'amount' => 'required|integer|min:10|max:100000'
        ]);
        //This generates a payment reference
        $reference = Rave::generateReference();

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer,ussd',
            'amount' => $request->amount, // Change to request
            'email' => auth()->user()->email, //Change to request or auth user
            'tx_ref' => $reference,
            'currency' => "NGN",
            'redirect_url' => route('fw.callback.withdraw', auth()->user()->id),
            'customer' => [
                'email' => auth()->user()->email, // Change to request or auth user
                "phone_number" => auth()->user()->phone, // Change to request or user
                "name" => auth()->user()->name // change to request or user
            ],

            "customizations" => [
                "title" => config('app.name'),
                "description" => "Deposit into wallet"
            ]
        ];

        auth()->user()->transactions()->create([
            'name' => Transaction::CREDIT,
            'txn_no' => $reference,
            'amt' => $request->amount,
            'model_type' => Transaction::DEPOSIT,
            'model_id' => 0,
            'status_id' => status_pending_id()
        ]);

        $payment = Rave::initializePayment($data);

        if ($payment['status'] !== 'success') {
            // notify something went wrong
            return apiError('Oops, something went wrong while connecting to flutterwave server', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // return redirect($payment['data']['link']);
        return apiSuccess([
            'payment_link' => $payment['data']['link']
        ]);
    }

    public function callback(User $user, Request $request) {
        $request->validate([
            'status' => 'required',
            'tx_ref' => 'required',
            'transaction_id' => 'required'
        ]);
        $status = $request->status;
        // dd($user->transactions);

        $pendingTransaction = $user->transactions()->where('txn_no', $request->tx_ref)->first();
        // dd($pendingTransaction);
        // Verify if pending transactin exists
        if($pendingTransaction == null) {
            return redirect(config('app.spa_url').'/dashboard?pm=fw&deposit=invalid');
        }

        //if payment is successful
        if ($status ==  'successful') {
            $transactionID = Rave::getTransactionIDFromCallback();
            $data = Rave::verifyTransaction($transactionID);

            // dd($data);

            $dataInfo = null;
            // Verify transaction reference and amount
            if ($data == null) {
                return redirect(config('app.spa_url').'/dashboard?pm=fw&deposit=invalid');
            } else {
                $dataInfo = $data['data'];
                if (($pendingTransaction->txn_no != $dataInfo['tx_ref']) && ($pendingTransaction->amt != $dataInfo['amount'])) {
                    return redirect(config('app.spa_url').'/dashboard?pm=fw&deposit=invalid');
                } else {
                    $dataInfo = $data['data'];
                };
            }

            $amount = $pendingTransaction->amt;
            $tx_ref = $pendingTransaction->txn_no;

            // Deposit Transaction
            $deposit = Deposit::create([
                'payment_method' => Deposit::FLUTTERWAVE
            ]);
            $pendingTransaction->model_id = $deposit->id;
            $pendingTransaction->status_id = status_completed_id();

            // Check if user has outstanding loan and resolve loan
            if ($user->wallet->loan_amt > 0) {
                // resolve loan from deposit
                $currentLoanBalance = $user->wallet->loan_amt;
                $loanBalance = $currentLoanBalance - $amount;
                if ($loanBalance > 0) {
                    // There is pending loan
                    $user->wallet->loan_amt = $loanBalance;
                    $user->transactions()->create([
                        'name' => Transaction::DEBIT,
                        'txn_no' => $tx_ref,
                        'amt' => $amount,
                        'prev_bal' => $user->wallet->amt,
                        'new_bal' => $user->wallet->amt,
                        'model_type' => Transaction::LOAN_DEBIT,
                        'model_id' => 0,
                        'status_id' => status_completed_id()
                    ]);
                }
                else if ($loanBalance < 0) {
                    // Loan resolved and extra amount remaining
                    $user->wallet->loan_amt = 0;
                    $prev_bal = $user->wallet->amt;
                    // Add balance to wallets
                    $user->wallet->amt += $loanBalance * -1;
                    $new_bal = $user->wallet->amt;
                    $loanRequests = $user->loanRequests()->active()->get();
                    $loanRequests->update([
                        'status_id' => status_completed_id()
                    ]);
                    $user->transactions()->create([
                        'name' => Transaction::DEBIT,
                        'txn_no' => $tx_ref,
                        'amt' => $currentLoanBalance,
                        'prev_bal' => $prev_bal,
                        'new_bal' => $new_bal,
                        'model_type' => Transaction::LOAN_DEBIT,
                        'model_id' => 0,
                        'status_id' => status_completed_id()
                    ]);
                    $user->loanRequests()->active()->update([
                        'status_id' => status_completed_id()
                    ]);


                    $user->notify(new LoanClearedNotify(config('app.currency').$currentLoanBalance));
                }
                else {
                    // Resolved Loan
                    $user->wallet->loan_amt = 0;
                    $user->transactions()->create([
                        'name' => Transaction::DEBIT,
                        'txn_no' => $tx_ref,
                        'amt' => $currentLoanBalance,
                        'prev_bal' => $user->wallet->amt,
                        'new_bal' => $user->wallet->amt,
                        'model_type' => Transaction::LOAN_DEBIT,
                        'model_id' => 0,
                        'status_id' => status_completed_id()
                    ]);

                    $user->loanRequests()->active()->update([
                        'status_id' => status_completed_id()
                    ]);

                    $user->notify(new LoanClearedNotify(config('app.currency').$currentLoanBalance));
                }
            } else {
                $pendingTransaction->prev_bal = $user->wallet->amt;
                $user->wallet->amt += $amount;
                $pendingTransaction->new_bal = $user->wallet->amt;
            }
            $pendingTransaction->save();
            $user->wallet->save();
            $paymentMethod = 'Flutterwave';
            try {
                $user->notify(new UserDepositNotify($pendingTransaction, 'Flutterwave'));
                Notification::send(User::admin()->get(),
                    new AdminDefaultNotify(config('app.name').' User Deposit',
                        'New deposit of '.$pendingTransaction->amount_string.' from '.$user->name.' using '.$paymentMethod.'. '));
            } catch (\Throwable $th) {
                reportError($th->getMessage());
            }
            return redirect(config('app.spa_url').'/dashboard?pm=fw&deposit=success');
        }
        elseif ($status ==  'cancelled'){
            $pendingTransaction->status_id = status_cancelled_id();
            $pendingTransaction->save();
            return redirect(config('app.spa_url').'/dashboard?pm=fw&deposit=cancelled');
        }
        else {
            $pendingTransaction->status_id = status_failed_id();
            $pendingTransaction->save();
            return redirect(config('app.spa_url').'/dashboard?pm=fw&deposit=error');
        }
    }

    public function verifyCardCallback(User $user, Request $request) {
        $request->validate([
            'status' => 'required',
            'tx_ref' => 'required',
            'transaction_id' => 'required'
        ]);
        $status = $request->status;
        // dd($user->transactions);

        $pendingTransaction = $user->transactions()->where('txn_no', $request->tx_ref)->first();
        // dd($pendingTransaction);
        // Verify if pending transactin exists
        if($pendingTransaction == null) {
            return redirect(config('app.spa_url').'/dashboard/withdraw?pm=fw&status=failed');
        }

        //if payment is successful
        if ($status ==  'successful') {
            $transactionID = Rave::getTransactionIDFromCallback();
            $data = Rave::verifyTransaction($transactionID);

            // dd($data);

            $dataInfo = null;
            // Verify transaction reference and amount
            if ($data == null) {
                return redirect(config('app.spa_url').'/dashboard/withdraw?pm=fw&status=failed');
            } else {
                $dataInfo = $data['data'];
                if (($pendingTransaction->txn_no != $dataInfo['tx_ref']) && ($pendingTransaction->amt != $dataInfo['amount'])) {
                    return redirect(config('app.spa_url').'/dashboard/withdraw?pm=fw&status=failed');
                } else {
                    $dataInfo = $data['data'];
                };
            }

            $amount = $pendingTransaction->amt;
            $tx_ref = $pendingTransaction->txn_no;

            // Verify Transaction and save card token
            if ($this->verifyPayment($user, $transactionID)) {
                $pendingTransaction->status_id = status_completed_id();
                $pendingTransaction->save();
                return redirect(config('app.spa_url').'/dashboard/withdraw?pm=fw&status=success');
            } else {
                reportError('Flutterwave: Could not verify flutterwave payment of '.$amount);
                return redirect(config('app.spa_url').'/dashboard/withdraw?pm=fw&status=failed');
            }
        }
        elseif ($status ==  'cancelled'){
            $pendingTransaction->status_id = status_cancelled_id();
            $pendingTransaction->save();
            return redirect(config('app.spa_url').'/dashboard/withdraw?pm=fw&status=cancelled');
        }
        else {
            $pendingTransaction->status_id = status_failed_id();
            $pendingTransaction->save();
            return redirect(config('app.spa_url').'/dashboard/withdraw?pm=fw&status=failed');
        }
    }

    public function cardInitialize(Request $request) {
        $request->validate([
            'client' => ['required', 'string']
        ]);
        $cardResponse = null;
        try {
            $cardResponse = Http::withToken(config('flutterwave.secretKey'))
                ->post('https://api.flutterwave.com/v3/charges?type=card', $request->all());
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

    public function validateCharge(Request $request) {
        $request->validate([
            'otp' => ['required', 'string'],
            'flw_ref' => ['required', 'string'],
        ]);
        $response = null;
        try {
            $response = Http::withToken(config('flutterwave.secretKey'))
                ->post('https://api.flutterwave.com/v3/validate-charge', $request->all());
        } catch (\Throwable $th) {
            return apiError('An error was encountered while processing this request.');
        }

        if ($response != null) {
            $data = $response->json();
            if ($data['status'] == 'success') {
                return $data;
            } else {
                return apiError($data['message']);
            }
        } else {
            return apiError('An error was encountered while processing this request.');
        }
    }

    public function verifyPayment(User $user, $txnId) {
        $response = null;
        try {
            $response = Http::withToken(config('flutterwave.secretKey'))
                ->get("https://api.flutterwave.com/v3/transactions/{$txnId}/verify");
        } catch (\Throwable $th) {
            return false;
        }

        if ($response != null) {
            $data = $response->json();
            if ($data['status'] == 'success') {
                $first6digits = $data['data']['card']['first_6digits'];
                $last4digits = $data['data']['card']['last_4digits'];
                $cardExpiry = $data['data']['card']['expiry'];
                $cardToken = $data['data']['card']['token'];
                $customerEmail = $data['data']['customer']['email'];
                $customerName = $data['data']['customer']['name'];
                $customerPhone = $data['data']['customer']['phone_number'];
                $customerId = $data['data']['customer']['id'];
                $cardExists = $user->cards()->where('first_6digits', $first6digits)->where('last_4digits', $last4digits)->exists();
                if (!$cardExists) {
                    $user->cards()->create([
                        'token' => $cardToken,
                        'expiry' => $cardExpiry,
                        'customer_name' => $customerName,
                        'customer_email' => $customerEmail,
                        'customer_phone' => $customerPhone,
                        'customer_id' => $customerId,
                        'first_6digits' => $first6digits,
                        'last_4digits' => $last4digits,
                        'is_default' => $user->has_cards ? false : true
                    ]);
                }
                // return $data;
                return true;
            } else {
                // return apiError($data['message']);
                return false;
            }
        } else {
            // return apiError('An error was encountered while processing this request.');
            return false;
        }
    }

    public function generateTransactionReference() {
        //This generates a payment reference
        $reference = Rave::generateReference();

        return apiSuccess($reference);
    }
}
