<div>
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Incidents Summary</h5>
            <div class="card-tools">
                <div class="form-inline">
                    <div class="form-inline">
                        <div class="form-group">
                            <label>From</label>
                            <input type="date" wire:model.defer="startDate" max="{{ now()->format('Y-m-d') }}"
                                   class="form-control mx-md-5">
                        </div>
                        <div class="form-group">
                            <label>To</label>
                            <input type="date" wire:model="endDate" max="{{ now()->format('Y-m-d') }}"
                                   class="form-control mx-md-5">
                        </div>
                        <button wire:click="filter" wire:loading.attr="disabled" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h6>Taxpayer Incidents</h6>
    <hr>
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-c-blue order-card">
                        <div class="card-block p-2">
                            <div class="">
                                <h6 class="text-left mb-4"><span>Total Taxpayer Incidents</span><span class="h6 ml-1 f-right">{{ $stats[0]->totaltaxpayerincidents ?? '' }}</span></h6>
                                <h6 class="text-left mb-4"><span>Pending Taxpayer Incidents</span><span class="h6 ml-1 f-right">{{ $stats[0]->totalpendingtaxpayerincidents ?? '' }}</span></h6>
                                <h6 class="text-left mb-4"><span>Closed Taxpayer Incidents</span><span class="h6 ml-1 f-right">{{ $stats[0]->totalclosedtaxpayerincidents ?? '' }}</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-c-blue order-card">
                        <div class="card-block p-2">
                            <div>
                                <h6 class="text-left mb-4"><span>Breached Taxpayer Incidents</span><span class="h6 ml-1 f-right">{{ $stats[0]->totaltaxpayerbreachedincidents ?? '' }}</span>
                                </h6>
                                <h6 class="text-left mb-4"><span>In Time Closed Taxpayer Incidents</span><span
                                            class="h6 ml-1 f-right">{{ $stats[0]->totaltaxpayerintimeclosedincidents ?? '' }}</span></h6>
                                <h6 class="text-left mb-4"><span>Late Closed Taxpayer Incidents</span><span
                                            class="h6 ml-1 f-right">{{ $stats[0]->totaltaxpayerlateclosedincidents ?? '' }}</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-c-blue order-card">
                <div class="card-block p-2">
                    <div>
                        <h6 class="text-left mb-3"><span>Top Taxpayers Reported Incidents by Category</span></h6>
                        @for ($i = 0; $i < 5; $i++)
                            @if(isset($taxpayerSubs[$i]))
                                <h6 class="text-left mb-3"><span>{{ $taxpayerSubs[$i]->name ?? '' }}</span><span class="h6 ml-1 f-right">{{ $taxpayerSubs[$i]->count ?? '' }}</span></h6>
                            @else
                                <h6 class="text-left mb-3"><span>&nbsp;</span></h6>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>


    <h6>Staff Incidents</h6>
    <hr>
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-c-blue order-card">
                        <div class="card-block p-2">
                            <div class="">
                                <h6 class="text-left mb-4"><span>Total Staff Incidents</span><span class="h6 ml-1 f-right">{{ $stats[0]->totalstaffincidents ?? '' }}</span></h6>
                                <h6 class="text-left mb-4"><span>Pending Staff Incidents</span><span class="h6 ml-1 f-right">{{ $stats[0]->totalpendingstaffincidents ?? '' }}</span></h6>
                                <h6 class="text-left mb-4"><span>Closed Staff Incidents</span><span class="h6 ml-1 f-right">{{ $stats[0]->totalclosedstaffincidents ?? '' }}</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-c-blue order-card">
                        <div class="card-block p-2">
                            <div>
                                <h6 class="text-left mb-4"><span>Breached Staff Incidents</span><span class="h6 ml-1 f-right">{{ $stats[0]->totalstaffbreachedincidents ?? '' }}</span>
                                </h6>
                                <h6 class="text-left mb-4"><span>In Time Closed Staff Incidents</span><span
                                            class="h6 ml-1 f-right">{{ $stats[0]->totalstaffintimeclosedincidents ?? '' }}</span></h6>
                                <h6 class="text-left mb-4"><span>Late Closed Staff Incidents</span><span
                                            class="h6 ml-1 f-right">{{ $stats[0]->totalstafflateclosedincidents ?? '' }}</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-c-blue order-card">
                <div class="card-block p-2">
                    <div>
                        <h6 class="text-left mb-3"><span>Top Staff Reported Incidents by Category</span></h6>
                        @for ($i = 0; $i < 5; $i++)
                            @if(isset($staffSubs[$i]))
                                <h6 class="text-left mb-3"><span>{{ $staffSubs[$i]->name ?? '' }}</span><span class="h6 ml-1 f-right">{{ $staffSubs[$i]->count ?? '' }}</span></h6>
                            @else
                                <h6 class="text-left mb-3"><span>&nbsp;</span></h6>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>