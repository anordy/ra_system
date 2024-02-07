@extends('layouts.master')

@section('title')
    Show Withholding Agent
@endsection

@section('content')
    @livewire('withholding-agents.withholding-agent-show', ['id' => $id])
@endsection
