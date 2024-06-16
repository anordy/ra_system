@extends('layouts.master')

@section('title', 'VAT Tax Returns')

@section('content')

    <div class="card mt-3">
        <div class="card-header">
            <div class="text-uppercase font-weight-bold">VAT Return Configuration</div>
            <div class="card-tools">
                    <a class="btn btn-info btn-sm" href="">
                        <i class="bi bi-plus-circle-fill"></i> Add
                    </a>
            </div>
        </div>

        <div class="card-body">
            <livewire:returns.vat.vat-config-table />
        </div>
    </div>
@endsection