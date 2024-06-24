<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-4">
                        <label class="control-label">Inspection Form</label>
                        <input type="file" class="form-control  @error('approvalReport') is-invalid @enderror"
                               wire:model.lazy="approvalReport">
                        @error('approvalReport')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="pt-2">
                    <div class="row pl-3 pb-2">
                        <span>Please provide additional attachments if any:</span>
                    </div>
                    <div class="card-body">
                        <table width="50%" class="table table-border table-striped">
                            <thead>
                            <tr>
                                <th>SN</th>
                                <th>Attachment Name</th>
                                <th>Attachment File</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($attachments as $i => $attachment)
                                <tr>
                                    <td>
                                        {{ $i + 1 }}
                                    </td>
                                    <td>
                                        <div class="input-group @error('attachment.' . $i) is-invalid @enderror">
                                            <input
                                                    class="form-control @error('attachments.' . $i . '.name') is-invalid @enderror"
                                                    wire:model.lazy="attachments.{{ $i }}.name"/>
                                            @error('attachments.' . $i . '.name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group @error('attachment.' . $i) is-invalid @enderror">
                                            <div x-data="{ isUploading: false, progress: 0 }"
                                                 x-on:livewire-upload-start="isUploading = true"
                                                 x-on:livewire-upload-finish="isUploading = false"
                                                 x-on:livewire-upload-error="isUploading = false"
                                                 x-on:livewire-upload-progress="progress = $event.detail.progress">
                                                <input
                                                        class="form-control @error('attachments.' . $i . '.file') is-invalid @enderror"
                                                        wire:model.lazy="attachments.{{ $i }}.file" type="file"
                                                        accept="application/pdf"/>

                                                <!-- Progress Bar -->
                                                <div x-show="isUploading">
                                                    <progress max="100" x-bind:value="progress"></progress>
                                                </div>
                                            </div>
                                            @error('attachments.' . $i . '.file')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </td>
                                    <td style="min-width: 100%">
                                        @if (count($attachments) > 1)
                                            <div class="text-right mt-2">
                                                <button class="btn btn-danger btn-sm"
                                                        wire:click="removeAttachment({{ $i }})">
                                                    <i class="bi bi-x-lg mr-1"></i>
                                                    <small> {{ __('Remove') }} </small>
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-right mt-2">
                            <button class="btn btn-secondary" wire:click="addAttachment()">
                                <i class="bi bi-plus-circle mr-1"></i>
                                Add Attachment
                            </button>
                        </div>
                    </div>
                </div>
                <div class="text-secondary small">
                    <span class="font-weight-bold">
                        {{ __('Note') }}:
                    </span>
                    <span class="">
                        {{ __('Uploaded Documents must be less than 3  MB in size') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
