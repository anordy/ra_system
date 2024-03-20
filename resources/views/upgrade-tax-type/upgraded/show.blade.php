@extends('layouts.master')

@section('title','Upgraded Tax Type')

@section('css')
    <style>
        .table td, .table th {
            border-top: none;
        }

    </style>
@endsection
@section('content')
    <div class="card rounded-0">
        <div class="card-header d-flex justify-content-between">
            <div>Upgraded Tax Type Details</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Business Name</td>
                            <td class="my-1">{{ $tax_type_change->business->name  }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Tax Payer Name</td>
                            <td class="my-1">{{$tax_type_change->business->taxpayer->first_name}} {{$tax_type_change->business->taxpayer->middle_name}} {{$tax_type_change->business->taxpayer->last_name}}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold text-uppercase">Upgraded From Tax Type</td>
                            <td class="my-1">
                                <span class="badge badge-info py-1 px-2">
                                    {{$tax_type_change->fromTax->name }}-{{$tax_type_change->from_tax_type_currency}}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold text-uppercase">Upgraded To</td>
                            <td class="my-1">
                                <span class="badge badge-info py-1 px-2">
                                    @if($tax_type_change->toTax->code === \App\Models\TaxType::VAT)
                                        {{$tax_type_change->subvat->name }} - {{$tax_type_change->to_tax_type_currency}}
                                    @else
                                        {{$tax_type_change->toTax->name }} - {{$tax_type_change->to_tax_type_currency}}
                                    @endif
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Recommendation/Grounds</td>
                            <td class="my-1">{{$tax_type_change->reason}}</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Phone</td>
                            <td class="my-1">{{ $tax_type_change->business->mobile }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Email</td>
                            <td class="my-1">{{ $tax_type_change->business->email }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Status</td>
                            <td class="my-1">{{$tax_type_change->status }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Date Upgraded</td>
                            <td class="my-1">{{$tax_type_change->created_at ? \Carbon\Carbon::create($tax_type_change->created_at)->format('d M Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Effective Date</td>
                            <td class="my-1">{{$tax_type_change->effective_date ? \Carbon\Carbon::create($tax_type_change->effective_date)->format('d M Y') : 'N/A' }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection