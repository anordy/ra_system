@extends('layouts.master')

@section('title', 'Return Verification')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            All Tax Returns Verified
        </div>
        <div class="card-body">
            @livewire('returns.verification-filter', ['tablename' => $tableName]) <br>
            @livewire('verification.verification-verified-table')
        </div>
    </div>
@endsection
