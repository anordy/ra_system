@extends('layouts.master')

@section('title','View Transport Service Payment')

@section('content')

    <livewire:public-service.public-service-payment :return="$return" />

    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            {{ __('View Transport Service Payment') }}
        </div>

        <div class="card-body">
            <div class="row m-3 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Plate Number</span>
                    <p class="my-1">{{ $return->motor->mvr->plate_number ?? 'N/A' }}</p>
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

        </div>
    </div>
@endsection