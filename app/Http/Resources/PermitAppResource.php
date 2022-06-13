<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermitAppResource extends JsonResource
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
            'full_name' => $this->fname . ' '. $this->lname,
            'applicant_type' => $this->applicant_type,
            'fname' => $this->fname,
            'lname' => $this->lname,
            'nida' => $this->nida,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'amount' => $this->amount,
            'institution_name' => $this->inst_name,
            'institution_address' => $this->inst_address,
            'institution_reg_date' => $this->inst_reg_date,
            'approved_by_name' => $this->inst_appd_name,
            'approved_by_title' => $this->inst_appd_title,
            'app_category' => $this->app_category == 'one_day' ? "One Day" : "Six Month",
        ];
    }
}
