@extends('layouts.master')

@section('title', 'Tax Clearance Request')

@section('content')
<div class="card p-0 m-0 mb-3">
    <div class="card-header text-uppercase">
        Tax Clearance Request
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <livewire:tax-clearance.tax-clearance-request-table />
    </div>
</div>
@endsection