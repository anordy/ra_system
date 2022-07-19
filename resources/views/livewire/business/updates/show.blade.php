<div class="card-body pb-0">
    <nav class="nav nav-tabs mt-0 border-top-0">
        <a href="#tab1" class="nav-item nav-link font-weight-bold active">Business Changes Details</a>
        <a href="#tab2" class="nav-item nav-link font-weight-bold">Approval History</a>
    </nav>
    <div class="tab-content px-2 card pt-3 pb-2">
        <div id="tab1" class="tab-pane fade active show">
            @livewire('business.updates.changes-approval-processing', ['modelName' => 'App\Models\BusinessUpdate', 'modelId' => $business_update->id, 'businessUpdate' => $business_update])
            <div class="row mx-4">
                <div class="col md-5">
                    Old Values <br>

                    <div class="mb-2">
                        <h6>Business Information</h6>
                        @foreach ($old_values->business_information as $key => $value)
                            <strong>{{ str_replace('_', ' ', $key) }}</strong> : {{ $value->name ?? $value }}<br>
                        @endforeach
                    </div>

                    <div class="mb-2">
                        <h6>Business Location</h6>
                        @foreach ($old_values->business_location as $key => $value)
                            <strong>{{ str_replace('_', ' ', $key) }}</strong> : {{ $value->name ?? $value }} <br>
                        @endforeach
                    </div>

                    <div class="mb-2">
                        <h6>Business Bank</h6>
                        @foreach ($old_values->business_bank as $key => $value)
                            <strong>{{ str_replace('_', ' ', $key) }}</strong> : {{ $value->name ?? $value }} <br>
                        @endforeach
                    </div>

                </div>

                <div class="col md-5">
                    New Values <br>

                    <div class="mb-2">
                        <h6>Business Information</h6>
                        @foreach ($new_values->business_information as $key => $value)
                            <strong>{{ str_replace('_', ' ', $key) }}</strong> :
                            @if ($key == 'business_activities_type_id' || $key == 'currency_id')
                                {{ $this->getNameById($key, $value) }}
                            @else
                                {{ $value->name ?? $value }}
                            @endif
                            <br>
                        @endforeach
                    </div>

                    <div class="mb-2">
                        <h6>Business Location</h6>
                        @foreach ($new_values->business_location as $key => $value)
                            <strong>{{ str_replace('_', ' ', $key) }}</strong> :
                            @if ($key == 'region_id' || $key == 'district_id' || $key == 'ward_id')
                                {{ $this->getNameById($key, $value) }}
                            @else
                                {{ $value->name ?? $value }}
                            @endif
                            <br>
                        @endforeach
                    </div>

                    <div class="mb-2">
                        <h6>Business Bank</h6>
                        @foreach ($new_values->business_bank as $key => $value)
                            <strong>{{ str_replace('_', ' ', $key) }}</strong> :
                            @if ($key == 'bank_id' || $key == 'account_type_id' || $key == 'currency_id')
                                {{ $this->getNameById($key, $value) }}
                            @else
                                {{ $value->name ?? $value }}
                            @endif
                            <br>
                        @endforeach
                    </div>

                </div>

            </div>
        </div>
        <div id="tab2" class="tab-pane fade">
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
