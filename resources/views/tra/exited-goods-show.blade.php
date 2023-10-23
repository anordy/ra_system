@extends('layouts.master')
@section('title', 'Exited Goods Information')
@section('content')
    <div class="card">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Exited Goods Information
        </div>
        <div class="card-body">
            @livewire('tra.exited-goods-table')
        </div>
    </div>
@endsection
