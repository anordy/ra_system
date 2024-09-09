@extends('layouts.master')

@section('title','Account')

@section('content')
    @livewire('account.account-details')
    @livewire('account.change-password')
@endsection