<div class="card rounded-0">
    <div class="card-header bg-white font-weight-bold">Bill Preview per Annum</div>
    <div class="card-body">
        <table class="table normal-text mb-0">
            <tbody>
            <tr>
                <th width="20%">Bill Description</th>
                <td colspan="2">Property Tax bill for {{ $property->type }}</td>
            </tr>

            @if($property->type === \App\Enum\PropertyTypeStatus::HOTEL)
                <tr>
                    <th width="20%">Bill Item</th>
                    <td>Property Tax</td>
                    <th class="text-right">{{ number_format($hotelBill->amount_charged, 2) }}</th>
                </tr>
                <tr class="bg-secondary">
                    <th colspan="2">Total Billed Amount Per Annum</th>
                    <th class="text-right">{{ number_format($hotelBill->amount_charged, 2) }} {{ $hotelBill->currency->iso }}</th>
                </tr>
            @endif

            @if($property->type === \App\Enum\PropertyTypeStatus::CONDOMINIUM)
                <tr>
                    <th width="20%">Bill Item</th>
                    <td>Property Tax</td>
                    <th class="text-right">{{ number_format($condominiumBill, 2) }}</th>
                </tr>
                <tr class="bg-secondary">
                    <th colspan="2">Total Billed Amount Per Annum</th>
                    <th class="text-right">{{ number_format($condominiumBill, 2) }} TZS</th>
                </tr>
            @endif

            </tbody>
        </table>
    </div>
</div>