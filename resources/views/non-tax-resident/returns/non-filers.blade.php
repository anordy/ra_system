@extends('layouts.master')

@section('title', 'List of Non Filers')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            List of Non Filers
        </div>
        <div class="card-body">
            <livewire:non-tax-resident.returns.non-filers-table />
        </div>
    </div>
@endsection
