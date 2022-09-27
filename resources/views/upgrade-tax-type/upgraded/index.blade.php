@extends('layouts.master')

@section('title','Upgraded Tax Types')

@section('css')
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <livewire:upgrade-tax-type.upgraded-tax-type-table />
        </div>
    </div>
@endsection