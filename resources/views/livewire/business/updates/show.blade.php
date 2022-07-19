<div>

    <div class="row">
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

        {{-- <div class="col md-2">
            Status <br>

            <div class="mb-2">
                <h6>Business Information</h6>
                @foreach ($old_values->business_information as $key => $value)
                    <strong>{{ str_replace('_', ' ', $key) }}</strong> : {{ $value->name ?? $value }}<br>
                @endforeach
            </div>
        </div> --}}



    </div>

    <div class="row">
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" wire:click='approve'>Approve changes</button>
        </div>
    </div>
</div>
