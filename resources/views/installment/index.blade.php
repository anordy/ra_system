@extends('layouts.master')

@section('title','Installment Requests')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Installment Requests
        </div>
        <div class="card-body">
            <livewire:installment.installments-table />
        </div>
    </div>
@endsection
