@extends('layouts.master')

@section('title')
    Vat Return
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h6>Vat Return Requests</h6>
        </div>
        <div class="card-body">
            <livewire:vat-return.requests-table year="{{$year}}" month="{{$month}}" />
        </div>
    </div>
@endsection

@section('scripts')

@endsection