@extends('layouts.master')
@section('title', 'VFDMS Receipts Information')
@section('content')
    <div class="card">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            VFDMS Receipts Information
        </div>
        <div class="card-body">
            @livewire('tra.vfdms-receipts-table')
        </div>
    </div>
@endsection
