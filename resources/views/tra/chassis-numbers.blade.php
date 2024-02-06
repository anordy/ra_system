@extends('layouts.master')
@section('title', 'Chassis Numbers Information')
@section('content')
    <div class="card">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Chassis Numbers Information
        </div>
        <div class="card-body">
            @livewire('tra.chassis-numbers-table')
        </div>
    </div>
@endsection
