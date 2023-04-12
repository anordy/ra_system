@extends('layouts.master')

@section('title', 'Tax Returns Vetting')

@section('content')

    <div class="card rounded-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            On Correction Tax Returns Vetting
        </div>

        <div class="card-body">
            <livewire:vetting.vetting-approval-table vettingStatus="{{ \App\Enum\VettingStatus::CORRECTION }}" />
        </div>
    </div>
@endsection
