@extends('layouts.master')

@section('title')
Audit Trail
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Audit Logs</h5>
        </div>

        <div class="card-body">
            @livewire('audit-log-table')
        </div>
    </div>
@endsection
