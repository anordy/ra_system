@extends('layouts.master')

@section('title', 'Departmental Reports')

@section('content')
    <div class="card rounded-0">
        @livewire('reports.department.departmental-reports')
    </div>
@endsection
