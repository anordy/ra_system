@if (count($this->getEnabledTranstions()) > 1)
    @if ($subject->previous_zno)
        <div class="card m-2 bg-white rounded-0">
            <div class="card-header">ZNUMBER Verification</div>
            <div class="card-body">
                <livewire:approval.znumber-verification :business="$subject" />
            </div>
        </div>
    @endif
    @if ($subject->reg_no)
        <div class="card m-2 bg-white rounded-0">
            <div class="card-header">BPRA Verification</div>
            <div class="card-body">
                <livewire:approval.bpra-verification :business="$subject" />
            </div>
            @if ($subject->bpra_verification_status === \App\Models\BusinessStatus::APPROVED && (count($directors) > 0 || count($shareholders) > 0 || count($shares) > 0))
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body mt-0 p-2 px-0">
                            <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist"
                                style="margin-bottom: 0;">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="directors-tab" data-toggle="tab"
                                       href="#directors" role="tab" aria-controls="directors"
                                       aria-selected="true">Directors</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="shareholders-tab" data-toggle="tab"
                                       href="#shareholders" role="tab" aria-controls="shareholders"
                                       aria-selected="false">Shareholders</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="shares_distribution-tab" data-toggle="tab"
                                       href="#shares_distribution" role="tab"
                                       aria-controls="shares_distribution" aria-selected="false">Shares
                                        &
                                        Distribution</a>
                                </li>
                            </ul>
                            <div class="tab-content bg-white border shadow-sm" id="myTabContent">
                                <div class="tab-pane fade show active" id="directors" role="tabpanel"
                                     aria-labelledby="directors-tab">
                                    <div class="row m-1 p-3">
                                        <table class="table table-striped table-sm">
                                            <label
                                                    class="font-weight-bold text-uppercase mt-2">Directors</label>
                                            <thead>
                                            <th style="width: 29%">Name</th>
                                            <th style="width: 16%">Phone</th>
                                            <th style="width: 10%">Email</th>
                                            <th style="width: 20%">Gender</th>
                                            <th style="width: 25%">Location</th>
                                            </thead>
                                            <tbody>
                                            @if (count($directors) > 0)
                                                @foreach ($directors as $director)
                                                    <tr>
                                                        <td class="">
                                                            {{ $director['first_name'] }}
                                                            {{ $director['middle_name'] }}
                                                            {{ $director['last_name'] }}
                                                        </td>
                                                        <td class="">
                                                            {{ $director['mob_phone'] }}
                                                        </td>
                                                        <td class="">
                                                            {{ $director['email'] }}
                                                        </td>
                                                        <td class="">
                                                            @if (substr($director['gender'] ?? '', 3) == 'M')
                                                                MALE
                                                            @elseif(substr($director['gender'] ?? '', 3) == 'F')
                                                                FEMALE
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="">
                                                            {{ $director['city_name'] }}
                                                            <div>
                                                                {{ $director['first_line'] }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10" class="text-center">
                                                        No Data
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                <div class="tab-pane fade" id="shareholders" role="tabpanel"
                                     aria-labelledby="shareholders-tab">
                                    <div class="row m-1 p-3">
                                        <table class="table table-striped table-sm">
                                            <label
                                                    class="font-weight-bold text-uppercase mt-2">Shareholders</label>
                                            <thead>
                                            <th style="width: 29%">Name</th>
                                            <th style="width: 16%">Phone</th>
                                            <th style="width: 10%">Email</th>
                                            <th style="width: 20%">Gender</th>
                                            <th style="width: 25%">Location</th>
                                            </thead>
                                            <tbody>
                                            @if (count($shareholders) > 0)
                                                @foreach ($shareholders as $shareholder)
                                                    <tr>
                                                        <td class="">
                                                            {{ $shareholder['entity_name'] ? $shareholder['entity_name'] : ($shareholder['first_name'] . ' ' . $shareholder['middle_name'] . ' ' . $shareholder['last_name'] ?? 'N/A') }}
                                                        </td>
                                                        <td class="">
                                                            {{ $shareholder['mob_phone'] }}
                                                        </td>
                                                        <td class="">
                                                            {{ $shareholder['email'] }}
                                                        </td>
                                                        <td class="">
                                                            @if (substr($shareholder['gender'] ?? '', 3) == 'M')
                                                                MALE
                                                            @elseif(substr($shareholder['gender'] ?? '', 3) == 'F')
                                                                FEMALE
                                                            @else
                                                                -
                                                            @endif
                                                        </td>

                                                        <td class="">
                                                            @if ($shareholder['city_name'])
                                                                {{ $shareholder['city_name'] }}
                                                                <div>
                                                                    {{ $shareholder['first_line'] }}
                                                                </div>
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10" class="text-center">
                                                        No Data
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="shares_distribution" role="tabpanel"
                                     aria-labelledby="shares_distribution-tab">
                                    <div class="row m-1 p-3">
                                        <table class="table table-striped table-sm">
                                            <label class="font-weight-bold text-uppercase mt-2">Shares &
                                                Distribution</label>
                                            <thead>
                                            <th style="width: 30%">Ower Name</th>
                                            <th style="width: 14%">No Of Shares</th>
                                            <th style="width: 5%">Currency</th>
                                            <th style="width: 23%">Shares Taken</th>
                                            <th style="width: 23%">Shares Paid</th>
                                            </thead>
                                            <tbody>
                                            @if (count($shares) > 0)
                                                @foreach ($shares as $share)
                                                    <tr>
                                                        <td class="">
                                                            {{ $share['shareholder_name'] }}
                                                        </td>
                                                        <td class="">
                                                            {{ $share['number_of_shares'] }}
                                                        </td>
                                                        <td class="">
                                                            {{ $share['currency'] }}
                                                        </td>
                                                        <td class="">
                                                            {{ $share['number_of_shares_taken'] }}
                                                        </td>
                                                        <td class="">
                                                            {{ $share['number_of_shares_paid'] }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10" class="text-center">
                                                        No Data
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            @endif

        </div>
    @endif
    <div class="card m-2 bg-white rounded-0">
        <div class="card-header font-weight-bold bg-white">
            Business Registration Approval
        </div>
        <div class="card-body">
            @if ($this->checkTransition('registration_officer_review'))
                @include('livewire.approval.registration_officer_review')
            @endif
            @include('livewire.approval.transitions')
            <div class="row mx-1">
                <div class="col-md-12 mb-2">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Comments</label>
                        <textarea class="form-control @error('comments') is-invalid @enderror" wire:model.defer='comments' rows="3"></textarea>

                        @error('comments')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        @if ($this->checkTransition('registration_officer_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="confirmPopUpModal('reject','application_filled_incorrect')">
                    <div wire:loading wire:target="reject">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Filled Incorrect return to Applicant
                </button>

                <button wire:click="confirmPopUpModal('approve', 'registration_officer_review')"
                    wire:loading.attr="disabled" class="btn btn-primary">
                    <div wire:loading wire:target="approve">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('registration_manager_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="confirmPopUpModal('reject','registration_manager_reject')">
                    <div wire:loading wire:target="reject">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    Reject & Return
                </button>
                <button wire:click="confirmPopUpModal('approve', 'registration_manager_review')"
                    wire:loading.attr="disabled" class="btn btn-primary">
                    <div wire:loading wire:target="approve">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('director_of_trai_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="confirmPopUpModal('reject','director_of_trai_reject')">
                    <div wire:loading wire:target="reject">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    Reject & Return
                </button>
                <button wire:click="confirmPopUpModal('approve', 'director_of_trai_review')"
                    wire:loading.attr="disabled" class="btn btn-primary">
                    <div wire:loading wire:target="approve">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    Approve & Complete
                </button>
            </div>
        @endif

    </div>
@endif
