@extends('layouts.master')

@section('title', 'BFO Excise Duty Return')

@section('content')
    <div class="card mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            BFO Excise Duty Return
        </div>
        
        {{-- <livewire:returns.return-card-report ['return_id' => $returnId] /> --}}
        @livewire('returns.return-card-report', ['data' => $data])
        <div class="card-body">
            <livewire:returns.bfo-excise-duty.bfo-excise-duty-table />
        </div>
    </div>
@endsection