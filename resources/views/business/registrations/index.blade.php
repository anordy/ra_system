@extends('layouts.master')

@section('title')
    Business
@endsection

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold">
            Registered Businesses
        </div>

        <div class="card-body">
            @livewire('business.registrations-table')
        </div>
    </div>
@endsection
