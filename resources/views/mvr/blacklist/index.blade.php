@extends('layouts.master')

@section('title','Registration Blacklists')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Registration Blacklists</h5>
            <div class="card-tools">
                {{--                @can('mvr-blacklist-zra')--}}
                <button class="btn btn-info btn-sm"
                        onclick="Livewire.emit('showModal', 'mvr.blacklist.initiate', '{{ \App\Enum\Mvr\MvrBlacklistInitiatorType::ZRA }}')">
                    <i
                            class="bi bi-plus-circle-fill"></i>
                    Initiate Blacklist
                </button>
                {{--                @endcan--}}

                {{--                @can('mvr-blacklist-zartsa')--}}
                <button class="btn btn-info btn-sm"
                        onclick="Livewire.emit('showModal', 'mvr.blacklist.initiate', '{{ \App\Enum\Mvr\MvrBlacklistInitiatorType::ZARTSA }}')">
                    <i
                            class="bi bi-plus-circle-fill"></i>
                    Initiate Blacklist ZARTSA
                </button>
                {{--                @endcan--}}
            </div>
        </div>
        <div class="card-body">

            <ul class="nav nav-tabs" id="myTab" role="tablist">
{{--                @can('mvr-blacklist-zra')--}}
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="to-print-link" data-toggle="tab" href="#all" role="tab"
                           aria-controls="home" aria-selected="true">ZRA Initiated</a>
                    </li>
{{--                @endcan--}}
{{--                @can('mvr-blacklist-zartsa')--}}
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="printed-link" data-toggle="tab" href="#pending-approval" role="tab"
                           aria-controls="profile" aria-selected="false">ZARTSA Initiated</a>
                    </li>
{{--                @endcan--}}
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane p-2 show active" id="all" role="tabpanel" aria-labelledby="to-print-tab">
                    @livewire('mvr.blacklist.black-list-table', ['initiatorType' => \App\Enum\Mvr\MvrBlacklistInitiatorType::ZRA])
                </div>
                <div class="tab-pane p-2" id="pending-approval" role="tabpanel" aria-labelledby="printed-tab">
                    @livewire('mvr.blacklist.black-list-table', ['initiatorType' => \App\Enum\Mvr\MvrBlacklistInitiatorType::ZARTSA])
                </div>
            </div>
        </div>
    </div>
@endsection
