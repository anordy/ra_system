@extends('layouts.master')

@section('title')
    Business Branches
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            Business Branches
        </div>
        <div class="card-body mt-0 p-2">
            <livewire:business.branches-table></livewire:business.branches-table>
        </div>
    </div>
@endsection
