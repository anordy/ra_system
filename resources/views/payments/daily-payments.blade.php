@extends('layouts.master')

@section('title', 'Daily Receipts')

@section('content')
    <div class="card rounded-0">
        @livewire('payments.daily-payments')
    </div>
@endsection
