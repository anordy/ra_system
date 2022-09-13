@extends('layouts.master')

@section('title','Returns Configurations Create')

@section('content')
    <div class="d-flex justify-content-end pb-2">
        <a class="btn btn-info" href="{{ route('settings.return-config.show', encrypt($taxtype_id)) }}">
            <i class="bi bi-arrow-return-left mr-2"></i>
            Back
        </a>
    </div>
    <div class="card">
        <div class="card-header ">
            <h6 class="text-capitalize">{{$code}} return configurations</h6>
        </div>
        <div class="card-body">

            <livewire:returns.add-return-config code="{{$code}}" taxtype_id="{{$taxtype_id}}"/>
        </div>
    </div>
@endsection

@section('scripts')

@endsection