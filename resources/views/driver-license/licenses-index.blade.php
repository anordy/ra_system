@extends('layouts.master')

@section('title', 'Driver\'s License')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h5>Licenses</h5>
            <div class="card-tools">

            </div>
        </div>

        <div class="card-body">


            <livewire:drivers-license.licenses-table :status="null" />


        </div>
    </div>
@endsection
