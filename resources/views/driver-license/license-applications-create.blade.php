@extends('layouts.master')

@section('title', 'Driver\'s License')

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <livewire:drivers-license.wizard.wizard id="sm" >
        </div>
    </div>
@endsection

