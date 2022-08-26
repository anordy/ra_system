@extends('layouts.master')

@section('title', 'Legal Cases Management')

@section('content')
    <div class="card mt-3">
        <div class="card-header bg-white">
            <h5>Appeals</h5>
            <div class="card-tools">
            </div>
        </div>

        <div class="card-body">
            <livewire:cases.appeals-table />
        </div>
    </div>
@endsection

