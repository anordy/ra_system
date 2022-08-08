@extends('layouts.master')

@section('title', 'Investigations Approvals')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Investigations Approvals
        </div>
        <div class="card-body">
            @livewire('investigation.tax-investigation-approval-table')
        </div>
    </div>
@endsection
