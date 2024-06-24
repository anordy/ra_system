@extends('layouts.master')

@section('title', 'Complete Land Lease Registration')

@section('content')
    @livewire('land-lease.land-lease-complete-registration', ['enc_id' => $id])
@endsection
