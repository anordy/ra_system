@extends('layouts.master')

@section('title')
    Returns Configuration
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Returns Configuration</h5>
        </div>

        <div class="card-body">
            <div class="list-group list-group-flush">
                <a href="{{ route('settings.returns.hotel') }}" class="list-group-item list-group-item-action">1. Hotel Levy Config</a>
                <a href="#" class="list-group-item list-group-item-action">2. Other</a>
                <a href="#" class="list-group-item list-group-item-action">3. Others</a>
              </div>
        </div>
    </div>
@endsection
