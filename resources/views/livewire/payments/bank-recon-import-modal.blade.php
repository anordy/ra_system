<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Import Bank Reconciliations</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                    <i class="fa fa-times-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                <x-errors></x-errors>

                <div class="row mx-0">
                    <div class="col-md-12">
                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Select file to import</label>
                            <input wire:model="reconFile"
                                   accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, text/csv"
                                   class="form-control form-control-file @error('reconFile') is-invalid @enderror"
                                   type="file" placeholder="Choose file to import">
                            @error('reconFile')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @else
                                <span class="small mt-2 d-block">
                                    Accepted files are .csv, .xls and .xlsx
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">
                    <i class="bi bi-x-circle mr-2"></i>
                    Close
                </button>
                <button class="btn btn-secondary" wire:click="downloadTemplate()">
                    <i class="bi bi-cloud-download-fill mr-2"></i>
                    Get Template File
                </button>
                <button class="btn btn-primary mr-2" wire:click="submit" wire:loading.attr="disable">
                    <i class="bi bi-arrow-bar-down mr-2" wire:loading.remove wire:target="submit"></i>
                    <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading wire:target="submit"></i>
                    Import Reconciliations
                </button>
            </div>
        </div>
    </div>
</div>