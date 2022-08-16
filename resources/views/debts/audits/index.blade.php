@extends('layouts.master')

@section('title', 'Audits Debt Management')

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            Audits Debt Management
        </div>
        <div class="card-body mt-0 p-2">
            <livewire:debt.audits-table  />
        </div>
    </div>
@endsection
