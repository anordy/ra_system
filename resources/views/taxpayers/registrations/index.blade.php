@extends('layouts.master')

@section('title', 'Taxpayers Registrations')

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <livewire:taxpayers.registrations-table />
        </div>
    </div>
@endsection