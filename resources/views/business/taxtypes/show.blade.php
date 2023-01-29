@extends('layouts.master')

@section('title')
    Change of Tax Type Request
@endsection

@section('content')
    @livewire('business.tax-type.tax-type-change-approve', ['taxchangeId' => encrypt($taxchange->id)])
@endsection
