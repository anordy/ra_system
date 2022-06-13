<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RealEstateResource extends JsonResource
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
            'description' => $this->description,
            'location' => $this->location,
            'sqm_price_usd' => $this->sqm_price_usd,
            'sqm_price_tzs' => $this->sqm_price_tzs,
            'total_sqm' => $this->total_sqm,
            'property_type' => ucfirst($this->property_type),
            'available' => $this->available == 1 ? "Yes" : "No",
        ];
    }
}
