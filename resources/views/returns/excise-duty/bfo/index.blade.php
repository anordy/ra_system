@extends('layouts.master')

@section('title', 'Taxpayers')

@section('content')
    <div class="card mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Bfo Excise Duty Return
        </div>
        <div class="card-body">
            <livewire:returns.bfo-excise-duty.bfo-excise-duty-table />
        </div>
    </div>
@endsection