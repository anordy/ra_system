@extends('layouts.master')

@section('title', "Appeal Number: $appeal->appeal_number")

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5>Appeal Number: {{$appeal->appeal_number}}</h5>
                    <hr />
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Appeal Number</span>
                    <p class="my-1">{{ $appeal->appeal_number }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Appeal Level</span>
                    <p class="my-1">{{ $appeal->court_level->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Date Opened</span>
                    <p class="my-1">{{ $appeal->date_opened ?? 'N/A'  }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Appeal details</span>
                    <p class="my-1">{{ $appeal->appeal_details }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Date Closed</span>
                    <p class="my-1">{{ $appeal->date_closed ?? 'N/A'  }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Outcome</span>
                    <p class="my-1">{{ $appeal->case_outcome->name ?? 'N/A'  }}</p>
                </div>
            </div>

            <br>
            <br>

            <div class="row">
                <div class="col-md-12">
                    <h5>Case Number: {{$appeal->case_number}}</h5>
                    <hr />
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Case #</span>
                    <p class="my-1">{{ $case->case_number  }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Date Opened</span>
                    <p class="my-1">{{ $case->date_opened  }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Court</span>
                    <p class="my-1">{{ $case->court  }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Stage</span>
                    <p class="my-1">{{ $case->case_stage->name  }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Outcome</span>
                    <p class="my-1">{{ $case->case_outcome->name ?? 'N/A'  }}</p>
                </div>

                @if(!empty($case->date_closed))
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Date Closed</span>
                        <p class="my-1">{{ $case->date_closed  }}</p>
                    </div>
                @endif
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Assigned Officer</span>
                    <p class="my-1">{{ !empty($case->assiged_officer)? $case->assiged_officer->fullname() : 'N/A'  }}</p>
                </div>
                <div class="col-md-12 mb-3">
                    <span class="font-weight-bold text-uppercase">Case Details</span>
                    <p class="my-1">{{ $case->case_details  }}</p>
                </div>
            </div>

            <div class="modal-footer">
                @if(empty($appeal->case_outcome))
                    <button class="btn btn-sm btn-primary mt-2"
                            onclick="Livewire.emit('showModal', 'cases.close-appeal-model',{{\App\Models\CaseAppeal::query()->where(['case_id'=>$case->id])->whereNull('case_outcome_id')->first()->id}})"><i class="fa fa-cross"></i>
                        Close Appeal
                    </button>
                @endif
            </div>

        </div>
    </div>
@endsection

