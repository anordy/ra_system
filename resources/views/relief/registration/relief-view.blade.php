@extends('layouts.master')

@section('title', 'Relief')

@section('content')
    @livewire('relief.relief-view', ['enc_id' => encrypt($id)])
@endsection
