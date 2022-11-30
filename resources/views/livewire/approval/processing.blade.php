@if (count($this->getEnabledTranstions()) > 1)
    <div class="card shadow-sm mb-2 bg-white">
        <div class="card-header font-weight-bold">
            Approval
        </div>
        <div class="card-body">
            @include('livewire.approval.transitions')

            <div class="row">
                <div class="col-md-12 px-0 mx-0">
                    <div class="card">
                        <div class="card-header">BPRA Verification</div>
                        <div class="card-body">
                            @if ($subject->reg_no)
                                <livewire:approval.bpra-verification :business="$subject" />
                            @endif
                        </div>

                        @if ($subject->bpra_verification_status === \App\Models\BusinessStatus::APPROVED &&
                            (count($directors) || count($shareholders) || count($shares)))
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-body mt-0 p-2">
                                        <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist"
                                            style="margin-bottom: 0;">
                                            @if (count($directors))
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active" id="directors-tab" data-toggle="tab"
                                                        href="#directors" role="tab" aria-controls="directors"
                                                        aria-selected="true">Directors</a>
                                                </li>
                                            @endif
                                            @if (count($shareholders))
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="shareholders-tab" data-toggle="tab"
                                                        href="#shareholders" role="tab" aria-controls="shareholders"
                                                        aria-selected="false">Shareholders</a>
                                                </li>
                                            @endif
                                            @if (count($shares))
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="shares_distribution-tab" data-toggle="tab"
                                                        href="#shares_distribution" role="tab"
                                                        aria-controls="shares_distribution" aria-selected="false">Shares
                                                        &
                                                        Distribution</a>
                                                </li>
                                            @endif
                                        </ul>
                                        <div class="tab-content bg-white border shadow-sm" id="myTabContent">
                                            @if (count($directors))
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
                                                                            @if (substr($director['gender'], 3) == 'M')
                                                                                MALE
                                                                            @elseif (substr($director['gender'], 3) == 'F')
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
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>
                                            @endif

                                            @if (count($shareholders))
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
                                                                @foreach ($shareholders as $shareholder)
                                                                    <tr>
                                                                        <td class="">
                                                                            {{ $shareholder['entity_name'] }}
                                                                        </td>
                                                                        <td class="">
                                                                            {{ $shareholder['mob_phone'] }}
                                                                        </td>
                                                                        <td class="">
                                                                            {{ $shareholder['email'] }}
                                                                        </td>
                                                                        <td class="">
                                                                            @if (substr($shareholder['gender'], 3) == 'M')
                                                                                MALE
                                                                            @elseif (substr($shareholder['gender'], 3) == 'F')
                                                                                FEMALE
                                                                            @else
                                                                                -
                                                                            @endif
                                                                        </td>
                                                                        <td class="">
                                                                            {{ $shareholder['city_name'] }}
                                                                            <div>
                                                                                {{ $shareholder['first_line'] }}
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endif

                                            @if (count($shares))
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
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>

                                    </div>

                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
            @if ($this->checkTransition('registration_officer_review'))
                @include('livewire.approval.registration_officer_review')
            @endif

            <div class="row mx-1">
                <div class="col-md-12 mb-2">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Comments</label>
                        <textarea class="form-control @error('comments') is-invalid @enderror" wire:model='comments' rows="3"></textarea>

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
                <button type="button" class="btn btn-danger" wire:click="reject('application_filled_incorrect')">
                    <div wire:loading wire:target="reject">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Filled Incorrect return to Applicant
                </button>

                <button wire:click="approve('registration_officer_review')" wire:loading.attr="disabled"
                    class="btn btn-primary">
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
                    wire:click="reject('registration_manager_reject')">Reject
                    & Return</button>
                <button type="button" class="btn btn-primary"
                    wire:click="approve('registration_manager_review')">Approve & Forward</button>
            </div>
        @elseif ($this->checkTransition('director_of_trai_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="reject('director_of_trai_reject')">Reject &
                    Return</button>
                <button type="button" class="btn btn-primary"
                    wire:click="approve('director_of_trai_review')">Approve &
                    Complete</button>
            </div>
        @endif

    </div>
@endif
