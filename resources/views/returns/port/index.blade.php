@extends('layouts.master')

@section('title','Port Return')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('returns.port.port-return-table')
        </div>
    </div>
@endsection