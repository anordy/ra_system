@extends('layouts.master')

@section('title', 'Reconciliations')


@section('content')

    <div class="card rounded-0">
        <div class="card-header font-weight-bold text-uppercase">
            Reconciliation Information
        </div>

        <div class="row m-4">
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Reconciliation As On</span>
                <p class="my-1">{{ $recon->TnxDt }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Reconciliation Type</span>
                <p class="my-1"> {{ $recon->ReconcOpt == 1 ? 'Successful Trasactions' : 'Failed Transactions' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Reconciliation Enquired On</span>
                <p class="my-1">{{ $recon->created_at }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Status</span>
                <p class="my-1">{{ $reconStatus }}</p>
            </div>
        </div>

    </div>
    <div class="card rounded-0">
        <div class="card-body m-4 p-2">
            <livewire:payments.recon-table recon_id="{{ $recon->id }}" />
        </div>
    </div>
@endsection
