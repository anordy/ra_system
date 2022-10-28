<div class="card">
    <div class="card-body">
        <h6 class="text-uppercase">Change Password</h6>
        <hr>
        <div class="row">
            <x-input name="current_password" type="password" required></x-input>
            <x-input name="new_password" type="password" required></x-input>
            <x-input name="confirm_password" type="password" required></x-input>
            <div class="col-md-12 text-right">
                <button class="btn btn-primary" wire:click="submit">
                    <i class="mr-2" wire:loading.remove wire:target="submit"></i>
                    <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading wire:target="submit"></i>
                    Change Password
                </button>
            </div>
        </div>
    </div>
</div>
