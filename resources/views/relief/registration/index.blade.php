@extends('layouts.master')

@section('title','Reliefs Registrations')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('relief.relief-registrations')
        </div>
    </div>
@endsection