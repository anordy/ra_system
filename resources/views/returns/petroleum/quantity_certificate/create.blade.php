@extends('layouts.master')

@section('title', 'Certificate of Quantity')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Certificate of Quantity Generate
           
        </div>
        <div class="card-body">
            @livewire('returns.petroleum.quantity-certificate-add')
        </div>
    </div>
@endsection
