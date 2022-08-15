@extends('layouts.master')

@section('title', 'Taxpayers')

@section('content')
    <div class="card mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Mobile Money Transfer Return
        </div>
        @livewire('returns.return-card-report', ['data' => $data])
        <div class="card-body">
            <livewire:returns.excise-duty.mobile-money-transfer-table />
        </div>
    </div>
@endsection