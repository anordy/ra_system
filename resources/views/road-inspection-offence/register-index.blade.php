@extends('layouts.master')

@section('title', 'Road Inspection Offences')

@section('content')
    <div class="card mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
             List Of Offences
            <div class="card-tools">
                <a href="{{route('rio.register.create')}}">
                    <button class="btn btn-info btn-sm"><i class="bi bi-plus-circle-fill"></i>New Offence</button>
                </a>
            </div>
        </div>
        <div class="card-body">
            <livewire:road-inspection-offence.register-table />
        </div>
    </div>
@endsection