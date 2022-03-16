<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VtuAirtimeResource extends JsonResource
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
            'id' => $this->id,
            'network' => $this->billable->name,
            'amount' => $this->amount,
            'phone_number' => $this->customer,
            'reference_no' => $this->reference,
            'status' => $this->status->title
        ];
    }
}
