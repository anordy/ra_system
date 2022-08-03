@extends('layouts.master')

@section('title','Waiver')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('assesments.waiver.waiver-table')
        </div>
    </div>
@endsection