@extends('layouts.master')

@section('title')
    {{$setting_title}}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">{{$setting_title}}</h5>
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                    onclick="Livewire.emit('showModal', 'mvr.generic-setting-add-modal', '{{preg_replace('/\\\\/','\\\\\\',$model)}}')"><i
                        class="fa fa-plus-circle"></i>
                    Add</button>

            </div>
        </div>

        <div class="card-body">
            @livewire('mvr.generic-settings-table',['model'=>$model])
        </div>
    </div>
@endsection
