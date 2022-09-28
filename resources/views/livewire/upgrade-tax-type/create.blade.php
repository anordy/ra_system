<div>
    @can('qualified-tax-types-add')
        <button wire:click="upgradeTaxType()" class="btn btn-primary px-3 ml-2" type="button"
                wire:loading.attr="disabled">
            <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
               wire:target="upgradeTaxType()"></i>
            <i class="bi bi-arrow-up-circle mr-2"></i>Upgrade Tax Type
        </button>
    @endcan
</div>
