@extends('layouts.master')

@section('title', 'Petroleum Returns History')

@section('content')
    <div class="card">
        <div class="card-body">
            <livewire:returns.petroleum.petroleum-card-one />
            <livewire:returns.petroleum.petroleum-card-two />
            @livewire('returns.return-filter', ['tablename' => $tableName, 'cardOne' => $cardOne, 'cardTwo' => $cardTwo]) <br>
            @livewire('returns.petroleum.petroleum-return-table')
        </div>
    </div>
@endsection
