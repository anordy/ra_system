@extends('layouts.master')

@section('title')
    View Withholding Agent
@endsection

@section('content')
    @livewire('withholding-agents.withholding-agent-view', ['id' => $id])
@endsection
