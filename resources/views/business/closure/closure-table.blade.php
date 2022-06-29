@extends('layouts.master')

@section('title')
    Business Closures
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Business Closures</h5>
        </div>

        <div class="card-body">
            @livewire('business.closure.closure-table')
        </div>
    </div>
@endsection


