<table style="border-collapse:collapse; width:100%">
    <thead>
    <tr>
        <th>
            <p style="margin-bottom: 0px; margin-top: 25px; text-align: left; font-size: 18px; text-transform: uppercase">Return Details</p>
        </th>
    </tr>
    </thead>
</table>
<table class="tbl-bordered tbl-p-6" style="width: 100%; margin-top: 10px;">
    <thead>
    <th style="width: 30%">Item Name</th>
    <th style="width: 20%">Value (TZS)</th>
    <th style="width: 10%">Rate</th>
    <th style="width: 20%">Tax (TZS)</th>
    </thead>
    <tbody>
    @foreach ($return->items as $item)
        <tr>
            <td>{{ $item->config->name }}</td>
            @if($item->config->code == 'WITHH')
                <td class="bg-secondary"></td>
            @else
                <td>{{ number_format($item->value, 2) }}</td>
            @endif
            @if($item->config->rate_applicable)
                <td>
                    {{ $item->config->rate_type === 'percentage' ? $item->config->rate . '%' : $item->config->rate_usd .''. $item->config->currency }}
                </td>
            @else
                <td class="bg-secondary"></td>
            @endif
            @if($item->config->is_summable)
                <td>{{ number_format($item->vat, 2) }}</td>
            @else
                <td class="bg-secondary"></td>
            @endif
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr class="bg-secondary">
        <th style="width: 20%">Total</th>
        <th style="width: 30%"></th>
        <th style="width: 25%"></th>
        <th style="width: 25%">{{ number_format($return->total_amount_due, 2) }}</th>
    </tr>
    </tfoot>
</table>


@if($return->withheld)
    <table style="border-collapse:collapse; width:100%">
        <thead>
        <tr>
            <th>
                <p style="margin-bottom: 0px; margin-top: 25px; text-align: left; font-size: 18px; text-transform: uppercase">{{ __('Return Withheld Details') }}</p>
            </th>
        </tr>
        </thead>
    </table>
    <table class="tbl-bordered tbl-p-6" style="width: 100%; margin-top: 10px;">
        <thead>
        <th></th>
        <th colspan="2">{{ __('Withholding') }}</th>
        <th colspan="2">{{ __('Agent') }}</th>
        <th colspan="2">{{ __('VFMS') }}</th>
        <th colspan="2">{{ __('Amount') }}</th>
        </thead>
        <thead>
        <th width="10">{{ __('SN') }}</th>
        <th>{{ __('Receipt No.') }}</th>
        <th>{{ __('Receipt Date') }}</th>
        <th>{{ __('Name') }}</th>
        <th>{{ __('Agent No') }}</th>
        <th>{{ __('Receipt No') }}</th>
        <th>{{ __('Receipt Date') }}</th>
        <th>{{ __('Net Amount') }}</th>
        <th>{{ __('Tax Withheld') }}</th>
        </thead>
        <tbody>
        @foreach ($return->withheld as $withheld)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $withheld->withholding_receipt_no }}</td>
                <td>{{ $withheld->withholding_receipt_date->toDateString() }}</td>
                <td>{{ $withheld->agent_name }}</td>
                <td>{{ $withheld->agent_no }}</td>
                <td>{{ $withheld->vfms_receipt_no }}</td>
                <td>{{ $withheld->vfms_receipt_date->toDateString() }}</td>
                <td>{{ number_format($withheld->net_amount) }} {{ $withheld->currency }}</td>
                <td>{{ number_format($withheld->tax_withheld) }} {{ $withheld->currency }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif