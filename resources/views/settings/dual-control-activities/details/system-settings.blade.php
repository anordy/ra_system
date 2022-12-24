<div>

    <div class="card">
        <div class="card-header">Original Values</div>
        <div class="card-body">
            <div class="row m-2 pt-3">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Name</span>
                    <p class="my-1">{{ $data->name }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Code</span>
                    <p class="my-1">{{ $data->code }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Vakue</span>
                    <p class="my-1">{{ $data->value }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Unit</span>
                    <p class="my-1">{{ $data->unit }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <span class="font-weight-bold text-uppercase">Description</span>
                    <p class="my-1">{{ $data->description }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Created At</span>
                    <p class="my-1">{{ $data->created_at }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Created At</span>
                    <p class="my-1">{{ $data->updated_at }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Status</span>
                    <p class="my-1">
                        @if ($data->is_approved == 0)
                            <span style=" background: #dc354559; color: #cf1c2d; font-size: 85%"
                                  class="badge badge-danger p-2">Pending</span>
                        @elseif($data->is_approved == 1)
                            <span style=" background: #72DC3559; color: #319e0a; font-size: 85%"
                                  class="badge badge-success p-2">Approved</span>
                        @else
                            <span style=" background: #dc354559; color: #cf1c2d; font-size: 85%"
                                  class="badge badge-danger p-2">Rejected</span>
                        @endif
                    </p>
                </div>

            </div>
        </div>
    </div>

    @if($edited_values)
        <div class="card">
            <div class="card-header">Edited Values</div>
            <div class="card-body">
                <div class="row m-2 pt-3">
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">First Name</span>
                        <p class="my-1">{{ $edited_values->fname }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Last Name</span>
                        <p class="my-1">{{ $edited_values->lname }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Phone Number</span>
                        <p class="my-1">{{ $edited_values->phone }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Email</span>
                        <p class="my-1">{{ $edited_values->email }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Gender</span>
                        <p class="my-1">
                            @if ($edited_values->gender == 'M')
                                <span >Male</span>
                            @else
                                <span>Female</span>
                            @endif
                        </p>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>