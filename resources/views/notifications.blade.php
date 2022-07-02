@extends('layouts.master')

@section('title')
    Notifications
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Notifications</h5>
            <div class="card-tools">
            
            </div>
        </div>

        <div class="card-body">
            @livewire('notifications-table')
        </div>
    </div>
@endsection
