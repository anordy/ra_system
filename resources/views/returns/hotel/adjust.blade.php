@extends('layouts.master')

@section('title','Adjust Hotel Returns')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('returns.hotel.adjust', ['return_id' => $returnId])
        </div>
    </div>
@endsection