@extends('layouts.master')

@section('title','Upgrade Tax Type')

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
                <a class="btn btn-info" href="{{ route('upgrade-tax-types.index') }}">
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
                            <td class="font-weight-bold text-uppercase">Tax Type</td>
                            <td class="my-1">{{$return->taxtype->name }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Tax Region</td>
                            <td class="my-1">{{ $return->businessLocaton->taxRegion->name }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Phone</td>
                            <td class="my-1">{{ $return->business->mobile }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Email</td>
                            <td class="my-1">{{ $return->business->email }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Current Turnover</td>
                            <td class="my-1">{{ $return->businessLocation->name }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection