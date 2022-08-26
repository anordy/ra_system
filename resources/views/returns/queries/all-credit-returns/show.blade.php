@extends('layouts.master')

@section('title','Return Queries')

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
            <div>Return Details</div>
            <div>
                <a class="btn btn-info" href="{{ route('queries.all-credit-returns') }}">
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
                            <td class="my-1">{{$return->business->taxpayer->first_name}} {{$return->business->taxpayer->last_name}}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold text-uppercase">Business Name</td>
                            <td class="my-1">{{ $return->business->name  }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Business Location Name</td>
                            <td class="my-1">{{ $return->businessLocation->name  }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Tax Region</td>
                            <td class="my-1">{{ $return->businessLocation->taxRegion->name }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Date of Business Commencement</td>
                            <td class="my-1">{{date('D, Y-m-d',strtotime($return->businessLocation->date_of_commencing)) }}</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Tax Type</td>
                            <td class="my-1">{{$return->taxtype->name }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Phone</td>
                            <td class="my-1">{{ $return->business->mobile }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Email</td>
                            <td class="my-1">{{ $return->business->email }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Total Sales</td>
                            <td class="my-1">{{ number_format($sales,2) }} <strong>{{$currency}}</strong></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection