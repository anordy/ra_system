@extends('layouts.master')

@section('title')
    Interest Rates
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Interest Rates</h5>
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                onclick="Livewire.emit('showModal', 'settings.interest-rate.interest-rate-add-modal')"><i
                    class="fa fa-plus-circle"></i>
                Add</button>
            </div>
        </div>

        <div class="card-body">
            @livewire('settings.interest-rate.interest-rates-table')
        </div>
    </div>
@endsection
