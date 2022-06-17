@extends('layouts.master')

@section('title', 'Taxpayers')

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <livewire:taxpayers.taxpayers-table />
        </div>
    </div>
@endsection