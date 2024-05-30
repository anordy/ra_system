<div>

        <form wire:submit.prevent="saveAgreedAmount">
            <div class="row w-25">
                <div class="form-group col-md-12">
                    <label for="agreedAmount">Agreed Claim Amount:</label>
                    <input type="text" class="form-control" id="agreedAmount" wire:model.lazy="agreedAmount"
                           oninput="formatWithCommas(this)">
                    @error('agreedAmount')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-12">
                    <label for="supportingDocument">Supporting Document:</label>
                    <input type="file" class="form-control" id="supportingDocument" wire:model="supportingDocument">
                    @error('supportingDocument')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-8">
                    <label for="supportingDocument">.</label>
                    <button type="submit" class="form-control btn btn-primary">Save</button>
                </div>
            </div>

        </form>
</div>

<script>
    function formatWithCommas(input) {
        // Remove existing commas and non-numeric characters
        let value = input.value.replace(/[^\d.-]/g, '');
        // Format the value with commas
        value = parseFloat(value).toLocaleString('en-US', {
            maximumFractionDigits: 2
        });
        // Update the input value
        input.value = value;
    }
</script>
