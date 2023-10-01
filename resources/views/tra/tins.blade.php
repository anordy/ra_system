@extends('layouts.master')
@section('title', 'TINs Information')
@section('content')
    <div class="card">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            TINs Information
        </div>
        <div class="card-body">
            @livewire('tra.tins-table')
        </div>
    </div>
@endsection
