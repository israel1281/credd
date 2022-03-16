<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\LoanClearedNotify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UsersController extends Controller
{
    public function query(Request $request, $count, $status = null) {
        $key = $request->search;
        $users = User::query();
        if ($key) {
            $users = $users->where('first_name', 'like', "%{$key}%")
                            ->orWhere('last_name', 'like', "%{$key}%");
        }
        if ($status) {
            $status = Status::where('title', $status)->first();
            $users = $users->where('status_id', $status->id);
        }
        $users = $users->with('status', 'wallet', 'role')->paginate($count);

        return apiSuccess($users, "Users queried successfully!");
    }

    public function clearLoan(User $user) {
        if ($user->wallet->loan_amt) {
            $currentLoanBalance = $user->wallet->loan_amt;
            $user->wallet->loan_amt = 0;
            $user->wallet->save();
            $user->transactions()->create([
                'name' => Transaction::DEBIT,
                'txn_no' => generateLoanNo(),
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

            return apiSuccess(null, "User's loan balance cleared successfully!");
        }
        else
            return apiSuccess(null, "Sorry, this user has no pending loan.");
    }

    public function updateBalance(User $user, Request $request) {
        if (! Gate::allows('update-balance')) {
            return apiError("Unauthorized access!", 403);
        };
        $request->validate([
            'amount' => 'required|numeric|gte:0'
        ]);
        $user->wallet->amt = $request->amount;
        $user->wallet->save();
        return apiSuccess(null, $user->name."'s balance updated successfully!");
    }

    public function updateLoanBalance(User $user, Request $request) {
        if (! Gate::allows('update-balance')) {
            return apiError("Unauthorized access!", 403);
        };
        $request->validate([
            'amount' => 'required|numeric|gte:0'
        ]);
        $user->wallet->loan_amt = $request->amount;
        $user->wallet->save();
        return apiSuccess(null, $user->name."'s loan balance updated successfully!");
    }

    public function markNotificationsRead() {
        auth()->user()->unreadNotifications->markAsRead();
        return apiSuccess([], "");
    }

    public function clearNotifications() {
        auth()->user()->notifications()->delete();
        return apiSuccess([], "");
    }

    public function changeRole(User $user) {
        if ($user->is_admin) {
            $user->role_id = role_user();
        } else {
            $user->role_id = role_admin();
        }
        $user->save();

        return apiSuccess(null, 'User role changed successfully!');
    }

    public function block(User $user, Request $request) {
        $request->validate([
            'reason' => 'required'
        ]);

        $user->status_id = status_blocked_id();
        $user->reason = $request->reason;
        $user->save();

        return apiSuccess(null, 'User account blocked successfully!');
    }

    public function active(User $user, Request $request) {
        $user->status_id = status_active_id();
        $user->reason = null;
        $user->save();

        return apiSuccess(null, 'User account activated successfully!');
    }
}
