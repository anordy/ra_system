<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StickerResource extends JsonResource
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
            'request_details' => $this->request_details,
            'vehicle' => (object)[
                'reg_no' => $this->vh_reg_no,
                'owner_name' => $this->vh_owner_name,
                'owner_phone' => $this->vh_owner_phone,
                'owner_address' => $this->vh_owner_addr,
                'type' => $this->vh_type,
                'weight' => $this->vh_weight,
                'insurance_info' => $this->vh_insurance,
                'permanent_address' => $this->vh_permanent_addr,
            ],
            'driver' => (object)[
                'name' => $this->dr_name,
                'phone' => $this->dr_phone,
                'age' => $this->dr_age,
                'licence_no' => $this->dr_licence_no,
            ],
            'duration' => $this->app_fee,
            'user_type' => $this->userType(),
            'driver_assistants' => StickerAssistantResource::collection($this->driverAssistants)
        ];
    }
}
