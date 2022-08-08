@extends('layouts.master')

@section('title', 'Airport Service Return')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('returns.port.port-filing-edit-return', ['return' => $return])
        </div>
    </div>
@endsection
