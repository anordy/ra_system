@extends('layouts.master')

@section('title', 'View Lease Registration')

@section('content')
    @livewire('land-lease.land-lease-register-view', ['enc_id' => $id])
@endsection
