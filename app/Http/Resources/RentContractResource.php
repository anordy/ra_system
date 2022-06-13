<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RentContractResource extends JsonResource
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
            'customer_type' => $this->customer_type,
            'fullName' => $this->fullName($this),
            'fname' => $this->customer_fname,
            'lname' => $this->customer_lname,
            'company_name' => $this->company_name,
            'company_licence_number' => $this->company_licence_no,
            'email' => $this->email,
            'phone' => $this->phone,
            'total_sqm' => $this->total_sqm,
            'amount_usd' => $this->amount_usd,
            'description' => $this->description,
            'address' => $this->address,
            'job_title' => $this->job_title,
            'contract_duration' => $this->duration,
            'contract_attachments' => $this->attachment,
            'contract_start_date' => $this->start_date,
            'property' => RealEstateResource::make($this->realEstate),
        ];
    }


    private function fullName($data){
        if($data->customer_type == 'Individual'){
            return $data->customer_fname .' '. $data->customer_lname;
        }elseif($data->customer_type = 'Company'){
            return $data->company_name;
        }else{
            return '';
        }
    }
}
