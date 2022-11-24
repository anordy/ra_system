@extends('layouts.master')

@section('title', 'Payment Summary')


@section('content')


    <div class="card rounded-0">
        <div class="card-header font-weight-bold text-uppercase">
            Request Reconciliation
        </div>
        <div class="card-body">
            <livewire:payments.request-recon></livewire:payments.request-recon>
        </div>
    </div>

    <div class="card rounded-0">
            <div class="card-header font-weight-bold text-uppercase">
                Reconciliation Enquiries
            </div>
            <div class="card-body">
                <livewire:payments.recon-enquiries-table />
            </div>
    </div>

@endsection
