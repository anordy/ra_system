@extends('layouts.master')

@section('title', 'Request for Extension')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Extensions Requests
        </div>
        <div class="card-body">
            <livewire:extension.extensions-table />
        </div>
    </div>
@endsection