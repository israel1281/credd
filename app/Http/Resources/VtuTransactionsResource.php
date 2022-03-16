<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VtuTransactionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "reference_no" => $this->reference,
            "customer_no" => $this->customer,
            "amount" => $this->amount,
            "amount_string" => $this->amount_string,
            "bill_payment_type" => $this->bp_type,
            "service" => $this->billable->short_name,
            "service_full" => $this->bp_name,
            "status" => $this->status->title,
            "status_colour" => $this->status->colour,
            "date" => $this->created_at
        ];
    }
}
