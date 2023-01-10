@extends('layouts.master')

@section('title')
    KYC Details Amendment Request
@endsection

@section('content')
<livewire:kyc.kyc-amendment-request-show id='{{$id}}'></livewire:kyc.kyc-amendment-request-show>
@endsection