@extends('layouts.master')

@section('title')
    Business
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Registered Businesses</h5>
            <div class="card-tools">
                
            </div>
        </div>

        <div class="card-body">
            @livewire('business.registrations-table')
        </div>
    </div>
@endsection
