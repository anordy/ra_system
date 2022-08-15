@extends('layouts.master')

@section('title', 'Return Verification')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Returns Verified With Assessments
        </div>
        <div class="card-body">
            @livewire('verification.verification-assessment-table')
        </div>
    </div>
@endsection
