@extends('layouts.master')

@section('title','Return Queries')

@section('content')
    <div class="card rounded-0">
        <div class="card-header d-flex justify-content-between">
            <div>Return Details</div>
            <div>
                <a class="btn btn-info" href="{{ route('queries.sales-purchases') }}">
                    <i class="bi bi-arrow-return-left mr-2"></i>
                    Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Tax Payer Name</td>
                            <td class="my-1">{{$return->business->taxpayer->first_name ?? 'N/A'}} {{$return->business->taxpayer->last_name ?? 'N/A'}}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold text-uppercase">Business Name</td>
                            <td class="my-1">{{ $return->business->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Business Location Name</td>
                            <td class="my-1">{{ $return->businessLocation->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Tax Region</td>
                            <td class="my-1">{{ $return->businessLocation->taxRegion->name ?? 'N/A' }}</td>
                        </tr>
                        @if($return->businessLocation->date_of_commencing)
                            <tr>
                                <td class="font-weight-bold text-uppercase">Date of Business Commencement</td>
                                <td class="my-1">{{date('D, Y-m-d',strtotime($return->businessLocation->date_of_commencing)) }}</td>
                            </tr>
                        @endif

                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Tax Type</td>
                            <td class="my-1">{{$return->taxType->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Phone</td>
                            <td class="my-1">{{ $return->business->mobile ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Email</td>
                            <td class="my-1">{{ $return->business->email ?? 'N/A' }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold text-uppercase">Return Month</td>
                            <td class="my-1">{{ $return->financialMonth->name ?? 'N/A' }}, {{ $return->financialYear->code ?? 'N/A' }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold text-uppercase">Total Sales</td>
                            <td class="my-1">{{ number_format($return->total_sales,2) }} <strong>{{$return->currency}}</strong></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Total Purchases</td>
                            <td class="my-1">{{ number_format($return->total_purchases,2) }} <strong>{{$return->currency}}</strong></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection