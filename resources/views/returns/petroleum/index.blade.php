@extends('layouts.master')

@section('title', 'Taxpayers')

@section('content')
    <div class="card mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Petroleum Return
        </div>
        <div class="card-body">
            <livewire:returns.petroleum.petroleum-return />
        </div>
    </div>
@endsection