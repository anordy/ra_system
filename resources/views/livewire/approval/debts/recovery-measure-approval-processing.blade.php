    <div>
        <br>
        @include('livewire.approval.transitions')

        <div class="card">

            <div class="card-body">
                <h6 class="text-uppercase mt-2 ml-2">Recommend Recovery Measures</h6>
                <hr>

                <div class="row m-2 pt-3">
                    <div class="col-md-12 form-group">
                        <label>Recommendation for recovery measure: *</label>
                        <select wire:model.lazy="recovery_measures" multiple size="6" class="form-control">
                            <option></option>
                            @foreach ($recovery_measure_categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('recovery_measures')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Comments</label>
                            <textarea class="form-control" wire:model.lazy='comments' rows="3"></textarea>
                        </div>
                    </div>

                </div>

                @if ($this->checkTransition('crdm_assign'))
                    <div class="modal-footer p-2 m-0">
                        <a href="{{ route('debts.debt.index') }}" class="btn btn-danger mr-2">Cancel</a>
                        <button type="button" class="btn btn-primary" wire:click="approve('crdm_assign')"
                            wire:loading.attr="disabled">
                            <div wire:loading wire:target="approve">
                                <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            Approve & Forward
                        </button>
                    </div>
                @elseif ($this->checkTransition('commissioner_review'))
                    <div class="modal-footer p-2 m-0">
                        <button type="button" class="btn btn-danger" wire:click="reject('assignment_filled_incorrect')">Reject
                            &
                            Return</button>
                        <button type="button" class="btn btn-primary"
                            wire:click="approve('commissioner_review')">Approve</button>
                    </div>
                @elseif ($this->checkTransition('assignment_corrected'))
                    <div class="modal-footer p-2 m-0">
                        <button type="button" class="btn btn-primary"
                            wire:click="approve('assignment_corrected')">Correct & Forward</button>
                    </div>
                @endif
            </div>

        </div>
    </div>
