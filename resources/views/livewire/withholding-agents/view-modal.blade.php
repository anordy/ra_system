<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="text-uppercase">View Withholding Agent</h5>
        </div>
        <div class="modal-body mx-4">
            <div class="card border-0">
                <h5 class="card-title text-uppercase">Main Details</h5>
                <div class="row">
                    <div class="col-3">
                        <span class="font-weight-bold">Withholding Agent Number</span>
                    </div>
                    <div class="col-5">
                        {{ $wa_number }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <span class="font-weight-bold">Tax Identification No. (TIN)</span>
                    </div>
                    <div class="col-5">
                        {{ $tin }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <span class="font-weight-bold">Institution Name</span>
                    </div>
                    <div class="col-5">
                        {{ $institution_name }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <span class="font-weight-bold">Address</span>
                    </div>
                    <div class="col-5">
                        {{ $address }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <span class="font-weight-bold">Place Of Institution</span>
                    </div>
                    <div class="col-5">
                        {{ $institution_place }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <span class="font-weight-bold">Contact Number</span>
                    </div>
                    <div class="col-5">
                        {{ $mobile }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <span class="font-weight-bold">Email Address</span>
                    </div>
                    <div class="col-5">
                        {{ $email }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <span class="font-weight-bold">Region</span>
                    </div>
                    <div class="col-5">
                        {{ $region }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <span class="font-weight-bold">District</span>
                    </div>
                    <div class="col-5">
                        {{ $district }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <span class="font-weight-bold">Ward</span>
                    </div>
                    <div class="col-5">
                        {{ $ward }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <span class="font-weight-bold">Date of Commencing</span>
                    </div>
                    <div class="col-5">
                        {{ $date_of_commencing }}
                    </div>
                </div>
            </div>

            <div class="card mt-4 border-0">
                <h5 class="card-title text-uppercase">Responsible Person Details</h5>
                <div class="row">
                    <div class="col-3">
                        <span class="font-weight-bold">Responsible Person Name</span>
                    </div>
                    <div class="col-5">
                        {{ $responsible_person_name }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <span class="font-weight-bold">Title</span>
                    </div>
                    <div class="col-5">
                        {{ $title }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <span class="font-weight-bold">Position</span>
                    </div>
                    <div class="col-5">
                        {{ $position }}
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
    </div>

</div>
</div>
