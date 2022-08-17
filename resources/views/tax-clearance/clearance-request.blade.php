@extends('layouts.master')

@section('title', 'Tax Clearance Request')

@section('content')
    <div class="row mx-1">
        <div class="col-md-12">
            <livewire:tax-clearance.tax-clearance-request 
            :returnDebts="$returnDebts" :verificationDebts="$verificationDebts"
            :auditDebts="$auditDebts" :investigationDebts="$investigationDebts"
            :businessLocation="$businessLocation" />
        </div>
    </div>
@endsection