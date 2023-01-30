@extends('layouts.master')

@section('title', 'eGA Charges')

@section('content')
    <div class="card rounded-0">
        <div class="card-header font-weight-bold text-uppercase bg-white">
            eGA Charges
        </div>
        <div class="card-body">
            @livewire('payments.ega-charges-filter')
        </div>
    </div>

@endsection
