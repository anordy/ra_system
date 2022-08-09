@extends('layouts.master')

@section('title', 'Edit Land Lease')

@section('content')
    @livewire('relief.relief-edit', ['enc_id' => $id])
@endsection
