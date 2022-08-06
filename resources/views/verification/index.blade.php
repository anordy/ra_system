@extends('layouts.master')

@section('title', 'Verifications')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Return Verifications
        </div>
        <div class="card-body">
            @livewire('verification.verification-table')
        </div>
    </div>
@endsection
