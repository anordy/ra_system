@extends('layouts.master')

@section('title', 'Cashbook Account')


@section('content')

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            {{ $account->psp_name ?? 'N/A' }} {{ $account->currency ?? 'N/A' }} - {{ $account->ctr_acc_num ?? 'N/A' }} Account
        </div>
        <div class="card-body">
            @livewire('finance.cash-book.cash-book-filter', ['tablename' => 'cash-book-table']) <br>
            <livewire:finance.cash-book.cash-book-table accountNumber="{{ $accountNumber }}" />
        </div>
    </div>
@endsection
