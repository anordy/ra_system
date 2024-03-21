@extends('layouts.master')

@section('title','Public Service Payments')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            {{ __('Public Service Payments') }}
        </div>

        <div class="card-body">
            <livewire:public-service.public-service-payments-table />
        </div>
    </div>
@endsection