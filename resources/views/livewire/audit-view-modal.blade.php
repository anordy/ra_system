<div>
    <div class="modal-dialog modal-xl">
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

                @if($event == 'updated' || $event == 'created' || $event == 'deleted' || $event == 'deactivated' || $event == 'activated')
                    <div class="row m-4">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <th style="width: 20%">Property</th>
                                <th style="width: 30%">Old Values</th>
                                <th style="width: 30%">New Values</th>
                            </thead>
                            <tbody>
                        @foreach(json_decode($old_values, true) as $key => $value)
                        
                            <tr>
                                <th>{{ str_replace("_"," ",$key) }}</th>
                                <td style="background: #ffe9e9">{{ $value }}</td>
                                <td style="background: #e9ffe9">
                                @php
                                    $new_changes = json_decode($new_values);
                                @endphp
                                {{ $new_changes->$key }}
                                </td>
                            </tr>

                        @endforeach
                    </tbody>

                    </table>
                    </div>           
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
