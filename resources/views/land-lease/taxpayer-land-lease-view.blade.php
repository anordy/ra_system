@extends('layouts.master')

@section('title', 'Land Lease')

@section('content')
    @livewire('land-lease.taxpayer-land-lease-view', ['enc_id' => $id])
@endsection
