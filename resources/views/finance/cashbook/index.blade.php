@extends('layouts.master')

@section('title', 'Cashbook Information')

@section('content')

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Cashbook Information
        </div>
    </div>

    <div class="row mx-1">
        @foreach($accounts as $account)
            <div class="col-md-3 card mx-1">
                <div class="card-body">
                    <h5 class="card-title">{{ str_replace('BANK', '', $account->psp_name)  }} Cashbook - {{ $account->currency ?? 'N/A'  }}</h5>
                    <p class="card-text">Account Number: {{ $account->ctr_acc_num ?? 'N/A'  }}</p>
                    <a href="{{ route('finance.cashbook.show', ['accountNum' => encrypt($account->ctr_acc_num)])  }}" class="btn btn-primary">View</a>
                </div>
            </div>
        @endforeach
    </div>

@endsection
