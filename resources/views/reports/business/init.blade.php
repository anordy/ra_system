@extends('layouts.master')

@section('title','Managerial Reports')

@section('content')
<div class="card">
    <div class="card-body mt-0">
        @livewire('reports.business.init')
    </div>
</div>
@endsection
