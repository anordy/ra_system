@extends('layouts.master')

@section('title')
    Notifications
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('notification.notifications-table')
        </div>
    </div>
@endsection
