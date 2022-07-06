@extends('layouts.master')

@section('title', 'Business Registration Details')

@section('content')
    <div class="mt-3">
        @include('business.registrations.includes.business_info')
    </div>
@endsection
