@extends('layouts.master')

@section('title', 'View Debt')

@section('content')
    <nav class="nav nav-tabs mt-0 border-top-0">
        <a href="#tab1" class="nav-item nav-link active">Debt Details</a>
        <a href="#tab2" class="nav-item nav-link">Business Information</a>
        <a href="#tab3" class="nav-item nav-link">Transport Service Details</a>
        <a href="#tab4" class="nav-item nav-link">Motor Vehicle Registration Details</a>
    </nav>

    <div class="tab-content px-2 card pt-3 pb-2">
        <div id="tab1" class="tab-pane fade active show m-4">
            <livewire:public-service.public-service-payment :return="$return" />
            <div class="card">
                <div class="card-header text-uppercase font-weight-bold bg-white">
                    {{ __('View Transport Service Payment') }}
                </div>
                <div class="card-body">
                    <div class="row m-3 pt-3">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Registration Number</span>
                            <p class="my-1">{{ $return->motor->mvr->plate_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Serial Number</span>
                            <p class="my-1">{{ $return->motor->mvr->registration_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Registration Type</span>
                            <p class="my-1">{{ $return->motor->mvr->regtype->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Vehicle Class</span>
                            <p class="my-1">{{ $return->motor->mvr->class->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Start Date</span>
                            <p class="my-1">{{ \Carbon\Carbon::create($return->start_date)->format('d M Y') ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">End Date</span>
                            <p class="my-1">{{ \Carbon\Carbon::create($return->end_date)->format('d M Y') ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Total Amount</span>
                            <p class="my-1">{{ $return->currency }} {{ number_format($return->amount ?? 0, 2) }}</p>
                        </div>
                        <div class="col-md-12 mt-3">
                            <p class="font-weight-bold text-uppercase mb-2">Penalties and Interests</p>
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <th>S/N</th>
                                    <th>Financial Month</th>
                                    <th>Principal</th>
                                    <th>Interest</th>
                                    <th>Total Amount</th>
                                    <th>Due Date</th>
                                </thead>
                                <tbody>
                                @foreach($return->interests as $interest)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $interest->financialMonth->name }}</td>
                                        <td>{{ number_format($interest->principal, 2) }}</td>
                                        <td>{{ number_format($interest->interest, 2) }}</td>
                                        <td>{{ number_format($interest->amount) }}</td>
                                        <td>{{ $interest->payment_date }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
        </div>
        <div id="tab2" class="tab-pane fade m-4">
            @include('debts.transports.includes.business-info', ['business' => $return->business])
        </div>
        <div id="tab3" class="tab-pane fade  m-4">
            @include('public-service.includes.info', ['registration' => $return->motor])
        </div>
        <div id="tab4" class="tab-pane fade m-4">
            @include('mvr.registration.reg_info', ['reg' => $return->motor->mvr])
        </div>
    </div>
@endsection
