@extends('layouts.master')

@section('title')
    Assign Recovery Measure
@endsection

@section('content')
    @livewire('debt.recovery-measure.assign-recovery-measure', ['debtId' => $debtId])
@endsection
