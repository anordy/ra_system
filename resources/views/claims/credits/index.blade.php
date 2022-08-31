@extends('layouts.master')

@section('title', 'Credit Brought Forward (Tax Credits)')

@section('content')
    <div class="card">
        <div class="card-body">
            <livewire:claims.credits-table></livewire:claims.credits-table>
        </div>
    </div>
@endsection