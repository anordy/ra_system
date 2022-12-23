<div>
    <div class="row m-2 pt-3">
        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">First Name</span>
            <p class="my-1">{{ $data->fname }}</p>
        </div>

        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Last Name</span>
            <p class="my-1">{{ $data->lname }}</p>
        </div>

        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Phone Number</span>
            <p class="my-1">{{ $data->phone }}</p>
        </div>
        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Email</span>
            <p class="my-1">{{ $data->email }}</p>
        </div>

        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Gender</span>
            <p class="my-1">
                @if ($data->gender == 'M')
                    <span >Male</span>
                @else
                    <span>Female</span>
                @endif
            </p>
        </div>

        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Created At</span>
            <p class="my-1">{{ $data->created_at }}</p>
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