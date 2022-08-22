@extends('layouts.master')

@section('title', 'Transport Agent Registration')

@section('content')
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="card m-3">

                <div class="card-body">
                    <h3 class="m-3">Agent Details</h3>
                    <livewire:mvr.agent-registration />
                </div>
            </div>
        </div>
    </div>
@endsection

