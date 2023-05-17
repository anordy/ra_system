@extends('layouts.master')

@section('title')
    Vat Return
@endsection

@section('content')
    <div>
        @if(empty($return))
            <div class="card">
                <div class="card-header"><h6>Business Profile</h6></div>
                <div class="card-body">
                    <div class="row my-2">

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Name</span>
                            <p class="my-1">{{ $return->business->name }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Taxpayer Identification Number (TIN)</span>
                            <p class="my-1">{{ $return->business->tin }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Tax Type</span>
                            <p class="my-1">{{ $return->taxtype->name }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Mobile No.</span>
                            <p class="my-1">{{ $return->business->mobile }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Accounting Period</span>
                            <p class="my-1">{{ $return->financial_year }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Month</span>
                            <p class="my-1">
                                <span style="text-transform: capitalize">{{$return->return_month}}</span>
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h6> {{$return->taxtype->name}} Return for the month of <span style="text-transform: capitalize">{{$return->return_month}}</span></h6></div>
                <div class="card-body">
                    <div>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Description</th>
                                <th class="text-right">Amount</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr>
                                <td>Output Tax</td>
                                <td class="text-right">{{number_format($return->standard_rated_supplies,2, '.',',')}}</td>
                            </tr>

                            <tr>
                                <td>Input Tax</td>
                                <td class="text-right">{{number_format($return->total_input_tax,2, '.',',')}}</td>
                            </tr>

                            <tr>
                                <th>Vat Payable/To Claim</th>
                                <th class="text-right">{{number_format($return->total_vat_payable,2, '.',',')}}</th>
                            </tr>

                            <tr>
                                <td>Vat Credit Brought Forward</td>
                                <td class="text-right">{{number_format($return->vat_credit_brought_forward,2, '.',',' )}}</td>
                            </tr>

                            <tr>
                                <th>Net Vat Payable</th>
                                <th class="text-right">{{number_format($return->total_vat_amount_due, 2, '.',',')}}</th>
                            </tr>

                            <tr>
                                <th>Penalty for late filling</th>
                                <th class="text-right">{{number_format($filling_penalty, 2, '.',',')}}</th>
                            </tr>

                            <tr>
                                <th>Grand Vat Payable</th>
                                <th class="text-right">{{number_format($total, 2, '.',',')}}</th>
                            </tr>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-danger text-center">
                you have not filled any return for this month
            </div>
        @endif


    </div>
@endsection

@section('scripts')

@endsection