@extends('layouts.master')

@section('title')
    {{$setting_title}}
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-white text-uppercase font-weight-bold">
            {{$setting_title}}
            <div class="card-tools">
                <button class="btn btn-primary btn-sm px-3"
                    onclick="Livewire.emit('showModal', 'mvr.generic-setting-add-modal', '{{preg_replace('/\\\\/','\\\\\\',$model)}}')">
                    <i class="bi bi-plus-circle-fill pr-2"></i> Add New
                </button>
            </div>
        </div>

        <div class="card-body">
            @livewire('mvr.generic-settings-table',['model'=>$model])
        </div>
    </div>
@endsection
