<div class="pb-3">
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="d-flex justify-content-end">

                <button class="btn btn-success ml-2" wire:click="exportPDF" wire:loading.attr="disabled">
                    <i class="fas fa-file-pdf mr-1" wire:loading.attr="disabled"></i>
                    Export PDF
                </button>

                <button class="btn btn-success ml-2" wire:click="exportExcel" wire:loading.attr="disabled">
                    <i class="fas fa-file-excel mr-1" wire:loading.attr="disabled"></i>
                    Export XLS
                </button>
            </div>
        </div>
    </div>
</div>
