@extends('layouts.master')

@section('title', 'General Reports')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold">
            Filter Reports
        </div>
        <div class="card-body mt-0">
            @livewire('reports.general.initial')
        </div>
    </div>

@endsection
