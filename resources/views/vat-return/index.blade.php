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
            <p>Select criteria below to view requests</p>

            <livewire:vat-return.index/>

        </div>
    </div>
@endsection

@section('scripts')

@endsection