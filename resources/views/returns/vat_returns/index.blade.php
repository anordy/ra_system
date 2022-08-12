@extends('layouts.master')

@section('title', 'VAT Tax Returns')

@section('content')
    <div class="card mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            VAT Return
        </div>
        {{-- @livewire('returns.return-card-report', ['data' => $data]) --}}
        <div class="card-body">
            <livewire:returns.vat.vat-return-table/>
        </div>
    </div>
@endsection