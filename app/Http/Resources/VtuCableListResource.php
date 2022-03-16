<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VtuCableListResource extends JsonResource
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
            'name' => $this->name,
            'amount' => $this->amount,
            'amount_string' => config('app.currency').$this->amount,
            'service_provider' => $this->service_id,
            'service_charge' => $this->fee,
            'service_charge_string' => config('app.currency').$this->fee,
            'label' => $this->label_name,
            'image_url' => asset($this->image)
        ];
    }
}
