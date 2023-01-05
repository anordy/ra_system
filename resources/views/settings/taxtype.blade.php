@extends('layouts.master')

@section('title')
    Tax Types
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Tax Types</h5>
        </div>

        <div class="card-body">
            @livewire('tax-types-table')
        </div>
    </div>
@endsection
