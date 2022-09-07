@extends('layouts.master')

@section('css')
@endsection

@section('title','Returns Taxtypes')

@section('content')
    <div class="card">
        <div class="card-body">
            <livewire:returns.return-tax-types-table/>
        </div>
    </div>
@endsection

@section('scripts')

@endsection