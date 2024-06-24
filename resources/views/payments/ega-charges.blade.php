@extends('layouts.master')

@section('title', 'eGAZ Charges')

@section('content')
    <div class="card rounded-0">
        <div class="card-header font-weight-bold text-uppercase bg-white">
            eGAZ Charges
        </div>
        <div class="card-body">
            @livewire('payments.ega-charges-filter')
        </div>
    </div>

@endsection
