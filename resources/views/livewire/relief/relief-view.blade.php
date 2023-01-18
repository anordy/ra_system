<div>
    <div class="d-flex justify-content-start mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-info">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white pt-1">
            Relief Information
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ $relief->business->name }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Location Name</span>
                    <p class="my-1">{{ $relief->location->name ?? $relief->business->name }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Project Section</span>
                    <p class="my-1">{{ $relief->projectSection->name }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Project</span>
                    <p class="my-1">{{ $relief->project->name }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Rate</span>
                    <p class="my-1">{{ number_format($relief->rate, 1) }}%</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">VAT</span>
                    <p class="my-1">{{ number_format($relief->vat, 1) }}%</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Status</span>
                    <p class="my-1">{{ $relief->status }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Expiration</span>
                    <p class="my-1">{{ date('d/m/Y', strtotime($relief->expire)) }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Total Item Amount</span>
                    <p class="my-1">{{ number_format($relief->total_amount, 1) }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">VAT Amount</span>
                    <p class="my-1">{{ number_format($relief->vat_amount, 1) }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Relieved Amount</span>
                    <p class="my-1">{{ number_format($relief->relieved_amount, 1) }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Amount Payable</span>
                    <p class="my-1">{{ number_format($relief->amount_payable, 1) }}</p>
                </div>

            </div>
            <div class="pt-5"></div>
            <div class="card-header text-uppercase font-weight-bold bg-white pt-1">
                Relief Items
            </div>
            <div class="pt-1"></div>
            @livewire('relief.relief-items-table', ['id' => encrypt($relief->id)])

            @if ($relief->reliefAttachments->count()>0)
            <div class="pt-5"></div>
            <div class="card-header text-uppercase font-weight-bold bg-white pt-1">
                Relief Attachments
            </div>
            <div class="pt-1"></div>
            <div class="row">
                @foreach ($relief->reliefAttachments as $reliefAttachment)
                    <div class="col-4">
                        <a class="file-item" target="_blank"
                            href="{{ route('reliefs.get.attachment', ['path' => encrypt($reliefAttachment->file_path)]) }}">
                            <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                            <div style="font-weight: 500;" class="ml-1">
                                {{ $reliefAttachment->file_name }}
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            @endif
            
        </div>

        <div class="card-footer">
            <div class="row" style="background-color: #faecec">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Registered By</span>
                    <p class="my-1">{{ $relief->createdBy->fname ?? '' }} {{ $relief->createdBy->mname ?? '' }}
                        {{ $relief->createdBy->lname ?? '' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Register At</span>
                    <p class="my-1">{{ $relief->created_at }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
