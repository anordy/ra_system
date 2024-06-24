<div class="card-body pb-0">
    <nav class="nav nav-tabs mt-0 border-top-0">
        <a href="#tab1" class="nav-item nav-link font-weight-bold active">Business Changes Details</a>
        <a href="#tab2" class="nav-item nav-link font-weight-bold">Approval History</a>
    </nav>
    <div class="tab-content px-2 card pt-3 pb-2">
        <div id="tab1" class="tab-pane fade active show p-4">

            <div class="col-md-12">
                <table class="table table-bordered table-striped table-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="text-left font-weight-bold text-uppercase">KYC Changed Information</label>
                        <label class="text-center">
                            <div>
                                @include('kyc.amendments.includes.status')
                            </div>
                        </label>
                        <label class="text-right text-uppercase">Requested by
                            <strong>{{ $createdBy }}</strong> <br> on
                            <strong>{{ $amendmentRequest->created_at->toFormattedDateString() }}</strong></label>
                    </div>

                    <thead>
                    <th>Property</th>
                    <th>Old Values</th>
                    <th>New Values</th>
                    <th>Status</th>
                    </thead>
                    <tbody>
                    <tr>
                        <th>First Name</th>
                        <td>{{ $old_values->first_name }}</td>
                        <td>{{ $new_values->first_name }}</td>
                        @if ($old_values->first_name == $new_values->first_name)
                            <td class="table-primary">Unchanged</td>
                        @else
                            <td class="table-success">Changed</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Middle Name</th>
                        <td>{{ $old_values->middle_name }}</td>
                        <td>{{ $new_values->middle_name }}</td>
                        @if ($old_values->middle_name == $new_values->middle_name)
                            <td class="table-primary">Unchanged</td>
                        @else
                            <td class="table-success">Changed</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Last Name</th>
                        <td>{{ $old_values->last_name }}</td>
                        <td>{{ $new_values->last_name }}</td>
                        @if ($old_values->last_name == $new_values->last_name)
                            <td class="table-primary">Unchanged</td>
                        @else
                            <td class="table-success">Changed</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $old_values->email }}</td>
                        <td>{{ $new_values->email }}</td>
                        @if ($old_values->email == $new_values->email)
                            <td class="table-primary">Unchanged</td>
                        @else
                            <td class="table-success">Changed</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td>{{ $old_values->mobile }}</td>
                        <td>{{ $new_values->mobile }}</td>
                        @if ($old_values->mobile == $new_values->mobile)
                            <td class="table-primary">Unchanged</td>
                        @else
                            <td class="table-success">Changed</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Alternative Phone Number</th>
                        <td>{{ $old_values->alt_mobile }}</td>
                        <td>{{ $new_values->alt_mobile }}</td>
                        @if ($old_values->alt_mobile == $new_values->alt_mobile)
                            <td class="table-primary">Unchanged</td>
                        @else
                            <td class="table-success">Changed</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Physical Address</th>
                        <td>{{ $old_values->physical_address }}</td>
                        <td>{{ $new_values->physical_address }}</td>
                        @if ($old_values->physical_address == $new_values->physical_address)
                            <td class="table-primary">Unchanged</td>
                        @else
                            <td class="table-success">Changed</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Region</th>
                        <td>{{ \App\Models\Region::find($old_values->region_id)->name }}</td>
                        <td>{{ \App\Models\Region::find($new_values->region_id)->name }}</td>
                        @if ($old_values->region_id == $new_values->region_id)
                            <td class="table-primary">Unchanged</td>
                        @else
                            <td class="table-success">Changed</td>

                        @endif
                    </tr>
                    <tr>
                        <th>District</th>
                        <td>{{ \App\Models\District::find($old_values->district_id)->name ?? 'N/A' }}</td>
                        <td>{{ \App\Models\District::find($new_values->district_id)->name ?? 'N/A' }}</td>
                        @if ($old_values->district_id == $new_values->district_id)
                            <td class="table-primary">Unchanged</td>
                        @else
                            <td class="table-success">Changed</td>

                        @endif
                    </tr>
                    <tr>
                        <th>Ward</th>
                        <td>{{ \App\Models\Ward::find($old_values->ward_id)->name ?? 'N/A' }}</td>
                        <td>{{ \App\Models\Ward::find($new_values->ward_id)->name ?? 'N/A' }}</td>
                        @if ($old_values->ward_id == $new_values->ward_id)
                            <td class="table-primary">Unchanged</td>
                        @else
                            <td class="table-success">Changed</td>

                        @endif
                    </tr>
                    <tr>
                        <th>Street</th>
                        <td>{{ \App\Models\Street::find($old_values->street_id)->name ?? 'N/A' }}</td>
                        <td>{{ \App\Models\Street::find($new_values->street_id)->name ?? 'N/A' }}</td>
                        @if ($old_values->street_id == $new_values->street_id)
                            <td class="table-primary">Unchanged</td>
                        @else
                            <td class="table-success">Changed</td>

                        @endif
                    </tr>
                    @if($old_values->is_citizen != $new_values->is_citizen)
                        @if ($new_values->is_citizen == '1')
                            <tr>
                                <th>Nida Number</th>
                                <td>{{ $old_values->nida_no ?? 'N/A' }}</td>
                                <td>{{ $new_values->nida_no ?? 'N/A' }}</td>
                                @if ($old_values->nida_no == $new_values->nida_no)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Zan ID</th>
                                <td>{{ $old_values->zanid_no ?? 'N/A' }}</td>
                                <td>{{ $new_values->zanid_no ?? 'N/A' }}</td>
                                @if ($old_values->zanid_no == $new_values->zanid_no)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                        @elseif($new_values->is_citizen == '0')
                            <tr>
                                <th>Nationality {{$old_values->country_id }} {{$new_values->country_id}}</th>
                                <td>{{ \App\Models\Country::find($old_values->country_id)->nationality ?? 'N/A' }}</td>
                                <td>{{ \App\Models\Country::find($new_values->country_id)->nationality ?? 'N/A' }}</td>
                                @if ($old_values->country_id == $new_values->country_id)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Passport Number</th>
                                <td>{{ $old_values->passport_no ?? 'N/A' }}</td>
                                <td>{{ $new_values->passport_no ?? 'N/A' }}</td>
                                @if ($old_values->passport_no == $new_values->passport_no)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Permit Number</th>
                                <td>{{ $old_values->permit_number ?? 'N/A' }}</td>
                                <td>{{ $new_values->permit_number ?? 'N/A' }}</td>
                                @if ($old_values->permit_number == $new_values->permit_number)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                        @endif
                    @else
                        @if ($old_values->is_citizen == '1')
                            <tr>
                                <th>Nida Number</th>
                                <td>{{ $old_values->nida_no }}</td>
                                <td>{{ $new_values->nida_no }}</td>
                                @if ($old_values->nida_no == $new_values->nida_no)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Zan ID</th>
                                <td>{{ $old_values->zanid_no }}</td>
                                <td>{{ $new_values->zanid_no }}</td>
                                @if ($old_values->zanid_no == $new_values->zanid_no)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                        @elseif($old_values->is_citizen == '0')
                            <tr>
                                <th>Nationality</th>
                                <td>{{ \App\Models\Country::find($old_values->country_id)->value('nationality') }}</td>
                                <td>{{ \App\Models\Country::find($new_values->country_id)->value('nationality') }}</td>
                                @if ($old_values->country_id == $new_values->country_id)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Passport Number</th>
                                <td>{{ $old_values->passport_no ?? 'N/A' }}</td>
                                <td>{{ $new_values->passport_no ?? 'N/A' }}</td>
                                @if ($old_values->passport_no == $new_values->passport_no)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Permit Number</th>
                                <td>{{ $old_values->permit_number ?? 'N/A' }}</td>
                                <td>{{ $new_values->permit_number ?? 'N/A' }}</td>
                                @if ($old_values->permit_number == $new_values->permit_number)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                        @endif
                    @endif
                    </tbody>
                </table>
            </div>

            @livewire('kyc.kyc-amendment-request-approval-processing', ['modelName' => 'App\Models\KycAmendmentRequest', 'modelId' => encrypt($amendmentRequest->id)])
        </div>
        <div id="tab2" class="tab-pane fade p-4">
            <livewire:approval.approval-history-table modelName='App\Models\KycAmendmentRequest'
                                                      modelId="{{ encrypt($amendmentRequest->id) }}" />
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
