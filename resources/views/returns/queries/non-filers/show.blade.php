@extends('layouts.master')

@section('title','Return Queries')

@section('content')
    <div class="card rounded-0">
        <div class="card-header d-flex justify-content-between">
            <div>Return Details</div>
            <div>
                <a class="btn btn-info" href="{{ route('queries.non-filers') }}">
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
                            <td class="my-1">{{$non_filer->business->taxpayer->first_name ?? 'N/A'}} {{$non_filer->business->taxpayer->last_name ?? 'N/A'}}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold text-uppercase">Business Name</td>
                            <td class="my-1">{{ $non_filer->business->name ?? 'N/A'  }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Business Location Name</td>
                            <td class="my-1">{{ $non_filer->businessLocation->name ?? 'N/A'  }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Tax Region</td>
                            <td class="my-1">{{ $non_filer->businessLocation->taxRegion->name ?? 'N/A' }}</td>
                        </tr>
                        @if($non_filer->businessLocation->date_of_commencing)
                            <tr>
                                <td class="font-weight-bold text-uppercase">Date of Business Commencement</td>
                                <td class="my-1">{{date('D, Y-m-d',strtotime($non_filer->businessLocation->date_of_commencing)) }}</td>
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
                            <td class="my-1">{{$non_filer->taxType->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Phone</td>
                            <td class="my-1">{{ $non_filer->business->mobile ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-uppercase">Email</td>
                            <td class="my-1">{{ $non_filer->business->email ?? 'N/A' }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection