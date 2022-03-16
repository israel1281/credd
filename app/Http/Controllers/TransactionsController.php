<?php

namespace App\Http\Controllers;

use App\Models\Status;

class TransactionsController extends Controller
{
    public function userQuery($count, $status = null) {
        $transactions = auth()->user()->transactions();
        if ($status) {
            $status = Status::where('title', $status)->first();
            $transactions = $transactions->where('status_id', $status->id);
        }
        $transactions = $transactions->latest()->with('status')->paginate($count);

        return apiSuccess($transactions, "Transactions queried successfully!");
    }
}
