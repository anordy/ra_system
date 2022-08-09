<div class="card">

    <livewire:verification.declared-sales-analysis modelName='{{ get_class($return) }}' :return="$return" />

    <div class="card-body">
        <h6 class="text-uppercase mt-2 ml-2">Filled Return Details for {{ $return->financialMonth->name }}, {{ $return->financialMonth->year->code }}</h6>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-sm">
                    <thead>
                        <th style="width: 30%">Item Name</th>
                        <th style="width: 20%">Value</th>
                        <th style="width: 10%">Rate</th>
                        <th style="width: 20%">VAT</th>
                    </thead>
                    <tbody>
                        @foreach ($return->configReturns as $item)
                            <tr>
                                <td>{{ $item->config->name ?? 'name' }}</td>
                                <td>{{ number_format($item->value) }}</td>
                                <td>{{ $item->config->rate_type ?? '' === 'percentage' ? $item->config->rate ?? '' : $item->config->rate_usd ?? '' }}
                                </td>
                                <td>{{ number_format($item->vat) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th style="width: 20%"></th>
                            <th style="width: 30%"></th>
                            <th style="width: 25%"></th>
                            <th style="width: 25%">{{ number_format($return->total_amount_due) }}</th>
                        </tr>

                    </tfoot>
                </table>

            </div>
        </div>
    </div>
</div>
