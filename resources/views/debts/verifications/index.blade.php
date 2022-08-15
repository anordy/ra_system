@extends('layouts.master')

@section('title', 'Assesments Debt Management')

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            Verification Assesments Debt Management
        </div>
        <div class="card-body mt-0 p-2">
            <livewire:debt.assesments-table />
        </div>
    </div>
@endsection
