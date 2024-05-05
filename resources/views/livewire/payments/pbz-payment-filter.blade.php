<div class="px-2 pt-1">
    <form wire:submit.prevent="filter">
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="currency" class="d-flex justify-content-between'">
                    <span>Currency</span>
                </label>
                <select name="currency" class="form-control" wire:model="currency">
                    <option value="{{ \App\Enum\GeneralConstant::ALL }}">All</option>
                    <option value="{{ \App\Enum\Currencies::TZS }}">TZS</option>
                    <option value="{{ \App\Enum\Currencies::USD }}">USD</option>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between">
                    <span>Start Date</span>
                </label>
                <input type="date" max="{{ now()->format('Y-m-d') }}" class="form-control" wire:model.defer="range_start">
                @error('range_start')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>End Date</span>
                </label>
                <input type="date" max="{{ now()->format('Y-m-d') }}" class="form-control" wire:model.defer="range_end">
                @error('range_end')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-6 form-group">
                <label for="has_bill" class="d-flex justify-content-between'">
                    <span>Has ZanMalipo Bill</span>
                </label>
                <select name="has_bill" class="form-control" wire:model.defer="has_bill">
                    <option value="{{ \App\Enum\GeneralConstant::ALL }}">All</option>
                    <option value="{{ \App\Enum\GeneralConstant::YES }}">Yes</option>
                    <option value="{{ \App\Enum\GeneralConstant::NO }}">No</option>
                </select>
            </div>
            <div class="col-md-6 form-group">
                <label for="has_bill" class="d-flex justify-content-between'">
                    <span>ZanMalipo Status</span>
                </label>
                <select class="form-control" wire:model.defer="zanmalipo_status">
                    <option value="{{ \App\Enum\GeneralConstant::ALL }}">All</option>
                    <option value="{{ \App\Enum\PaymentStatus::PAID }}">Paid</option>
                    <option value="{{ \App\Enum\PaymentStatus::PENDING }}">Pending</option>
                    <option value="{{ \App\Enum\PaymentStatus::FAILED }}">Failed</option>
                    <option value="{{ \App\Enum\PaymentStatus::CANCELLED }}">Cancelled</option>
                    <option value="{{ \App\Enum\PaymentStatus::PARTIALLY }}">Paid Partially</option>
                </select>
            </div>

            <div class="col-md-12 text-center">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary ml-2" wire:click="filter " wire:loading.attr="disabled">
                        <i class="bi bi-filter-square-fill mr-1" wire:loading.attr="disabled"></i>
                            Apply Filter
                    </button>

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
    </form>
</div>
