@extends('layouts.master')

@section('title', 'Taxpayers')

@section('content')
    <div class="card mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Taxpayers List
        </div>
        <div class="card-body">
            <livewire:taxpayers.taxpayers-table />
        </div>
    </div>
@endsection