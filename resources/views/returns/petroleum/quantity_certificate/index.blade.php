@extends('layouts.master')

@section('title', 'Certificate of Quantity')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Certificate of Quantity
            <div class="card-tools" style="margin-top: -8px;">
                <a href="{{ route('petroleum.certificateOfQuantity.create') }}" class="btn btn-sm btn-info white">
                    <div class="text-white">Generate Certificate</div>
                </a>
            </div>
        </div>
        <div class="card-body">
            @livewire('returns.petroleum.quantity-certificate-table')
        </div>
    </div>
@endsection
