@extends('layouts.master')

@section('title','File Petroleum Returns')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('returns.petroleum.filing-return', ['location' => $location, 'tax_type' => $tax_type, 'business' => $business])
        </div>
    </div>
@endsection