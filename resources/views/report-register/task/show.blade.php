@extends('layouts.master')

@section('title','View Task')

@section('content')
    @livewire('report-register.task.view-task', ['taskId' => $id])
@endsection
