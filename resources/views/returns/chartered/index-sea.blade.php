@extends('layouts.master')

@section('title',__('Chartered Returns History'))

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('returns.chartered.sea-returns-table')
        </div>
    </div>
@endsection