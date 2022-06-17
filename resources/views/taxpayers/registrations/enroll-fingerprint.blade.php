@extends('layouts.master')

@section('title', 'Taxpayer Registration Details')

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <livewire:taxpayers.enroll-fingerprint :kyc="$kyc"></livewire:taxpayers.enroll-fingerprint>
        </div>
    </div>
@endsection