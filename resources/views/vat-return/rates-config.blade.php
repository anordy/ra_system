@extends('layouts.master')

@section('title')
    Vat Return
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Rates configuration</h5>
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                        onclick="Livewire.emit('showModal', 'vat-return.rates-modal')"><i
                            class="bi bi-plus-circle-fill"></i>
                    Add</button>
            </div>
        </div>

        <div class="card-body">
            @livewire('vat-return.rates-table')
        </div>
    </div>
@endsection

@section('scripts')

@endsection