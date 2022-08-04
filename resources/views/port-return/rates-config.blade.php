@extends('layouts.master')

@section('title')
    Port Return
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Rates configuration</h5>
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                        onclick="Livewire.emit('showModal', 'port-tax-return.rates-modal')"><i
                            class="fa fa-plus-circle"></i>
                    Add</button>
            </div>
        </div>

        <div class="card-body">
            @livewire('port-tax-return.rates-table')
        </div>
    </div>
@endsection

@section('scripts')

@endsection