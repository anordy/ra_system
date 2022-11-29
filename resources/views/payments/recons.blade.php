@extends('layouts.master')

@section('title', 'Reconciliations')


@section('content')

    <livewire:payments.recon-status :recon="$recon"></livewire:payments.recon-status>

    <div class="card rounded-0">
        <div class="card-body m-4 p-2">
            <livewire:payments.recon-table recon_id="{{ $recon->id }}" />
        </div>
    </div>
@endsection
