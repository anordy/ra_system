@extends('layouts.master')

@section('title', 'List Motor Vehicle De-registration')

@section('content')

    <div class="card p-0 m-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <div class="text-uppercase font-weight-bold">{{ __('Motor Vehicle DeRegistrations') }}</div>
        </div>
        <div class="card-body mt-0 p-2">
            <livewire:mvr.deregistration.deregistrations-table></livewire:mvr.deregistration.deregistrations-table>
        </div>
    </div>

@endsection