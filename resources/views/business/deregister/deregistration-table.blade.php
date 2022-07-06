@extends('layouts.master')

@section('title','Business De-registrations History')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="text-uppercase">Business De-registrations</h5>
        </div>
        <div class="card-body">
            @livewire('business.deregister.deregister-business-table')
        </div>
    </div>
@endsection