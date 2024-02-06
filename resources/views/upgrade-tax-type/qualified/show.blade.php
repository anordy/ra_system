@extends('layouts.master')

@section('title','Qualified Tax Type')

@section('css')
    <style>
        .table td, .table th {
            border-top: none;
        }

    </style>
@endsection
@section('content')
    @livewire('upgrade-tax-type.qualified.show', ['return' => $return, 'sales' => $sales, 'currency' => $currency])
@endsection