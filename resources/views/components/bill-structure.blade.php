<div class="card rounded-0">
    <div class="card-header bg-white font-weight-bold">Bill Summary</div>
    <div class="card-body">
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th width="20%">Bill Description:</th>
                <td colspan="2">{{ $bill->description }}</td>
            </tr>
            @foreach($bill->bill_items as $item)
                <tr>
                    <th width="20%">Bill Item:</th>
                    <td>{{ $item->taxType->name }}</td>
                    <th class="text-right">{{ number_format($item->amount, 2) }}</th>
                </tr>
            @endforeach
            <tr>
                <th colspan="2">Total Billed Amount:</th>
                <th class="text-right">{{ $bill->amount }} {{ $bill->currency }}</th>
            </tr>
            </tbody>
        </table>
    </div>
</div>