@extends('layouts.master')

@section('title','File Petroleum Returns')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('returns.petroleum.filing-return-edit', ['return' => $return])
        </div>
    </div>
@endsection