<div class="d-flex justify-content-between">
    @if ($taxClaim->original_figure && $taxClaim->supporting_document_for_agreed_figure)
        <button class="btn btn-primary" wire:click="enableEditing">Edit Figure</button>
    @else
        <form class="w-100 d-flex justify-content-between" wire:submit.prevent="saveAgreedAmount">
            <div class="form-group">
                <label for="agreedAmount">Agreed Claim Amount:</label>
                <input type="text" class="form-control" id="agreedAmount" wire:model.lazy="agreedAmount"
                    oninput="formatWithCommas(this)">
                @error('agreedAmount')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="supportingDocument">Supporting Document:</label>
                <input type="file" class="form-control" id="supportingDocument" wire:model="supportingDocument">
                @error('supportingDocument')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="supportingDocument">.</label>
                <button type="submit" class="form-control btn btn-primary">Save</button>
            </div>

        </form>
    @endif
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
