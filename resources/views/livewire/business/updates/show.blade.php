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
                            {{-- <tr>
                            <th>TIN</th>
                            <td>{{ $old_values->business_information->tin }}</td>
                            <td>{{ $new_values->business_information->tin }}</td>
                        </tr> --}}
                            <tr>
                                <th>Reg No</th>
                                <td>{{ $old_values->business_information->reg_no }}</td>
                                <td>{{ $new_values->business_information->reg_no }}</td>
                                @if ($old_values->business_information->reg_no == $new_values->business_information->reg_no)
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
                                <td>{{ $old_values->business_information->alt_mobile }}</td>
                                <td>{{ $new_values->business_information->alt_mobile }}</td>
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
                            <tr>
                                <th>Physical Address</th>
                                <td>{{ $old_values->business_information->physical_address }}</td>
                                <td>{{ $new_values->business_information->physical_address }}</td>
                                @if ($old_values->business_information->physical_address == $new_values->business_information->physical_address)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            {{-- <tr>
                            <th>Date of Commencing</th>
                            <td>{{ $old_values->business_information->date_of_commencing }}</td>
                            <td>{{ $new_values->business_information->date_of_commencing }}</td>
                        </tr>
                        <tr>
                            <th>Pre Estimated Turnover</th>
                            <td>{{ $old_values->business_information->pre_estimated_turnover }}</td>
                            <td>{{ $new_values->business_information->pre_estimated_turnover }}</td>
                        </tr>
                        <tr>
                            <th>Post Estimated Turnover</th>
                            <td>{{ $old_values->business_information->post_estimated_turnover }}</td>
                            <td>{{ $new_values->business_information->post_estimated_turnover }}</td>
                        </tr>
                        <tr>
                            <th>Goods and Services Types</th>
                            <td>{{ $old_values->business_information->goods_and_services_types }}</td>
                            <td>{{ $new_values->business_information->goods_and_services_types }}</td>
                        </tr>
                        <tr>
                            <th>Goods and Services Examples</th>
                            <td>{{ $old_values->business_information->goods_and_services_example }}</td>
                            <td>{{ $new_values->business_information->goods_and_services_example }}</td>
                        </tr> --}}

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
                                <td>{{ $old_values->business_location->owner_name }}</td>
                                <td>{{ $new_values->business_location->owner_name }}</td>
                                @if ($old_values->business_location->owner_name == $new_values->business_location->owner_name)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Owners Phone No</th>
                                <td>{{ $old_values->business_location->owner_phone_no }}</td>
                                <td>{{ $new_values->business_location->owner_phone_no }}</td>
                                @if ($old_values->business_location->owner_phone_no == $new_values->business_location->owner_phone_no)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Physical Address</th>
                                <td>{{ $old_values->business_location->physical_address }}</td>
                                <td>{{ $new_values->business_location->physical_address }}</td>
                                @if ($old_values->business_location->physical_address == $new_values->business_location->physical_address)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Street</th>
                                <td>{{ $old_values->business_location->street }}</td>
                                <td>{{ $new_values->business_location->street }}</td>
                                @if ($old_values->business_location->street == $new_values->business_location->street)
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

                        </tbody>
                    </table>
                </div>
            @else
                <div class="col-md-12">
                    <table class="table table-striped table-sm">
                        <label class="font-weight-bold text-uppercase">Consultant / Preparer</label>
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
                        </tbody>
                    </table>
                    <br>
                    @if ($agent_contract && $new_values->is_own_consultant == 0)
                    <div class="col-md-3">
                        <a class="file-item" target="_blank"
                           href="{{ route('business.contract.file', encrypt($business_update->id)) }}">
                            <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                            <div style="font-weight: 500;" class="ml-1">
                                View Agent Contract
                            </div>
                        </a>
                    </div>
                    @endif
              
                    <br>
                </div>
            @endif


            @livewire('business.updates.changes-approval-processing', ['modelName' => 'App\Models\BusinessUpdate', 'modelId' => $business_update->id, 'businessUpdate' => $business_update])
        </div>
        <div id="tab2" class="tab-pane fade p-4">
            <livewire:approval.approval-history-table modelName='App\Models\BusinessUpdate'
                modelId="{{ $business_update->id }}" />
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
