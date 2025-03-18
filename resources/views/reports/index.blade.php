@extends('layouts.master')

@section('title','Reports')

@section('content')
<div class="card">
    <div class="card-body mt-0">
        @livewire('reports.init')
    </div>
</div>
@endsection
