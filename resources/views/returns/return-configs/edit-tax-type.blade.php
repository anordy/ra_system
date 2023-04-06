@extends('layouts.master')

@section('title','Returns Configurations Edit')

@section('content')
    <div class="d-flex justify-content-end pb-2">
        <a class="btn btn-info" href="{{ route('settings.return-config.index')}}">
            <i class="bi bi-arrow-return-left mr-2"></i>
            Back
        </a>
    </div>
    <div class="card">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            editing return tax types
        </div>
        <div class="card-body">
            <livewire:returns.edit-return-tax-type taxtype_id="{{$id}}"/>
        </div>
    </div>
@endsection

@section('scripts')

@endsection