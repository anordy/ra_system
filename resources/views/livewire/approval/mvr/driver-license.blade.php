@if (count($this->getEnabledTransitions()) > 1)
    <div class="card shadow-sm mb-2 bg-white">
        <div class="card-header font-weight-bold">
            Approval
        </div>
        <div class="card-body">
            @include('livewire.approval.transitions')
            @if ($this->checkTransition('transport_officer_review'))
                <div class="row">
                    <div class="form-group col-lg-4">
                        <label class="control-label">Competence Certificate @if (!$competenceCertificate)*@endif
                        </label>
                        <input type="file" class="form-control" wire:model="competenceCertificate" id="competenceCertificate">
                        @error('competenceCertificate')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="text-secondary small">
                            <span class="font-weight-bold">
                                {{ __('Note') }}:
                            </span>
                            <span class="">
                                {{ __('Uploaded Documents must be less than 3  MB in size') }}
                            </span>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="control-label">License Restrictions</label>
                            <div x-data="{ selectedRestrictions: [], isOpen: false }">
                                <div class="position-relative">
                                    <button @click="isOpen = !isOpen" class="btn btn-outline-secondary w-100 text-start pr-2">
                                        <div class="d-flex align-items-center justify-content-between text-dark font-weight-bold">
                                            {{ count($selectedRestrictions) ? implode(', ', $selectedRestrictions) : 'Select Restrictions' }}
                                            <span>
                                                <span x-show="selectedRestrictions.length > 0" class="bg-primary text-white badge px-2 ">
                                                    {{ count($selectedRestrictions) }}
                                                </span>
                                                <i class="bi bi-chevron-down"></i>
                                            </span>
                                        </div>
                                    </button>
                                    <div x-show="isOpen" @click.away="isOpen = false"
                                         class="position-absolute bg-white border rounded mt-1 w-100 overflow-auto max-h-40 shadow z-9999"
                                         x-cloak>
                                        @foreach ($restrictions as $restriction)
                                            <label class="d-block px-4 py-2 mb-0">
                                                <input type="checkbox" x-model="selectedRestrictions"
                                                       value="{{ $restriction->symbol }}"
                                                       wire:model.lazy="selectedRestrictions.{{ $restriction->id }}">
                                                {{ $restriction->description }} ({{ $restriction->symbol  }})
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif

            <div class="row m">
                <div class="col-md-12 mb-3">
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
        @if ($this->checkTransition('transport_officer_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="confirmPopUpModal('reject', 'application_filled_incorrect')">Reject &
                    Return
                </button>
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'transport_officer_review')">Approve &
                    Complete
                </button>
            </div>
        @endif

    </div>
@endif
