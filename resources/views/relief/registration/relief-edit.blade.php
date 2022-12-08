@extends('layouts.master')

@section('title', 'Edit Relief')

@section('content')
    @livewire('relief.relief-edit', ['enc_id' => $id])
@endsection
