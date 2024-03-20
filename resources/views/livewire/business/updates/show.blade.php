<div class="card-body pb-0">
    <nav class="nav nav-tabs mt-0 border-top-0">
        <a href="#tab1" class="nav-item nav-link font-weight-bold active">Business Changes Details</a>
        <a href="#tab2" class="nav-item nav-link font-weight-bold">Approval History</a>
    </nav>
    <div class="tab-content px-2 card pt-3 pb-2">
        <div id="tab1" class="tab-pane fade active show p-4">

            @if ($business_update->type == 'business_information')
                <div class="col-md-12">
                    <table class="table table-bordered table-striped table-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="text-left font-weight-bold text-uppercase">Business Information</label>
                            <label class="text-right text-uppercase">Changed by
                                <strong>{{ $business_update->taxpayer->full_name }}</strong> <br> on
                                <strong>{{ $business_update->created_at->toFormattedDateString() }}</strong></label>
                        </div>
                        <thead>
                            <th style="width: 20%">Property</th>
                            <th style="width: 30%">Old Values</th>
                            <th style="width: 30%">New Values</th>
                            <th style="width: 20%">Status</th>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Business Name</th>
                                <td>{{ $old_values->business_information->name }}</td>
                                <td>{{ $new_values->business_information->name }}</td>
                                @if ($old_values->business_information->name == $new_values->business_information->name)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Reg No</th>
                                <td>{{ $old_values->business_information->reg_no ?? 'N/A' }}</td>
                                <td>{{ $new_values->business_information->reg_no ?? 'N/A' }}</td>
                                @if ($old_values->business_information->reg_no == $new_values->business_information->reg_no)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Previous Zno</th>
                                <td>{{ $old_values->business_information->previous_zno ?? 'N/A' }}</td>
                                <td>{{ $new_values->business_information->previous_zno ?? 'N/A' }}</td>
                                @if ($old_values->business_information->previous_zno == $new_values->business_information->previous_zno)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Taxpayer Name</th>
                                <td>{{ $old_values->business_information->taxpayer_name }}</td>
                                <td>{{ $new_values->business_information->taxpayer_name }}</td>
                                @if ($old_values->business_information->taxpayer_name == $new_values->business_information->taxpayer_name)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $old_values->business_information->email }}</td>
                                <td>{{ $new_values->business_information->email }}</td>
                                @if ($old_values->business_information->email == $new_values->business_information->email)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Mobile</th>
                                <td>{{ $old_values->business_information->mobile }}</td>
                                <td>{{ $new_values->business_information->mobile }}</td>
                                @if ($old_values->business_information->mobile == $new_values->business_information->mobile)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Alternative Mobile</th>
                                <td>{{ $old_values->business_information->alt_mobile ?? 'N/A' }}</td>
                                <td>{{ $new_values->business_information->alt_mobile ?? 'N/A' }}</td>
                                @if ($old_values->business_information->alt_mobile == $new_values->business_information->alt_mobile)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Business Activities</th>
                                <td>{{ $old_values->business_information->business_activities_type_id->name }}</td>
                                <td>{{ $this->getNameById('business_activities_type_id', $new_values->business_information->business_activities_type_id) }}
                                </td>
                                @if ($old_values->business_information->business_activities_type_id->name ==
                                    $this->getNameById('business_activities_type_id', $new_values->business_information->business_activities_type_id))
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Currency</th>
                                <td>{{ $old_values->business_information->currency_id->name }}</td>
                                <td>{{ $this->getNameById('currency_id', $new_values->business_information->currency_id) }}
                                </td>
                                @if ($old_values->business_information->currency_id->name ==
                                    $this->getNameById('currency_id', $new_values->business_information->currency_id))
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Owner Designation</th>
                                <td>{{ $old_values->business_information->owner_designation }}</td>
                                <td>{{ $new_values->business_information->owner_designation }}</td>
                                @if ($old_values->business_information->owner_designation == $new_values->business_information->owner_designation)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Place of Business</th>
                                <td>{{ $old_values->business_information->place_of_business }}</td>
                                <td>{{ $new_values->business_information->place_of_business }}</td>
                                @if ($old_values->business_information->place_of_business == $new_values->business_information->place_of_business)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <table class="table table-bordered table-striped table-sm">
                        <label class="font-weight-bold text-uppercase">Business Location</label>
                        <thead>
                            <th style="width: 20%">Property</th>
                            <th style="width: 30%">Old Values</th>
                            <th style="width: 30%">New Values</th>
                            <th style="width: 20%">Status</th>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Nature of Possession</th>
                                <td>{{ $old_values->business_location->nature_of_possession }}</td>
                                <td>{{ $new_values->business_location->nature_of_possession }}</td>
                                @if ($old_values->business_location->nature_of_possession == $new_values->business_location->nature_of_possession)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Owner's Name</th>
                                <td>{{ $old_values->business_location->owner_name ?? 'N/A' }}</td>
                                <td>{{ $new_values->business_location->owner_name ?? 'N/A' }}</td>
                                @if ($old_values->business_location->owner_name == $new_values->business_location->owner_name)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Owners Phone No</th>
                                <td>{{ $old_values->business_location->owner_phone_no ?? 'N/A' }}</td>
                                <td>{{ $new_values->business_location->owner_phone_no ?? 'N/A' }}</td>
                                @if ($old_values->business_location->owner_phone_no == $new_values->business_location->owner_phone_no)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>House No</th>
                                <td>{{ $old_values->business_location->house_no }}</td>
                                <td>{{ $new_values->business_location->house_no }}</td>
                                @if ($old_values->business_location->house_no == $new_values->business_location->house_no)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Meter No</th>
                                <td>{{ $old_values->business_location->meter_no }}</td>
                                <td>{{ $new_values->business_location->meter_no }}</td>
                                @if ($old_values->business_location->meter_no == $new_values->business_location->meter_no)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Latitude</th>
                                <td>{{ $old_values->business_location->latitude }}</td>
                                <td>{{ $new_values->business_location->latitude }}</td>
                                @if ($old_values->business_location->latitude == $new_values->business_location->latitude)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Longitude</th>
                                <td>{{ $old_values->business_location->longitude }}</td>
                                <td>{{ $new_values->business_location->longitude }}</td>
                                @if ($old_values->business_location->longitude == $new_values->business_location->longitude)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Region</th>
                                <td>{{ $old_values->business_location->region_id->name }}</td>
                                <td>{{ $this->getNameById('region_id', $new_values->business_location->region_id) }}
                                </td>
                                @if ($old_values->business_location->region_id->name ==
                                    $this->getNameById('region_id', $new_values->business_location->region_id))
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>District</th>
                                <td>{{ $old_values->business_location->district_id->name }}</td>
                                <td>{{ $this->getNameById('district_id', $new_values->business_location->district_id) }}
                                </td>
                                @if ($old_values->business_location->district_id->name ==
                                    $this->getNameById('district_id', $new_values->business_location->district_id))
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Ward</th>
                                <td>{{ $old_values->business_location->ward_id->name }}</td>
                                <td>{{ $this->getNameById('ward_id', $new_values->business_location->ward_id) }}</td>
                                @if ($old_values->business_location->ward_id->name ==
                                    $this->getNameById('ward_id', $new_values->business_location->ward_id))
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            @if ($old_values->business_location->street_id || $new_values->business_location->street_id)
                            <tr>
                                <th>Street</th>
                                <td>{{ $old_values->business_location->street_id->name }}</td>
                                <td>{{ $this->getNameById('street_id', $new_values->business_location->street_id) }}</td>
                                @if ($old_values->business_location->street_id->name ==
                                    $this->getNameById('street_id', $new_values->business_location->street_id))
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            @endif
                            <tr>
                                <th>Fax No</th>
                                <td>{{ $old_values->business_location->fax ?? 'N/A' }}</td>
                                <td>{{ $new_values->business_location->fax ?? 'N/A' }}</td>
                                @if ($old_values->business_location->fax ==
                                    $new_values->business_location->fax)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>PO Box</th>
                                <td>{{ $old_values->business_location->po_box ?? 'N/A' }}</td>
                                <td>{{ $new_values->business_location->po_box ?? 'N/A' }}</td>
                                @if ($old_values->business_location->po_box == $new_values->business_location->po_box)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            @elseif($business_update->type == 'bank_information')
            <div class="col-md-12">
                <table class="table table-bordered table-striped table-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="text-left font-weight-bold text-uppercase">{{ $business_update->business->name }} Bank Information</label>
                        <label class="text-right text-uppercase">Changed by
                            <strong>{{ $business_update->taxpayer->full_name }}</strong> <br> on
                            <strong>{{ $business_update->created_at->toFormattedDateString() }}</strong></label>
                    </div>
                    <thead>
                        <th style="width: 50%">Old Bank Information</th>
                        <th style="width: 50%">Updated Bank Information</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                @if (count($old_values ?? []) > 0)
                                    @foreach ($old_values as $old_bank)
                                    <div class="mb-4">
                                        <p>Bank Name: {{ $this->getNameById('bank_id',$old_bank->bank_id) ?? '' }}</p>
                                        <p>Account Number: {{ $old_bank->acc_no ?? '' }}</p>
                                        <p>Account Type: {{ $this->getNameById('account_type_id', $old_bank->account_type_id) ?? '' }}</p>
                                        <p>Currency: {{ $this->getNameById('currency_id', $old_bank->currency_id) ?? '' }}</p>
                                        <p>Branch Name: {{ $old_bank->branch ?? '' }}</p>
                                    </div>
                                    <hr>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if (count($new_values ?? []) > 0)
                                    @foreach ($new_values as $new_bank)
                                    <div class="mb-4">
                                        <p>Bank Name: {{ $this->getNameById('bank_id',$new_bank->bank_id) ?? '' }}</p>
                                        <p>Account Number: {{ $new_bank->acc_no ?? '' }}</p>
                                        <p>Account Type: {{ $this->getNameById('account_type_id', $new_bank->account_type_id) ?? '' }}</p>
                                        <p>Currency: {{ $this->getNameById('currency_id', $new_bank->currency_id) ?? '' }}</p>
                                        <p>Branch Name: {{ $new_bank->branch ?? '' }}</p>
                                    </div>
                                    <hr>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @elseif($business_update->type == 'business_attachments')
            <div class="col-md-12">
                <table class="table table-bordered table-striped table-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="text-left font-weight-bold text-uppercase">{{ $business_update->business->name }} Business Attachments</label>
                        <label class="text-right text-uppercase">Changed by
                            <strong>{{ $business_update->taxpayer->full_name }}</strong> <br> on
                            <strong>{{ $business_update->created_at->toFormattedDateString() }}</strong></label>
                    </div>
                    <thead>
                        <th style="width: 50%">Old Attachments</th>
                        <th style="width: 50%">Updated Attachments</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                @if (count($old_values ?? []) > 0)
                                <div class="row">
                                    @foreach ($old_values as $file)
                                    <div class="col-md-12">
                                        <a class="file-item" target="_blank"
                                            href="{{ route('business.file', encrypt($file->id)) }}">
                                            <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
                                            <div class="ml-1 font-weight-bold">
                                                {{ $this->getNameById('file_type_id',$file->business_file_type_id) ?? 'N/A' }}
                                            </div>
                                        </a>
                                    </div>
                                    <hr>
                                    @endforeach
                                </div>
                                @endif
                            </td>
                            <td>
                                <div class="row">
                                    @foreach ($new_values->supporting_attachments as $file)
                                        <div class="col-md-12">
                                            <a class="file-item" target="_blank"
                                                href="{{ route('business.file-location', encrypt($file->location)) }}">
                                                <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
                                                <div class="ml-1 font-weight-bold">
                                                    {{ $file->name ?? 'N/A' }}
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                    @foreach ($new_values->partners_tins as $file)
                                        <div class="col-md-12">
                                            <a class="file-item" target="_blank"
                                                href="{{ route('business.file-location', encrypt($file->tin_location)) }}">
                                                <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
                                                <div class="ml-1 font-weight-bold">
                                                TIN for {{ $file->reference_no ?? 'N/A' }}
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @elseif($business_update->type == 'responsible_person')
                <div class="col-md-12">
                    <table class="table table-striped table-sm">
                        <label class="font-weight-bold text-uppercase">{{ $business_update->business->name }} Consultant / Preparer</label>
                        <thead>
                            <th style="width: 30%">Property</th>
                            <th style="width: 25%">Old Values</th>
                            <th style="width: 25%">New Values</th>
                            <th style="width: 20%">Status</th>
                        </thead>
                        <tbody>

                            <tr>
                                <th>Is Own Consultant</th>
                                <td>{{ $old_values->is_own_consultant == 1 ? 'Yes' : 'No' }}</td>
                                <td>{{ $new_values->is_own_consultant == 1 ? 'Yes' : 'No' }}</td>
                                @if ($old_values->is_own_consultant == $new_values->is_own_consultant)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Tax Consultant Reference No.</th>
                                <td>{{ $old_values->tax_consultant_reference_no ?? 'N/A' }}</td>
                                <td>{{ $new_values->tax_consultant_reference_no ?? 'N/A' }}</td>
                                @if ($old_values->tax_consultant_reference_no == $new_values->tax_consultant_reference_no)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Has Assistants</th>
                                <td>{{ $old_values->hasAssistants ? 'Yes' : 'No' }}</td>
                                <td>{{ $new_values->hasAssistants ? 'Yes' : 'No' }}</td>
                                @if ($old_values->hasAssistants == $new_values->hasAssistants)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>First Assistant</th>
                                @if($old_values->hasAssistants)
                                    <td>
                                        {{ $this->taxpayer($old_values->assistants[0]->taxpayer_id)->fullName }}
                                    </td>
                                @else
                                    <td>N/A</td>
                                @endif
                                @if($new_values->hasAssistants)
                                    <td>
                                        {{ $this->taxpayer($new_values->assistants[0]->taxpayer_id)->fullName }}
                                    </td>
                                @else
                                    <td>N/A</td>
                                @endif
                                <td class="bg-secondary"></td>
                            </tr>
                            <tr>
                                <th>Second Assistant</th>
                                @if($old_values->hasAssistants && count($old_values->assistants) > 1)
                                    <td>
                                        {{ $this->taxpayer($old_values->assistants[1]->taxpayer_id)->fullName }}
                                    </td>
                                @else
                                    <td>N/A</td>
                                @endif
                                @if($new_values->hasAssistants && count($new_values->assistants) > 1)
                                    <td>
                                        {{ $this->taxpayer($new_values->assistants[1]->taxpayer_id)->fullName }}
                                    </td>
                                @else
                                    <td>N/A</td>
                                @endif
                                <td class="bg-secondary"></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    @if ($agent_contract && $new_values->is_own_consultant == 0)
                    <div class="col-md-3">
                        <a class="file-item" target="_blank"
                           href="{{ route('business.contract.file', encrypt($business_update->id)) }}">
                            <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
                            <div class="ml-1 font-weight-bold">
                                View Agent Contract
                            </div>
                        </a>
                    </div>
                    @endif
              
                    <br>
                </div>
            @elseif ($business_update->type == 'hotel_information')
            <div class="col-md-12">
                <table class="table table-striped table-sm">
                    <label class="font-weight-bold text-uppercase">{{ $business_update->business->name }} Hotel Information</label>
                    <thead>
                        <th style="width: 30%">Property</th>
                        <th style="width: 25%">Old Values</th>
                        <th style="width: 25%">New Values</th>
                        <th style="width: 20%">Status</th>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Is Taxpayer the owner of the hotel ?</th>
                            <td>{{ $old_values->business_reg_no ? 'No' : 'Yes' }}</td>
                            <td>{{ $new_values->business_reg_no ? 'No' : 'Yes' }}</td>
                            @if ($old_values->business_reg_no == $new_values->business_reg_no)
                                <td class="table-primary">Unchanged</td>
                            @else
                                <td class="table-success">Changed</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Business Reg No</th>
                            <td>{{ $old_values->business_reg_no ?? 'N/A' }}</td>
                            <td>{{ $new_values->business_reg_no ?? 'N/A' }}</td>
                            @if ($old_values->business_reg_no == $new_values->business_reg_no)
                                <td class="table-primary">Unchanged</td>
                            @else
                                <td class="table-success">Changed</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Old Business Reg No</th>
                            <td>{{ $old_values->old_business_reg_no ?? 'N/A' }}</td>
                            <td>{{ $new_values->old_business_reg_no ?? 'N/A' }}</td>
                            @if ($old_values->old_business_reg_no == $new_values->old_business_reg_no)
                                <td class="table-primary">Unchanged</td>
                            @else
                                <td class="table-success">Changed</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Company Name</th>
                            <td>{{ $old_values->company_name ?? 'N/A' }}</td>
                            <td>{{ $new_values->company_name ?? 'N/A' }}</td>
                            @if ($old_values->company_name == $new_values->company_name)
                                <td class="table-primary">Unchanged</td>
                            @else
                                <td class="table-success">Changed</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Management Company</th>
                            <td>{{ $old_values->management_company ?? 'N/A' }}</td>
                            <td>{{ $new_values->management_company ?? 'N/A' }}</td>
                            @if ($old_values->management_company == $new_values->management_company)
                                <td class="table-primary">Unchanged</td>
                            @else
                                <td class="table-success">Changed</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Hotel Location</th>
                            <td>{{ $old_values->hotel_location }}</td>
                            <td>{{ $new_values->hotel_location }}</td>
                            @if ($old_values->hotel_location == $new_values->hotel_location)
                                <td class="table-primary">Unchanged</td>
                            @else
                                <td class="table-success">Changed</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Number of Rooms</th>
                            <td>{{ $old_values->number_of_rooms }}</td>
                            <td>{{ $new_values->number_of_rooms }}</td>
                            @if ($old_values->number_of_rooms == $new_values->number_of_rooms)
                                <td class="table-primary">Unchanged</td>
                            @else
                                <td class="table-success">Changed</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Number of Single Rooms</th>
                            <td>{{ $old_values->number_of_single_rooms }}</td>
                            <td>{{ $new_values->number_of_single_rooms }}</td>
                            @if ($old_values->number_of_single_rooms == $new_values->number_of_single_rooms)
                                <td class="table-primary">Unchanged</td>
                            @else
                                <td class="table-success">Changed</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Number of Double Rooms</th>
                            <td>{{ $old_values->number_of_double_rooms }}</td>
                            <td>{{ $new_values->number_of_double_rooms }}</td>
                            @if ($old_values->number_of_double_rooms == $new_values->number_of_double_rooms)
                                <td class="table-primary">Unchanged</td>
                            @else
                                <td class="table-success">Changed</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Number of Other Rooms</th>
                            <td>{{ $old_values->number_of_other_rooms }}</td>
                            <td>{{ $new_values->number_of_other_rooms }}</td>
                            @if ($old_values->number_of_other_rooms == $new_values->number_of_other_rooms)
                                <td class="table-primary">Unchanged</td>
                            @else
                                <td class="table-success">Changed</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Hotel Capacity</th>
                            <td>{{ $old_values->hotel_capacity }}</td>
                            <td>{{ $new_values->hotel_capacity }}</td>
                            @if ($old_values->hotel_capacity == $new_values->hotel_capacity)
                                <td class="table-primary">Unchanged</td>
                            @else
                                <td class="table-success">Changed</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Average Charging Rate</th>
                            <td>{{ $old_values->average_rate }}</td>
                            <td>{{ $new_values->average_rate }}</td>
                            @if ($old_values->average_rate == $new_values->average_rate)
                                <td class="table-primary">Unchanged</td>
                            @else
                                <td class="table-success">Changed</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Other Services</th>
                            <td>{{ $old_values->other_services }}</td>
                            <td>{{ $new_values->other_services }}</td>
                            @if ($old_values->other_services == $new_values->other_services)
                                <td class="table-primary">Unchanged</td>
                            @else
                                <td class="table-success">Changed</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Hotel Star Rating</th>
                            <td>{{ $this->getNameById('hotel_star_id', $old_values->hotel_star_id) }} Star</td>
                            <td>{{ $this->getNameById('hotel_star_id', $new_values->hotel_star_id) }} Star</td>
                            @if ($old_values->hotel_star_id == $new_values->hotel_star_id)
                                <td class="table-primary">Unchanged</td>
                            @else
                                <td class="table-success">Changed</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
                <br>
            </div>
            @elseif ($business_update->type == 'transfer_ownership')
                <div class="col-md-12">
                    <table class="table table-striped table-sm">
                        <label class="font-weight-bold text-uppercase">{{ $business_update->business->name }} Hotel Information</label>
                        <thead>
                        <th style="width: 30%">Property</th>
                        <th style="width: 25%">Old Values</th>
                        <th style="width: 25%">New Values</th>
                        <th style="width: 20%">Status</th>
                        </thead>
                        <tbody>
                        <tr>
                            <th>Taxpayer Information</th>
                            <td>{{ $old_values->taxpayer_name ?? 'N/A' }} - {{ $old_values->taxpayer_ref_no ?? 'N/A' }}</td>
                            <td>{{ $new_values->taxpayer_name ?? 'N/A' }} - {{ $new_values->taxpayer_ref_no ?? 'N/A' }}</td>
                            @if ($old_values->taxpayer_id == $new_values->taxpayer_id)
                                <td class="table-primary">Unchanged</td>
                            @else
                                <td class="table-success">Changed</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Responsible Person Information</th>
                            <td>{{ $old_values->responsible_name ?? 'N/A' }} - {{ $old_values->responsible_ref_no ?? 'N/A' }}</td>
                            <td>{{ $new_values->responsible_person_name ?? 'N/A' }} - {{ $new_values->responsible_ref_no ?? 'N/A' }}</td>
                            @if ($old_values->responsible_person_id == $new_values->responsible_person_id)
                                <td class="table-primary">Unchanged</td>
                            @else
                                <td class="table-success">Changed</td>
                            @endif
                        </tr>

                        </tbody>
                    </table>
                    <br>
                </div>
            @endif

            @livewire('business.updates.changes-approval-processing', ['modelName' => 'App\Models\BusinessUpdate', 'modelId' => encrypt($business_update->id), 'businessUpdate' => $business_update])
        </div>
        <div id="tab2" class="tab-pane fade p-4">
            <livewire:approval.approval-history-table modelName='App\Models\BusinessUpdate'
                modelId="{{ encrypt($business_update->id) }}" />
        </div>
    </div>
</div>


@section('scripts')
    <script>
        $(document).ready(function() {
            $(".nav-tabs a").click(function() {
                $(this).tab('show');
            });
        });
    </script>
@endsection
