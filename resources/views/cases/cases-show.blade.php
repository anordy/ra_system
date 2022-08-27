@extends('layouts.master')

@section('title', "Case #: $case->case_number")

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="details-a" data-toggle="tab" href="#details" role="tab"
                       aria-controls="home" aria-selected="true">Case Details</a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="proceedings-a" data-toggle="tab" href="#proceedings" role="tab"
                       aria-controls="home" aria-selected="true">Proceedings</a>
                </li>
                @if(!empty($case->date_closed))
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="appeals-a" data-toggle="tab" href="#appeals" role="tab"
                           aria-controls="profile" aria-selected="false">Appeals</a>
                    </li>
                @endif
            </ul>
            <hr>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane p-2 show active" id="details" role="tabpanel" aria-labelledby="to-print-tab">
                    <div class="row">
                        <div class="col-md-12">
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
                            <button class="btn btn-sm btn-primary mt-2"  onclick="Livewire.emit('showModal', 'cases.assign-officer-model',{{$case->id}})"><i class="fa fa-plus"></i>
                                {{!empty($case->assiged_officer)? 'Re-assign officer':'Assign officer'}}
                            </button>
                        </div>
                        <div class="col-md-12 mb-3">
                            <span class="font-weight-bold text-uppercase">Case Details</span>
                            <hr>
                            <p class="my-1">{{ $case->case_details  }}</p>
                        </div>
                    </div>
                </div>

                <div class="tab-pane p-2" id="proceedings" role="tabpanel" aria-labelledby="to-print-tab">
                    <livewire:cases.proceedings-table case_id="{{$case->id}}" />

                    @if(empty($case->case_outcome))
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary mt-2"
                                onclick="Livewire.emit('showModal', 'cases.add-proceeding-model',{{$case->id}})"><i class="fa fa-plus"></i>
                            Add Proceeding
                        </button>

                        <button class="btn btn-sm btn-primary mt-2"
                                onclick="Livewire.emit('showModal', 'cases.close-case-model',{{$case->id}})"><i class="fa fa-cancel"></i>
                            Close Case
                        </button>
                    </div>
                    @endif
                </div>

                @if(!empty($case->date_closed))
                <div class="tab-pane p-2" id="appeals" role="tabpanel" aria-labelledby="to-print-tab">

                   @foreach($case->case_appeals as $appeal)
                        <div class="row">
                           <div class="col-md-12">
                               <h5>Appeal #: {{$appeal->appeal_number}}</h5>
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
                    @endforeach
                    <div class="modal-footer">
                        @if(\App\Models\CaseAppeal::query()->where(['case_id'=>$case->id])->whereNull('case_outcome_id')->exists())
                            <button class="btn btn-sm btn-primary mt-2"
                                    onclick="Livewire.emit('showModal', 'cases.close-appeal-model',{{\App\Models\CaseAppeal::query()->where(['case_id'=>$case->id])->whereNull('case_outcome_id')->first()->id}})"><i class="fa fa-cross"></i>
                                Close Appeal
                            </button>
                        @else
                            <button class="btn btn-sm btn-primary mt-2"
                                    onclick="Livewire.emit('showModal', 'cases.add-appeal-model',{{$case->id}})"><i class="fa fa-plus"></i>
                                Add Appeal
                            </button>
                        @endif
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
@endsection

