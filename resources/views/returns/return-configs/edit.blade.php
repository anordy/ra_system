@extends('layouts.master')

@section('title','Returns Configurations Edit')

@section('content')
    <div class="d-flex justify-content-end pb-2">
        <a class="btn btn-info" href="{{ route('settings.return-config.show', encrypt($taxtype_id)) }}">
            <i class="bi bi-arrow-return-left mr-2"></i>
            Back
        </a>
    </div>
    <div class="card">
        <div class="card-header bg-white text-uppercase font-weight-bold">
            editing {{$code}} return configurations
        </div>
        <div class="card-body">
            <livewire:returns.edit-return-config config_id="{{$config_id}}" taxtype_id="{{$taxtype_id}}"/>
        </div>
    </div>
@endsection

@section('scripts')

@endsection