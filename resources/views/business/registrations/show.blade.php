@extends('layouts.master')

@section('title', 'Business Registration Details')

@section('content')
    <div class="mt-3">

    <div class="d-flex justify-content-end mb-3">
        <a target="_blank" href="{{ route('business.certificate', encrypt($business->id)) }}" class="btn btn-success btn-sm mt-1">
            <i class="bi bi-patch-check mr-1"></i> Certificate of Registration
        </a>
    </div>

        @include('business.registrations.includes.business_info')
    </div>
@endsection
