@extends('layouts.master')

@section('title','Reliefs Applications')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire("relief.relief-list")
        </div>
    </div>
@endsection