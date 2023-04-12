@extends('layouts.master')

@section('title', 'Corrected Tax Returns Vetting')

@section('content')

    <div class="card rounded-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Corrected Tax Returns Vetting
        </div>

        <div class="card-body">
            <livewire:vetting.vetting-approval-table vettingStatus="{{ \App\Enum\VettingStatus::CORRECTED }}" />
        </div>
    </div>
@endsection
