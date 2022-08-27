@extends('layouts.master')

@section('title', 'Road Inspection Offences')

@section('content')
    <div class="card mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
             Register Offence
        </div>
        <div class="card-body">
            <livewire:road-inspection-offence.register-create />
        </div>
    </div>
@endsection