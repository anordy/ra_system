@extends('layouts.master')

@section('title', 'View Request')

@section('content')
    <div class="row mx-1">
        <div class="col-md-12">
            <livewire:tax-clearance.tax-clearance-request :business_location_id="$business_location_id" />
        </div>
    </div>
@endsection