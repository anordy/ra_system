<html>

<head>
    <title></title>
    <style>
        body {
            background-image: url("{{ public_path() }}/images/logo.png");
            background-repeat: no-repeat;
            background-position: center;
            background-size:contain;
            margin: 15px;
            opacity: 0.03;
        }

        thead {
            text-align: center
        }

        .tableHead {
            background-color: rgb(182, 193, 208);
            color: rgb(0, 0, 0);
        }

        tbody tr:nth-child(odd) {
            background-color: #fff;
        }

        tbody tr:nth-child(even) {
            background-color: #ddd;
        }

        .total {
            background-color: rgb(201, 201, 201);
            color: rgb(0, 0, 0);
            font-weight: bold;
        }

        .zrb {
            /* background-color: rgb(182, 193, 208); */
            color: rgb(19, 19, 19);
            font-weight: bold;
            font-size: 30px;
            margin-bottom: 3px;
        }

        .table {
            border: 1px solid black;
            width: 100%;
            border-collapse: collapse;
            background: transparent;
        }

        .tbl-bordered td, .tbl-bordered th, .tbl-bordered {
            border: .5px solid black;
            border-collapse: collapse;
        }

        .tbl-p-6 td, .tbl-p-6 th {
            padding: 6px;
        }
    </style>
</head>

<body style="font-size: 8pt">

<table style="border-collapse:collapse; width:100%">
    <thead>
    <tr>
        <th style="text-align:center;" colspan="15">
            <p class="zrb">ZANZIBAR REVENUE AUTHORITY</p>
            <strong>{{ $return->taxType->name }} Tax Return</strong><br>
        </th>
    </tr>
    </thead>
</table>
<br>
<table style="width: 100%;" class="tbl-bordered tbl-p-6">
    <tbody>
    <tr>
        <td>
            <strong>Business Name</strong>
        </td>
        <td>{{ $return->business->name }}</td>
    </tr>
    <tr>
        <td>
            <strong>Business Location</strong>
        </td>
        <td>{{ $return->businessLocation->name }}</td>
    </tr>
    <tr>
        <td>
            <strong>Return Month</strong>
        </td>
        <td>{{ $return->financialMonth->name }} {{ $return->financialMonth->year->code }}</td>
    </tr>
    <tr>
        <td>
            <strong>Filed By</strong>
        </td>
        <td>{{ $return->taxpayer->fullName }}</td>
    </tr>
    <tr>
        <td>
            <strong>Return Category</strong>
        </td>
        <td>{{ strtoupper($return->return_category) }}</td>
    </tr>
    <tr>
        <td>
            <strong>Application Status</strong>
        </td>
        <td>{{ strtoupper($return->application_status) }}</td>
    </tr>
    </tbody>
</table>

@if($return)
    <table style="border-collapse:collapse; width:100%">
        <thead>
        <tr>
            <th>
                <p style="margin-bottom: 0px; margin-top: 25px; text-align: left; font-size: 18px; text-transform: uppercase">Return Details (TZS)</p>
            </th>
        </tr>
        </thead>
    </table>
    <table class="tbl-bordered tbl-p-6" style="width: 100%; margin-top: 10px;">
        <thead>
        <th style="width: 30%">Item Name</th>
        <th style="width: 20%">Value ({{ $return->currency }})</th>
        <th style="width: 10%">Rate</th>
        <th style="width: 20%">Tax ({{ $return->currency  }})</th>
        </thead>
        <tbody>
        @foreach ($return->configReturns as $item)
            <tr>
                <td>{{ $item->config->name }}</td>
                <td>{{ number_format($item->value, 2) }}</td>
                <td>
                    @if ($item->config->rate_type == 'fixed')
                        @if ($item->config->currency == 'both')
                            {{ $item->config->rate }} TZS <br>
                            {{ $item->config->rate_usd }} USD
                        @elseif ($item->config->currency == 'TZS')
                            {{ $item->config->rate }} TZS
                        @elseif ($item->config->currency == 'USD')
                            {{ $item->config->rate_usd }} USD
                        @endif
                    @elseif ($item->config->rate_type == 'percentage')
                        {{ $item->config->rate }} %
                    @endif
                    {{-- {{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd }} --}}
                </td>
                <td>{{ number_format($item->vat, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr class="bg-secondary">
            <th style="width: 20%">Total</th>
            <th style="width: 30%"></th>
            <th style="width: 25%"></th>
            <th style="width: 25%">{{ number_format($return->total_amount_due) }}</th>
        </tr>
        </tfoot>
    </table>
@endif

@if($return_)
    <table style="border-collapse:collapse; width:100%">
        <thead>
        <tr>
            <th>
                <p style="margin-bottom: 0px; margin-top: 25px; text-align: left; font-size: 18px; text-transform: uppercase">Return Details (USD)</p>
            </th>
        </tr>
        </thead>
    </table>
    <table class="tbl-bordered tbl-p-6" style="width: 100%; margin-top: 10px;">
        <thead>
        <th style="width: 30%">Item Name</th>
        <th style="width: 20%">Value ({{ $return->currency }})</th>
        <th style="width: 10%">Rate</th>
        <th style="width: 20%">Tax ({{ $return->currency  }})</th>
        </thead>
        <tbody>
        @foreach ($return_->configReturns as $item)
            <tr>
                <td>{{ $item->config->name }}</td>
                <td>{{ number_format($item->value, 2) }}</td>
                <td>
                    @if ($item->config->rate_type == 'fixed')
                        @if ($item->config->currency == 'both')
                            {{ $item->config->rate }} TZS <br>
                            {{ $item->config->rate_usd }} USD
                        @elseif ($item->config->currency == 'TZS')
                            {{ $item->config->rate }} TZS
                        @elseif ($item->config->currency == 'USD')
                            {{ $item->config->rate_usd }} USD
                        @endif
                    @elseif ($item->config->rate_type == 'percentage')
                        {{ $item->config->rate }} %
                    @endif
                    {{-- {{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd }} --}}
                </td>
                <td>{{ number_format($item->vat, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr class="bg-secondary">
            <th style="width: 20%">Total</th>
            <th style="width: 30%"></th>
            <th style="width: 25%"></th>
            <th style="width: 25%">{{ number_format($return->total_amount_due) }}</th>
        </tr>
        </tfoot>
    </table>
@endif

@if($return && count($return->penalties))
    <table style="border-collapse:collapse; width:100%">
        <thead>
        <tr>
            <th>
                <p style="margin-bottom: 0px; margin-top: 25px; text-align: left; font-size: 18px; text-transform: uppercase">Penalties (TZS)</p>
            </th>
        </tr>
        </thead>
    </table>
    <table class="tbl-bordered tbl-p-6" style="width: 100%; margin-top: 10px;">
        <thead>
        <tr>
            <th>Month</th>
            <th>Tax Amount</th>
            <th>Late Filing Amount</th>
            <th>Late Payment Amount</th>
            <th>Interest Rate</th>
            <th>Interest Amount</th>
            <th>Payable Amount</th>
        </tr>
        </thead>

        <tbody>
        @php
            $return->penalties = $return->penalties->concat($return->tax_return->penalties)->sortBy('tax_amount');
        @endphp
        @if(count($return->penalties))
            @foreach ($return->penalties as $penalty)
                <tr>
                    <td>{{ $penalty['financial_month_name'] }}</td>
                    <td>{{ number_format($penalty['tax_amount'], 2) }} <strong>{{ $return->currency}}</strong></td>
                    <td>{{ number_format($penalty['late_filing'], 2) }} <strong>{{ $return->currency}}</strong></td>
                    <td>{{ number_format($penalty['late_payment'], 2) }} <strong>{{ $return->currency}}</strong></td>
                    <td>{{ number_format($penalty['rate_percentage'], 4) }}</td>
                    <td>{{ number_format($penalty['rate_amount'], 2) }} <strong>{{ $return->currency}}</strong></td>
                    <td>{{ number_format($penalty['penalty_amount'], 2)}} <strong>{{ $return->currency}}</strong></td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="text-center py-3">
                    No penalties for this return.
                </td>
            </tr>
        @endif
        </tbody>
    </table>
@endif
@if($return_ && count($return_->penalties))
    <table style="border-collapse:collapse; width:100%">
        <thead>
        <tr>
            <th>
                <p style="margin-bottom: 0px; margin-top: 25px; text-align: left; font-size: 18px; text-transform: uppercase">Penalties (USD)</p>
            </th>
        </tr>
        </thead>
    </table>
    <table class="tbl-bordered tbl-p-6" style="width: 100%; margin-top: 10px;">
        <thead>
        <tr>
            <th>Month</th>
            <th>Tax Amount</th>
            <th>Late Filing Amount</th>
            <th>Late Payment Amount</th>
            <th>Interest Rate</th>
            <th>Interest Amount</th>
            <th>Payable Amount</th>
        </tr>
        </thead>

        <tbody>
         @php
            $return_->penalties = $return_->penalties->merge($return_->tax_return->penalties);
        @endphp
        @if(count($return_->penalties))
            @foreach ($return_->penalties as $penalty)
                <tr>
                    <td>{{ $penalty['financial_month_name'] }}</td>
                    <td>{{ number_format($penalty['tax_amount'], 2) }} <strong>{{ $return_->currency}}</strong></td>
                    <td>{{ number_format($penalty['late_filing'], 2) }} <strong>{{ $return_->currency}}</strong></td>
                    <td>{{ number_format($penalty['late_payment'], 2) }} <strong>{{ $return_->currency}}</strong></td>
                    <td>{{ number_format($penalty['rate_percentage'], 4) }}</td>
                    <td>{{ number_format($penalty['rate_amount'], 2) }} <strong>{{ $return_->currency}}</strong></td>
                    <td>{{ number_format($penalty['penalty_amount'], 2)}} <strong>{{ $return_->currency}}</strong></td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="text-center py-3">
                    No penalties for this return.
                </td>
            </tr>
        @endif
        </tbody>
    </table>
@endif

@if($return && $return->tax_return->latestBill)
    @php($bill = $return->tax_return->latestBill)
    <table style="border-collapse:collapse; width:100%">
        <thead>
        <tr>
            <th>
                <p style="margin-bottom: 0px; margin-top: 25px; text-align: left; font-size: 18px; text-transform: uppercase;">Bill Summary (TZS)</p>
            </th>
        </tr>
        </thead>
    </table>
    <table class="tbl-bordered tbl-p-6" style="width: 100%; margin-top: 10px;">
        <tbody>
        <tr>
            <th width="20%">Bill Description</th>
            <td colspan="2">{{ $bill->description }}</td>
        </tr>
        @foreach ($bill->bill_items as $item)
            <tr>
                <th width="20%">Bill Item</th>
                <td>{{ $item->taxType->name }}</td>
                <th>{{ number_format($item->amount, 2) }}</th>
            </tr>
        @endforeach
        <tr class="bg-secondary">
            <th colspan="2">Total Billed Amount</th>
            <th class="text-right">{{ number_format($bill->amount, 2) }} {{ $bill->currency }}</th>
        </tr>
        </tbody>
    </table>
@endif
@if($return_ && $return_->tax_return->latestBill)
    @php($bill_ = $return_->tax_return->latestBill)
    <table style="border-collapse:collapse; width:100%">
        <thead>
        <tr>
            <th>
                <p style="margin-bottom: 0px; margin-top: 25px; text-align: left; font-size: 18px; text-transform: uppercase;">Bill Summary (USD)</p>
            </th>
        </tr>
        </thead>
    </table>
    <table class="tbl-bordered tbl-p-6" style="width: 100%; margin-top: 10px;">
        <tbody>
        <tr>
            <th width="20%">Bill Description</th>
            <td colspan="2">{{ $bill_->description }}</td>
        </tr>
        @foreach ($bill_->bill_items as $item)
            <tr>
                <th width="20%">Bill Item</th>
                <td>{{ $item->taxType->name }}</td>
                <th>{{ number_format($item->amount, 2) }}</th>
            </tr>
        @endforeach
        <tr class="bg-secondary">
            <th colspan="2">Total Billed Amount</th>
            <th class="text-right">{{ number_format($bill_->amount, 2) }} {{ $bill_->currency }}</th>
        </tr>
        </tbody>
    </table>
@endif
</body>
</html>
