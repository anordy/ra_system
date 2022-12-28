<div class="card rounded-0">
    <div class="card-header font-weight-bold text-uppercase bg-white">
        Import Bank Reconciliations
    </div>
    <div class="card-body px-4">
        <x-errors></x-errors>

        <div class="row mx-0">
            <div class="form-group mb-0 flex-grow-1 mr-2">
                <input wire:model="reconFile" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, text/csv" class="form-control form-control-file" type="file" placeholder="Choose file to import">
            </div>
            <button class="btn btn-primary" wire:click="submit">
                <i class="bi bi-arrow-bar-down mr-2"></i>
                Import Reconciliations
            </button>
        </div>
    </div>
</div>