<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Notifications\LoanClearedNotify;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class AutoCreditInterest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autocredit:interest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto credits loan interest';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $message = "No interest added!";
        $wallets = Wallet::where('loan_amt', '>', 0)->get();
        foreach($wallets as $wallet) {
            $loanRequests = $wallet->user->loanRequests()->active()->get();
            foreach($loanRequests as $loanRequest) {
                $currentDate = now();
                $expiryDate = $loanRequest->expire_at;
                $user = $loanRequest->user;

                if ($currentDate->greaterThan($expiryDate)) {
                    // Try to autodebit
                    if ($user->autoDebitCharges()->failedOnce()) {
                        $amountToCharge = floor($wallet->loan_amt / 2);
                        // I stopped here. Continue to try to debit loan
                        $tryDebitLoan = $this->tryDebitAmount($amountToCharge, $loanRequest);
                        if ($tryDebitLoan) {
                            $this->billLoan($wallet, $amountToCharge, $tryDebitLoan);
                        }
                    } else if ($user->autoDebitCharges()->failedTwice() && ($wallet->loan_amt > 1000)) {
                        $amountToCharge = 1000;
                        $tryDebitLoan = true;
                        while($tryDebitLoan != false && $wallet->loan_amt != 0) {
                            if ($amountToCharge < $wallet->loan_amt) {
                                $tryDebitLoan = $this->tryDebitAmount($amountToCharge, $loanRequest);
                                if ($tryDebitLoan) {
                                    $this->billLoan($wallet, $amountToCharge, $tryDebitLoan);
                                }
                            } else {
                                $amountToCharge = $wallet->loan_amt;
                                $tryDebitLoan = $this->tryDebitAmount($amountToCharge, $loanRequest);
                                if ($tryDebitLoan) {
                                    $this->billLoan($wallet, $amountToCharge, $tryDebitLoan);
                                }
                            }
                        }
                    } else {
                        $tryDebitLoan = $this->tryDebitAmount($wallet->loan_amt, $loanRequest);
                        if ($tryDebitLoan) {
                            $this->billLoan($wallet, $wallet->loan_amt, $tryDebitLoan);
                        }

                    }

                    if ($wallet->loan_amt) {
                        $wallet->loan_amt += 0.035 * $loanRequest->amount;
                        $wallet->save();
                        $message = "Interest added successfully!";
                    } else {
                        $message = "Loan cleared successfully!";
                    }
                }
            }
        }
        $this->info($message);
    }

    public function tryDebitAmount($amount, $loanRequest) {
        $card = $loanRequest->user->cards()->default()->first();
        if ($card) {
            $response = null;
            $tx_ref = generateFlutterwaveTransactionNo();
            try {
                $body = [
                    'token' => $card->token,
                    'email' => $card->customer_email,
                    'currency' => 'NGN',
                    'country' => 'NG',
                    'amount' => $amount,
                    'tx_ref' => generateFlutterwaveTransactionNo(),
                    'first_name' => $loanRequest->user->first_name,
                    'last_name' => $loanRequest->user->last_name,
                ];
                $response = Http::withToken(config('flutterwave.secretKey'))
                    ->post('https://api.flutterwave.com/v3/tokenized-charges', $body);

                if ($response != null) {
                    $data = $response->json();
                    if ($data['status'] == 'success') {
                        $loanRequest->user->autodebitCharges()->create([
                            'loan_request_id' => $loanRequest->id,
                            'card_id' => $card->id,
                            'amount' => $amount,
                            'status_id' => status_completed_id()
                        ]);
                        return $tx_ref;
                    } else {
                        $loanRequest->user->autodebitCharges()->create([
                            'loan_request_id' => $loanRequest->id,
                            'card_id' => $card->id,
                            'amount' => $amount,
                            'status_id' => status_failed_id()
                        ]);
                        return false;
                    }
                } else {
                    return false;
                }

                return false;


            } catch (\Throwable $th) {
                reportError('AutoDebit Error: '.$th->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }

    public function billLoan(Wallet $wallet, int $amount, $txRef) {
        $currentLoanBalance = $wallet->loan_amt;
        $loanBalance = $currentLoanBalance - $amount;
        $user = $wallet->user;
        if ($loanBalance > 0) {
            // There is pending loan
            $wallet->loan_amt = $loanBalance;
            $wallet->user->transactions()->create([
                'name' => Transaction::DEBIT,
                'txn_no' => $txRef,
                'amt' => $amount,
                'prev_bal' => $wallet->amt,
                'new_bal' => $wallet->amt,
                'model_type' => Transaction::LOAN_DEBIT,
                'model_id' => 0,
                'status_id' => status_completed_id()
            ]);
        }
        else {
            // Resolved Loan
            $wallet->loan_amt = 0;
            $user->transactions()->create([
                'name' => Transaction::DEBIT,
                'txn_no' => $txRef,
                'amt' => $currentLoanBalance,
                'prev_bal' => $wallet->amt,
                'new_bal' => $wallet->amt,
                'model_type' => Transaction::LOAN_DEBIT,
                'model_id' => 0,
                'status_id' => status_completed_id()
            ]);

            $user->loanRequests()->active()->update([
                'status_id' => status_completed_id()
            ]);
            // add in loan part
            $user->autodebitCharges()->where('status_id', status_failed_id())->update([
                'status_id' => status_completed_id()
            ]);

            $user->notify(new LoanClearedNotify(config('app.currency').$currentLoanBalance));
        }
        $wallet->save();
    }
}
