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
                        <label class="text-left font-weight-bold text-uppercase">Taxpayer Changed Information</label>
                        <label class="text-center">
                            <div>
                                @include('taxpayers.amendments.includes.status')
                            </div>
                        </label>
                        <label class="text-right text-uppercase">Requested by
                            <strong>{{ $createdBy }}</strong> <br> on
                            <strong>{{ $amendmentRequest->created_at->toFormattedDateString() }}</strong></label>
                    </div>

                    <thead>
                        <th style="width: 20%">Property</th>
                        <th style="width: 30%">Old Values</th>
                        <th style="width: 30%">New Values</th>
                        <th style="width: 20%">Status</th>
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
                            <th>Altenatine Phone Number</th>
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
                    </tbody>
                </table>
            </div>

            @livewire('taxpayers.details-amendment-request-approval-processing', ['modelName' => 'App\Models\TaxpayerAmendmentRequest', 'modelId' => encrypt($amendmentRequest->id), 'amendmentRequest' => $amendmentRequest])
        </div>
        <div id="tab2" class="tab-pane fade p-4">
            <livewire:approval.approval-history-table modelName='App\Models\TaxpayerAmendmentRequest'
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
