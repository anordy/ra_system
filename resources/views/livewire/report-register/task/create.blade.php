<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="text-uppercase">Create New Task</h5>
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="bi bi-x-circle-fill"></i></button>
        </div>
        <div class="modal-body">
            <div class="border-0">
                @include('layouts.component.messages')

                <div class="row mx-4 mt-2">
                    <div class="col-md-12 form-group">
                        <label>Task Name *</label>
                        <input type="text" wire:model.defer="title"
                               class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}">
                        @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3 form-group">
                        <label>Task Description *</label>
                        <textarea class="form-control @error("description") is-invalid @enderror"
                                  wire:model.defer='description' rows="4"></textarea>
                        @error("description")
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="row mx-4">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>Set Task Priority *</label>
                            <select wire:model.defer="priority" class="form-control @error('priority') is-invalid @enderror">
                                <option value="">Select Priority</option>
                                @foreach ($priorities ?? [] as $priority)
                                    <option value="{{ $priority }}">{{ ucfirst($priority) }}</option>
                                @endforeach
                            </select>
                            @error('priority')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <x-select-searchable :options="$users" name="staffId" col="6" label="Assigned To" placeholder="Select User" optionNameAccessor="fullname"/>
                    <div class="col-md-6 form-group">
                        <label>Task Date *</label>
                        <input type="date" wire:model.defer="startDate"
                               min="{{ now()->format('Y-m-d') }}"
                               class="form-control {{ $errors->has('startDate') ? 'is-invalid' : '' }}">
                        @error('startDate')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>Is this a scheduled task? *</label>
                            <select wire:model="isScheduled"
                                    class="form-control @error('isScheduled') is-invalid @enderror">
                                <option value="">Select Option</option>
                                <option value="{{ \App\Enum\GeneralConstant::ZERO }}">No</option>
                                <option value="{{ \App\Enum\GeneralConstant::ONE }}">Yes</option>
                            </select>
                            <small>A scheduled task will run only when the scheduled time has been set</small>
                            @error('isScheduled')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    @if($isScheduled === \App\Enum\GeneralConstant::ONE)
                        <div class="col-md-6 form-group">
                            <label>Schedule Time *</label>
                            <input type="datetime-local" wire:model="scheduledTime"
                                   min="{{ now() }}"
                                   class="form-control {{ $errors->has('scheduledTime') ? 'is-invalid' : '' }}">
                            @error('scheduledTime')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    @endif
                </div>

                @foreach ($files as $index => $file)
                    <div class="row mx-4">
                        <div class="col-md-6">
                            <input type="text" wire:model.defer="files.{{ $index }}.name" class="form-control"
                                   placeholder="Enter Document Name" value="{{ $file["name"] }}">
                            @error("files.$index.name")
                            <span class="text-danger">{{ __("Please Enter Name of The Document") }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input type="file" wire:model.defer="files.{{ $index }}.file" class="form-control">
                            @error("files.$index.file")
                            <span
                                class="text-danger">{{ __("Please Upload Valid Document File  In PDF or EXCEL Format") }}</span>
                            @enderror
                        </div>
                        @if ($index > 0)
                            <div class="col-md-1">
                                <button wire:click="removeFileInput({{ $index }})" class="btn btn-danger">
                                    Remove
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach

            </div>
        </div>
        <div class="modal-footer">
            <button wire:click="addFileInput" wire:loading.attr="disabled" class="btn btn-primary">Add More
                File
            </button>
            <button type="button" class="btn btn-danger px-2" data-dismiss="modal">Close</button>
            <button class="btn btn-primary rounded-0" wire:click="submit" wire:loading.attr="disable">
                <i class="bi bi-arrow-return-right mr-2" wire:loading.remove wire:target="submit"></i>
                <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                   wire:target="submit"></i>
                {{ __('Submit') }}
            </button>
        </div>
    </div>
</div>
