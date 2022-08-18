@extends('layouts.master')

@section('title', 'Audits Debt Management')

@section('content')
<div class="card p-0 m-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="text-uppercase font-weight-bold">Audit Debts</div>
        <div class="card-tools">

        </div>
    </div>
    <div class="card-body mt-0 p-2">
        <livewire:debt.assessment-debts-table />
    </div>
</div>
@endsection
