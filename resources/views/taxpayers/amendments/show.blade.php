@extends('layouts.master')

@section('title')
    Taxpayer Details Amendment Request
@endsection

@section('content')
<livewire:taxpayers.details-amendment-request-show id='{{$id}}'></livewire:taxpayers.details-amendment-request-show>
@endsection