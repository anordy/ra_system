<div>

    <div class="card">
        <div class="card-header">
            User Role Detail
        </div>
        <div class="card-body">
            <div class="row m-2 pt-3">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">First Name</span>
                    <p class="my-1">{{ $data->fname   }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Last Name</span>
                    <p class="my-1">{{ $data->lname  }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Phone Number</span>
                    <p class="my-1">{{ $data->phone  }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Email</span>
                    <p class="my-1">{{ $data->email  }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Role</span>
                    <p class="my-1">{{ $data->role->name  }}</p>
                </div>


                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Gender</span>
                    <p class="my-1">
                        @if ($data->gender == 'M')
                            <span>Male</span>
                        @else
                            <span>Female</span>
                        @endif

                    </p>
                </div>

            </div>
        </div>
    </div>

    @if($new_values)
        <div class="card">
            <div class="card-header">New Values</div>
            <div class="card-body">
                <div class="row m-2 pt-3">
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Role</span>
                        <p class="my-1">{{ getRole($new_values->role_id) }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>