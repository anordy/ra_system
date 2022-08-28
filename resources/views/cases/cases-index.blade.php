@extends('layouts.master')

@section('title', 'Legal Cases Management')

@section('content')
    <div class="card mt-3">
        <div class="card-header bg-white">
            <h5>Cases</h5>
            <div class="card-tools">
            </div>
        </div>

        <div class="card-body">
            <livewire:cases.cases-table />
        </div>
    </div>
@endsection

