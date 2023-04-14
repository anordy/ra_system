<div>
    @if (count($returnHistories ?? []) > 0)
        @foreach ($returnHistories as $key => $history)
            <div id="accordion">
                <div class="card">
                    <button class="btn collapsed" data-toggle="collapse"
                        data-target="#collapseLocation-{{ $key }}" aria-expanded="false"
                        aria-controls="collapseLocation-{{ $key }}">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                Edit History Version #{{ $history->version }}
                                <span class="ml-2">
                                    <i class="bi bi-chevron-double-down"></i>
                                </span>
                            </h5>
                        </div>
                    </button>

                    <div id="collapseLocation-{{ $key }}" class="collapse" aria-labelledby="headingTwo"
                        data-parent="#accordion">
                        <div class="card-body">
                            @include('vetting.includes.return-history-item-tables')
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
    <div class="py-3">
        <div class="text-center">No Return History</div>
    </div>
    @endif

</div>
