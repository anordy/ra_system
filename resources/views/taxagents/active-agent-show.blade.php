@extends('layouts.master')

@section('title', 'Tax Consultants')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h6>Registration Details</h6>
                <a class="btn btn-info" href="{{route('taxagents.active')}}">Back</a>
            </div>
        </div>

        <div class="card-body">
            @include('taxagents.includes.show')
        </div>
    </div>

@endsection