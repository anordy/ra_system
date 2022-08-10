@extends('layouts.master')

@section('title','Tax Claims')

@section('content')
    <div class="card">
        <div class="card-body">
            <livewire:claims.claims-table></livewire:claims.claims-table>
        </div>
    </div>
@endsection