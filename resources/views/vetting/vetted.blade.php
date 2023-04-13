@extends('layouts.master')

@section('title', 'Tax Returns Vetting')

@section('content')

    <div class="card rounded-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Returns Vetting
        </div>

        <div class="card-body">
            <livewire:vetting.vetting-approval-table vettingStatus="{{ \App\Enum\VettingStatus::VETTED }}" />
        </div>
    </div>
@endsection
