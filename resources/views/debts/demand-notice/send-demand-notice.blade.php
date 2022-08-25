@extends('layouts.master')

@section('title', 'Send Demand Notice')

@section('content')
    <livewire:debt.demand-notice.send-demand-notice debtId="{{ $debtId }}" />
@endsection
