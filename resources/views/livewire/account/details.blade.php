<div class="card">
    <div class="card-body">
        <h6 class="text-uppercase">Account Details</h6>
        <hr>
        <div class="row">
            <x-input name="first_name" readonly></x-input>
            <x-input name="last_name" readonly></x-input>
            <x-input name="email" readonly></x-input>
            <x-input name="mobile" required></x-input>
            <div class="col-md-12 text-right">
                <button class="btn btn-primary" wire:click="makeChanges">
                    <i class="mr-2" wire:loading.remove wire:target="makeChanges"></i>
                    <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading wire:target="makeChanges"></i>
                    Update Changes
                </button>
            </div>
        </div>
    </div>
</div>
