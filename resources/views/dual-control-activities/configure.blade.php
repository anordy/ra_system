@extends('layouts.master')

@section('title')
    Dual Control Configuration
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Configuration for dual control</h5>
        </div>

        <div class="card-body">
            <livewire:settings.dual-control-activity.configuration />
        </div>
    </div>
@endsection

