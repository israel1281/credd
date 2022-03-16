<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'account_number' => $this->acc_no,
            'balance' => $this->amount,
            'balance_str' => $this->amount_string,
            'loan_balance' => $this->loan_amount,
            'loan_balance_str' => $this->loan_amount_string,
            'account_type' => $this->type,
            'transfer_max' => $this->max_amt_transfer ?: -1,
        ];
    }
}
