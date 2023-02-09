@extends('layouts.master')

@section('title')
    Tax Types
@endsection

@section('content')
    <div class="card">
        <div class="card-header font-weight-bold text-uppercase bg-white">
            Tax Types
        </div>

        <div class="card-body">
            @livewire('tax-types-table')
        </div>
    </div>
@endsection
