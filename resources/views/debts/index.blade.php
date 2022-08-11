@extends('layouts.master')

@section('title','Debt Management')

@section('content')
<div class="card mt-3">
    <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="return-tab" data-toggle="tab" href="#return" role="tab"
                   aria-controls="return" aria-selected="true">Return Debts</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="verification-tab" data-toggle="tab" href="#verification" role="tab"
                   aria-controls="profile" aria-selected="false">Verification Debts</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="objection-tab" data-toggle="tab" href="#objection" role="tab"
                   aria-controls="profile" aria-selected="false">Objection Debts</a>
            </li>
        </ul>

        <div class="tab-content card" id="myTabContent">

            <div class="tab-pane p-2 show active" id="return" role="tabpanel" aria-labelledby="return-tab">
            

            </div>
            <div class="tab-pane p-2" id="verification" role="tabpanel" aria-labelledby="verification-tab">
             


            </div>
            <div class="tab-pane p-2" id="objection" role="tabpanel" aria-labelledby="objection-tab">
            
                <livewire:debt.objection-table />

            </div>

        </div>
    </div>
</div>

@endsection