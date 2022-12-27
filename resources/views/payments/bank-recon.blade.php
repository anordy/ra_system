@extends('layouts.master')

@section('title', 'Bank Reconciliations')

@section('content')

    <livewire:payments.bank-recon-import />

    <div class="card rounded-0">
        <div class="card-header font-weight-bold text-uppercase bg-white">
            Bank Reconciliations
        </div>
        <div class="card-body">
{{--            <livewire:payments.recon-enquiries-table />--}}
        </div>
    </div>
@endsection
