@extends('layouts.master')

@section('title', 'Land Lease Payment')

@section('content')
    @livewire('land-lease.view-lease-payment', ['enc_id' => $id])
@endsection
