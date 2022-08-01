@extends('layouts.master')

@section('title','Certificate of Quantity')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('returns.petroleum.petroleum-return-table')
        </div>
    </div>
@endsection