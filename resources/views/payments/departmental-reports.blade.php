@extends('layouts.master')

@section('title', 'Departmental Reports')

@section('content')
    <div class="card rounded-0">
        <div class="card-header font-weight-bold text-uppercase bg-white">
            Departmental Reports
        </div>
        <div class="card-body">
            @livewire('payments.departmental-report-filter')
        </div>
    </div>

@endsection
