<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VtuElectricityListResource extends JsonResource
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
            'service_provider' => $this->service_id,
            'short_name' => $this->short_name,
            'label' => $this->label_name,
            'image_url' => asset($this->image)
        ];
    }
}
