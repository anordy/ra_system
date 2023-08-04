@extends('layouts.bare')

@section('title', 'Security Questions')

@section('content')
    @livewire('account.security-questions', ['pre' => true])
@endsection