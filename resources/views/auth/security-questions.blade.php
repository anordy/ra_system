@extends('layouts.login')
@section('title')
    {{__('Reference No. Recovery Security Questions') }}
@endsection
@php($messages = false)
@section('styles')
    <style nonce="custom_style">
        .body-color {
            background-color: rgb(255 255 255 / 94%);
        }

        .flex-1 {
            flex: 1;
        }
    </style>
    @livewireStyles(['nonce'=>'custom_style'])
@endsection
@section('content')
    <div class="row justify-content-center mx-3">
        <div class="middle-box middle-box-wd">
            <livewire:auth.questions-validation></livewire:auth.questions-validation>
        </div>
    </div>
@endsection

@section('scripts')
    @livewireScripts(['nonce'=>'custom_script'])
@endsection