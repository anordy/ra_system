@extends('layouts.master')

@section('title', 'Land Lease')

@section('content')
    @livewire('relief.relief-view', ['enc_id' => $id])
@endsection
