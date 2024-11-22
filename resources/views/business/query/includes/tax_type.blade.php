<table class="table table-sm px-2">
    <thead>
    <tr>
        <th>No</th>
        <th>Tax Type</th>
        <th>Currency</th>
    </tr>
    </thead>
    <tbody>
    @foreach($business->taxes as $i => $type)
        <tr>
            <td class="px-2">{{ $i+1 }}.</td>
            <td class="px-2">{{ $type->taxType->code === \App\Models\TaxType::VAT ? $type->subvat->name : $type->taxType->name }}</td>
            <td class="px-2">{{ $type->currency ?? 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>


@if($business->lumpsumPayment)
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Lumpsum Annual Estimate</span>
        <p class="my-1">{{ number_format($business->lumpsumPayment->annual_estimate, 2) ?? 'N/A' }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Payment Quarters</span>
        <p class="my-1">{{ $business->lumpsumPayment->payment_quarters ?? 'N/A' }}</p>
    </div>
@endif