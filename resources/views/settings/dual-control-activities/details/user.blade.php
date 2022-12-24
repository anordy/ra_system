<div>

    <div class="card">
        <div class="card-header">
            @if($result->action == \App\Models\DualControl::ADD)
                Added Values
            @else
                Old Values
            @endif
        </div>
        <div class="card-body">
            <div class="row m-2 pt-3">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">First Name</span>
                    <p class="my-1">{{ $old_values->fname }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Last Name</span>
                    <p class="my-1">{{ $old_values->lname }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Phone Number</span>
                    <p class="my-1">{{ $old_values->phone }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Email</span>
                    <p class="my-1">{{ $old_values->email }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Gender</span>
                    <p class="my-1">
                        @if ($old_values->gender == 'M')
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
                        <span class="font-weight-bold text-uppercase">First Name</span>
                        <p class="my-1">{{ $new_values->fname }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Last Name</span>
                        <p class="my-1">{{ $new_values->lname }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Phone Number</span>
                        <p class="my-1">{{ $new_values->phone }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Email</span>
                        <p class="my-1">{{ $new_values->email }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Gender</span>
                        <p class="my-1">
                            @if ($new_values->gender == 'M')
                                <span>Male</span>
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