@extends('layouts.master')

@section('title', 'Taxpayers')

@section('content')
    <div class="card mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Stamp Duty Return
        </div>
        <div class="card-body">
            <livewire:returns.stamp-duty.stamp-duty-return />
        </div>
    </div>
@endsection