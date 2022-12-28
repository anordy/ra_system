@extends('layouts.master')

@section('title')
Audit Trail
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-center">
                <img src="{{ asset('images/logo.jpg') }}" width="30" alt="ZRB Logo">
            </div>
            <div class="text-uppercase font-weight-bold">Audit Logs</div>
        </div>

        <div class="card-body">
            @livewire('audit-log-table')
        </div>
    </div>
@endsection
