@extends('layouts.master')

@section('title')
    Dual Control Activities
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Activities for dual control</h5>
        </div>

        <div class="card-body">
            @livewire('settings.dual-control-activity.activity-table')
        </div>
    </div>
@endsection
