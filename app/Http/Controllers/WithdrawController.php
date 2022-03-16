<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawRequest;
use App\Notifications\WithdrawRequestAdminNotify;
use App\Notifications\WithdrawStatusNotify;
use Illuminate\Http\Request;
use Notification;
use Symfony\Component\HttpFoundation\Response;

class WithdrawController extends Controller
{
    public function withdraw(Request $request) {
        $request->validate([
            'amount' => 'required|integer|min:100|max:'.auth()->user()->wallet->amt,
            'bank_name' => 'required',
            'account_number' => 'required',
        ]);
        if (auth()->user()->bvn_verified) {
            $prev_bal = auth()->user()->wallet->amt;
            auth()->user()->wallet->amt -= $request->amount;
            auth()->user()->wallet->save();
            $new_bal = auth()->user()->wallet->amt;
            $withdrawRequest = auth()->user()->withdrawRequests()->create([
                'amount' => $request->amount,
                'account_name' => auth()->user()->name,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'status_id' => status_pending_id()
            ]);
            auth()->user()->transactions()->create([
                'name' => Transaction::DEBIT,
                'txn_no' => generateWithdrawNo(),
                'amt' => $request->amount,
                'prev_bal' => $prev_bal,
                'new_bal' => $new_bal,
                'model_type' => Transaction::WITHDRAW,
                'model_id' => $withdrawRequest->id,
                'status_id' => status_completed_id()
            ]);

            // Mail/Notification
            try {
                Notification::send(User::admin()->get(), new WithdrawRequestAdminNotify(auth()->user(), $withdrawRequest->amount_string));
            } catch (\Throwable $th) {
                reportError($th->getMessage());
            }
            return apiSuccess(null, 'Withdrawal successful!');
        } else {
            return apiError('You need to verify your BVN to request for withdrawal!', Response::HTTP_BAD_REQUEST);
        }
    }

    public function query($count, $status = null) {
        $withrawRequests = WithdrawRequest::query();

        if ($status) {
            $status = Status::where('title', $status)->first();
            $withrawRequests = $withrawRequests->where('status_id', $status->id);
        }

        $withrawRequests = $withrawRequests->latest()->with('user', 'status')->paginate($count);

        return apiSuccess($withrawRequests, "Withdrawal queried successfully!");
    }

    public function userPending() {
        $withrawRequests = auth()->user()->withdrawRequests()->where('status_id', status_pending_id())->latest()
            ->with(['user', 'status'])->get();

        return apiSuccess($withrawRequests);
    }

    public function adminPending($count) {
        $withrawRequests = WithdrawRequest::where('status_id', status_pending_id())
            ->orWhere('status_id', status_processing_id())
            ->latest()->with(['status', 'user'])->paginate($count);

        return apiSuccess($withrawRequests, 'Withdrawals retrieved successfully!');
    }

    public function respond(WithdrawRequest $withdrawRequest, $response, Request $request) {
        if (($withdrawRequest->status_id != status_pending_id()) &&
            ($withdrawRequest->status_id != status_processing_id())) {
            return apiSuccess(null, 'You have already responded to this withdraw request');
        }
        if ($response == 'accept') {
            $withdrawRequest->update([
                'status_id' => status_completed_id()
            ]);
            return apiSuccess(null, 'Withdrawal completed successfully!');
        } else if ($response == 'reject') {
            // Revert amount back to balance
            $prev_bal = $withdrawRequest->user->wallet->amt;
            $withdrawRequest->user->wallet->amt += $withdrawRequest->amount;
            $withdrawRequest->user->wallet->save();
            $new_bal = $withdrawRequest->user->wallet->amt;

            // Update withdraw request
            $withdrawRequest->update([
                'reason' => $request->reason,
                'status_id' => status_rejected_id()
            ]);

            $withdrawRequest->user->transactions()->create([
                'name' => Transaction::CREDIT,
                'txn_no' => generateWithdrawNo(),
                'amt' => $withdrawRequest->amount,
                'prev_bal' => $prev_bal,
                'new_bal' => $new_bal,
                'model_type' => Transaction::WITHDRAW,
                'model_id' => $withdrawRequest->id,
                'status_id' => status_completed_id()
            ]);

            try {
                $withdrawRequest->user->notify(new WithdrawStatusNotify(WithdrawStatusNotify::REJECT, $withdrawRequest->amount_string, $request->reason));
            } catch (\Throwable $th) {
                reportError($th->getMessage());
            }
            return apiSuccess(null, 'Withdraw request rejected successfully!');
        } else if ($response == 'process') {
            // Started initating transaction
            $withdrawRequest->update([
                'status_id' => status_processing_id()
            ]);

            try {
                $withdrawRequest->user->notify(new WithdrawStatusNotify(WithdrawStatusNotify::PROCESS, $withdrawRequest->amount_string));
            } catch (\Throwable $th) {
                reportError($th->getMessage());
            }
            return apiSuccess(null, 'Withdraw request processing!');
        } else {
            return apiError('Invalid response, please try again!', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function cancel(WithdrawRequest $withdrawRequest) {
        // Ability for use to cancel withdraw request that is not being processed but pending
        if (($withdrawRequest->status_id == status_pending_id())) {
            $prev_bal = auth()->user()->wallet->amt;
            auth()->user()->wallet->amt += $withdrawRequest->amount;
            auth()->user()->wallet->save();
            $new_bal = auth()->user()->wallet->amt;

            $withdrawRequest->update([
                'status_id' => status_cancelled_id()
            ]);

            auth()->user()->transactions()->create([
                'name' => Transaction::CREDIT,
                'txn_no' => generateWithdrawNo(),
                'amt' => $withdrawRequest->amount,
                'prev_bal' => $prev_bal,
                'new_bal' => $new_bal,
                'model_type' => Transaction::WITHDRAW,
                'model_id' => $withdrawRequest->id,
                'status_id' => status_completed_id()
            ]);
            return apiSuccess(null, 'Withdraw request cancelled successfully!');
        }
    }
}
