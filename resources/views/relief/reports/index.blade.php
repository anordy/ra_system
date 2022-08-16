@extends('layouts.master')

@section('title','Reliefs Report')

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- This is the relief report page --}}
            @livewire('relief.relief-generate-report')
        </div>
    </div>
@endsection