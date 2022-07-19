@extends('layouts.master')

@section('title')
    View Changes
@endsection

@section('content')
    @livewire('business.updates.show-changes', ['businessId' => $businessId])
@endsection
