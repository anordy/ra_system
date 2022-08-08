@extends('layouts.master')

@section('title', 'Tax Returns')

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#biz" role="tab"
                       aria-controls="home" aria-selected="true">Pending Approval</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="academic-tab" data-toggle="tab" href="#academic" role="tab"
                       aria-controls="profile" aria-selected="false">Approved Returns</a>
                </li>
            </ul>

            <div class="tab-content card" id="myTabContent">

                <div class="tab-pane p-2 show active" id="biz" role="tabpanel" aria-labelledby="biz-tab">
                    <div class=" disp-Info text-center">
                        Requests needed for verification
                    </div>
                    <livewire:returns.vat.vat-return-table/>

                </div>
                <div class="tab-pane p-2" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                    <div class="disp-Info text-center">
                        Requests needed for approval
                    </div>

{{--                    <livewire:tax-agent.tax-agent-table/>--}}

                </div>

            </div>

            <div>
                <livewire:returns.vat.nill-return />
            </div>
        </div>
    </div>
@endsection