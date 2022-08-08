@extends('layouts.master')

@section('title','Electronic Money Transaction')

@section('content')
    <div class="card">
        <div class="card-body">
            <livewire:returns.em-transaction.em-transactions-table />
        </div>
    </div>
@endsection