@extends('layouts.master')

@section('title','Petroleum Returns History')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('returns.return-card-report', ['data' => $data])
            @livewire('returns.petroleum.petroleum-return-table')
        </div>
    </div>
@endsection