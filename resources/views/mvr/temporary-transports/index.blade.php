@extends('layouts.master')

@section('title', 'Motor Vehicle Temporary Transportation')

@section('content')

    <div class="card p-0 m-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <div class="text-uppercase font-weight-bold">{{ __('Motor Vehicle Temporary Transportation') }}</div>
        </div>
        <div class="card-body mt-0 p-2">
            <livewire:mvr.temporary-transport.temporary-transports-table></livewire:mvr.temporary-transport.temporary-transports-table>
        </div>
    </div>

@endsection