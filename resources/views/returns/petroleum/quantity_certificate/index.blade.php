@extends('layouts.master')

@section('title', 'Certificate of Quantity')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Certificate of Quantity
            @can('certificate-of-quantity-create')
                <div class="card-tools">
                    <a href="{{ route('petroleum.certificateOfQuantity.create') }}" class="btn btn-sm btn-info white">
                        <div class="text-white">Generate Certificate</div>
                    </a>
                </div>
            @endcan
        </div>
        <div class="card-body">
            @livewire('returns.petroleum.quantity-certificate-table')
        </div>
    </div>
@endsection
