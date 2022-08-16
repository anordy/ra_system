@extends('layouts.master')

@section('title','Port Return')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('returns.port.port-filing-return',['business_location_id' => $business_location_id,'tax_type_id' => $tax_type_id, 'filling_month_id' => $filling_month_id])
        </div>
    </div>
@endsection