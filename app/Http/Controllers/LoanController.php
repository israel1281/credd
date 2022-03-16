<?php

namespace App\Http\Controllers;

use App\Mail\LoanRequestAdminMail;
use App\Models\LoanRequest;
use App\Models\Status;
use App\Models\Transaction;
use App\Notifications\LoanNotify;
use Illuminate\Http\Request;
use Mail;
use Symfony\Component\HttpFoundation\Response;

class LoanController extends Controller
{
    public function apply(Request $request) {
        $request->validate([
            'amount' => 'required|integer|size:5000',
        ]);
        if (!auth()->user()->bvn_verified) {
            return apiError('You need to verify your BVN to apply for a loan!', Response::HTTP_BAD_REQUEST);
        }
        if (auth()->user()->is_blacklist_ng) {
            return apiError("Sorry, but you are not eligible for a loan. Thank you!");
        }
        if (auth()->user()->has_kyc_pending_request) {
            return apiSuccess(null, 'Your loan application is being processed, please hold on.');
        }
        if (auth()->user()->has_kyc_pending) {
            return apiSuccess(null, "Your KYC is being processed and you will be notified when it's approved!");
        }
        if (auth()->user()->has_pending_loan_request) {
            return apiSuccess("Your loan application is being processed. Please hold on!");
        }
        if (auth()->user()->has_pending_loan) {
            return apiSuccess("Sorry, you currently have a pending loan! Please clear loan to apply for a new one.");
        }

        $interest = ($request->amount * 0.030) * 14;
        $loanRequest = auth()->user()->loanRequests()->create([
            'amount' => $request->amount,
            'interest' => $interest,
            'status_id' => status_pending_id()
        ]);
        try {
            Mail::to(config('mail.from.address'))->send(new LoanRequestAdminMail($loanRequest));
        } catch (\Throwable $th) {
            reportError($th->getMessage());
        }
        return apiSuccess(null, 'Loan application successful, awaiting approval!');
    }

    public function query($count, $status = null) {
        $loans = LoanRequest::query();
        if ($status) {
            $status = Status::where('title', $status)->first();
            $loans = $loans->where('status_id', $status->id);
        }

        $loans = $loans->latest()->with('user', 'status')->paginate($count);

        return apiSuccess($loans, 'Loans queried successfully!');
    }

    public function adminPending($count) {
        $loanRequests = LoanRequest::where('status_id', status_pending_id())
            ->latest()->with('status', 'user')->paginate($count);

        return apiSuccess($loanRequests, 'Loans retrieved successfully!');
    }

    public function respond(LoanRequest $loanRequest, $response) {
        if ($loanRequest->status_id != status_pending_id()) {
            return apiSuccess(null, 'You have already responded to this loan');
        }
        if ($response == 'accept') {
            $loanRequest->update([
                'start_at' => now(),
                'expire_at' => now()->addDays(14),
                'status_id' => status_active_id()
            ]);
            $loanWithInterest = $loanRequest->amount + $loanRequest->interest;
            $loanRequest->user->wallet->loan_amt += $loanWithInterest;

            $prev_bal = auth()->user()->wallet->amt;
            $loanRequest->user->wallet->amt += $loanRequest->amount;
            $loanRequest->user->wallet->save();
            $new_bal = auth()->user()->wallet->amt;

            $loanRequest->user->transactions()->create([
                'name' => Transaction::LOAN_CREDIT,
                'txn_no' => generateLoanNo(),
                'amt' => $loanRequest->amount,
                'prev_bal' => $prev_bal,
                'new_bal' => $new_bal,
                'model_type' => Transaction::LOAN_REQUEST,
                'model_id' => $loanRequest->id,
                'status_id' => status_completed_id()
            ]);
            // untested
            // $loanRequest->user->loanRequests()->pending()->delete();
            try {
                $loanRequest->user->notify(new LoanNotify(LoanNotify::ACCEPT, $loanRequest->amount_string));
            } catch (\Throwable $th) {
                reportError($th->getMessage());
            }
            return apiSuccess(null, 'Loan request approved successfully!');
        } else if ($response == 'reject') {
            $loanRequest->update([
                'status_id' => status_rejected_id()
            ]);
            try {
                $loanRequest->user->notify(new LoanNotify(LoanNotify::REJECT, $loanRequest->amount_string));
            } catch (\Throwable $th) {
                reportError($th->getMessage());
            }
            return apiSuccess(null, 'Loan request rejected successfully!');
        } else {
            return apiError('Invalid response, please try again!', Response::HTTP_BAD_REQUEST);
        }
    }
}
