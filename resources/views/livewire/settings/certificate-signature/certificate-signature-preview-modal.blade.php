<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Preview Certificates</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <a target="_blank"
                           href="{{ route('business.certificate', ['location' => encrypt($location->id), 'type' => encrypt($taxType->tax_type_id)]) }}"
                           class="btn btn-success btn-sm mt-1 text-white">
                            <i class="bi bi-download"></i>
                            Preview Business Certificate
                        </a>
                    </div>

                    <div class="form-group col-lg-12">
                        <a target="_blank" href="{{ route('tax-clearance.certificate', encrypt($taxClearance->id)) }}"
                           class="btn btn-success btn-sm mt-1 text-white">
                            <i class="bi bi-download"></i>
                            Preview Tax Clearance Certificate
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
