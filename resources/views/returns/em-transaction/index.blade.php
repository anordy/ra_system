@extends('layouts.master')

@section('title', 'Electronic Money Transaction')

@section('content')

@livewire('returns.return-summary', ['vars' => $vars])
@livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])

<div class="card">
    <div class="card-body">
        <livewire:returns.em-transaction.em-transactions-table />
    </div>
</div>
@endsection