<div>
    <div>
        <div class="card-header text-uppercase font-weight-bold bg-white">
            {{ __('Lease Information') }}
        </div>
        <div class="row pt-3">
            <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                 x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false"
                 x-on:livewire-upload-progress="progress = $event.detail.progress">
                <x-input name="leaseAgreement" label="{{ __('Lease Agreement Document') }}" col="12"
                         type="file" required></x-input>

                <!-- Progress Bar -->
                <div x-show="isUploading">
                    <progress max="100" x-bind:value="progress"></progress>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <button class="btn btn-primary ml-1" wire:click="submit" wire:loading.attr="disabled">
                {{ __('Submit') }}
                <i class="bi bi-chevron-right ml-1" wire:loading.remove wire:target="submit"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                   wire:target="submit"></i>
            </button>
        </div>
    </div>
</div>
