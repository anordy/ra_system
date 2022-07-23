@extends('layouts.master')

@section('title')
Audit Trail
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="text-uppercase font-weight-bold">Audit Logs</div>
        </div>

        <div class="card-body">
            @livewire('audit-log-table')
        </div>
    </div>
@endsection
