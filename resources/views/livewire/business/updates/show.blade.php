<div class="row">
    <div class="col md-6">
        Old Values <br>

        <div class="mb-2">
            <h6>Business Information</h6>
            @foreach (json_decode($old_values)->business_information as $key => $value)
                <strong>{{ str_replace('_', ' ', $key) }}</strong> : {{ $value->name ?? $value }}<br>
            @endforeach
        </div>

        <div class="mb-2">
            <h6>Business Location</h6>
            @foreach (json_decode($old_values)->business_location as $key => $value)
                <strong>{{ str_replace('_', ' ', $key) }}</strong> : {{ $value->name ?? $value }} <br>
            @endforeach
        </div>

        <div class="mb-2">
            <h6>Business Bank</h6>
            @foreach (json_decode($old_values)->business_bank as $key => $value)
                <strong>{{ str_replace('_', ' ', $key) }}</strong> : {{ $value->name ?? $value }} <br>
            @endforeach
        </div>

    </div>

    <div class="col md-6">
        New Values <br>

        <div class="mb-2">
            <h6>Business Information</h6>
            @foreach (json_decode($new_values)->business_information as $key => $value)
                <strong>{{ str_replace('_', ' ', $key) }}</strong> : {{ $value->name ?? $value }} <br>
            @endforeach
        </div>

        <div class="mb-2">
            <h6>Business Location</h6>
            @foreach (json_decode($new_values)->business_location as $key => $value)
                <strong>{{ str_replace('_', ' ', $key) }}</strong> : {{ $value->name ?? $value }} <br>
            @endforeach
        </div>

        <div class="mb-2">
            <h6>Business Bank</h6>
            @foreach (json_decode($new_values)->business_bank as $key => $value)
                <strong>{{ str_replace('_', ' ', $key) }}</strong> : {{ $value->name ?? $value }}<br>
            @endforeach
        </div>

    </div>
</div>