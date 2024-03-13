@extends('layouts.master')

@section('title', 'Taxpayer Registration Details')

@push('styles')
    <link href="{{ asset('/css/registration.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <livewire:taxpayers.enroll-fingerprint :kyc="$kyc"></livewire:taxpayers.enroll-fingerprint>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/registration.js') }}"></script>
@endpush