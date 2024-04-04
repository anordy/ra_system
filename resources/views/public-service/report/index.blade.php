@extends('layouts.master')

@section('title', 'Public Service Reports')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h5>Public Service Report</h5>
        </div>

        <livewire:reports.public-service.public-service-report />
    </div>
@endsection

