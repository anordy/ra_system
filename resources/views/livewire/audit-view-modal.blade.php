<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">View Changes</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                @if($event=='logged in')
                {{-- <h6 class="text-uppercase">User: {{ $fname . ' ' . $lname}}</h5> --}}
                <h6 class="text-uppercase">Logged in at: {{ $created_at }}</h5>
                @else
                <h6 class="text-uppercase">Old Values</h5>
                    {{ $old_values }}
                <h6 class="text-uppercase">New Values</h5>
                    {{ $new_values }}
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
