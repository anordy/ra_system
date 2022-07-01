<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">View Changes</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                @if($event=='logged in' || $event == 'logged out')
                    <h6 class="text-uppercase">{{ $event }} at: {{ $created_at }}</h5>
                @endif

                @if($event == 'updated' || $event == 'created' || $event == 'deleted')
                    @if($tags == 'activated' || $tags == 'deactivated')
                        <p>User {{ $new_values }} was {{ $tags }} at {{ $created_at }}</p>
                    @else
                    <div class="row">
                        <div class="col">
                            <h6 class="text-uppercase">Old Values</h6>
                            @foreach (json_decode($old_values, true) as $key => $value)
                                <strong>{{ $key }}</strong> : {{ $value }} <br>
                            @endforeach
                        </div>
                        <div class="col">
                            <h6 class="text-uppercase">New Values</h6>
                            @foreach (json_decode($new_values, true) as $key => $value)
                                <strong>{{ $key }}</strong> : {{ $value }} <br>
                            @endforeach
                        </div>
                    </div>           
                    @endif
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
