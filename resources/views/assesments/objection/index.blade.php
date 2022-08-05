@extends('layouts.master')

@section('title','Objection')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('assesments.objection.objection-table')
        </div>
    </div>
@endsection