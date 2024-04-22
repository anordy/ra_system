@extends('layouts.master')

@section('title', 'Driver\'s License')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            Licenses
        </div>
        <div class="card-body">
            <livewire:drivers-license.licenses-table :status="null" />
        </div>
    </div>
@endsection
