@extends('layouts.master')

@section('title', 'Reliefs Report')

@section('content')
    <div class="d-flex justify-content-start mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-info">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
    <div class="shadow rounded">
        <div class="card pt-2">
            <div class="card-header text-uppercase font-weight-bold bg-grey ">
                Report Preview
            </div>
            <div class="card-body">
                @livewire('relief.relief-report-table',['payload'=>$payload])
            </div>
        </div>
    </div>
    <div class="shadow rounded">
        <div class="card pt-2">
            <div class="card-header text-uppercase font-weight-bold bg-grey ">
                Report Summary
            </div>
            <div class="card-body">
                @livewire('relief.relief-report-summary',['payload'=>$payload])
            </div>
        </div>
    </div>
@endsection
