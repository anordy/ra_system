@extends('layouts.master')

@section('title','Petroleum Returns History')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])
            @livewire('returns.petroleum.petroleum-return-table')
        </div>
    </div>
@endsection