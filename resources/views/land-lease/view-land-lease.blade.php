@extends('layouts.master')

@section('title', 'Land Lease')

@section('content')
    @livewire('land-lease.land-lease-view', ['enc_id' => $id])
@endsection
