@extends('layouts.master')

@section('title', 'Missing Bank Reconciliations')

@section('content')
    <div class="card rounded-0">
        <div class="card-header font-weight-bold text-uppercase bg-white">
            Missing Bank Reconciliations
        </div>
        <div class="card-body">
            <livewire:payments.missing-bank-recon-filter />
        </div>
    </div>
@endsection
