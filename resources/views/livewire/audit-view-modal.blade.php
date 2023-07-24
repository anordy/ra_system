<div>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">View Changes</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row m-4">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Staff Name</span>
                        <p class="my-1">{{ $audit->user->fname ?? '' }} {{ $audit->user->lname ?? '' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Event Performed</span>
                        <p class="my-1">{{ $audit->event }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Performed At</span>
                        <p class="my-1">
                            @php
                                $model = preg_split('[\\\]', $audit->auditable_type)[2];
                                $label = preg_replace('/(?<=[a-z])[A-Z]|[A-Z](?=[a-z])/', ' $0', $model);
                                echo "{$label}: ";
                            @endphp
                            {{ $audit->auditable->name ?? '' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">IP Address</span>
                        <p class="my-1">{{ $audit->ip_address }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Event Time</span>
                        <p class="my-1">{{ $audit->created_at }}</p>
                    </div>
                    <div class="col-md-8 mb-9">
                        <span class="font-weight-bold text-uppercase">User Agent</span>
                        <p class="my-1">{{ $audit->user_agent }}</p>
                    </div>

                </div>

                <div class="row m-4">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <th style="width: 20%">Property</th>
                            <th style="width: 30%">Old Values</th>
                            <th style="width: 30%">New Values</th>
                        </thead>

                        @if ($audit->event == 'created')
                        <tbody>
                            @foreach (json_decode($new_values, true) as $key => $value)
                                <tr>
                                    <th>{{ str_replace('_', ' ', $key) }}</th>
                                    <td style="background: #ffe9e9">
                                        @php
                                            $old_changes = json_decode($old_values);
                                        @endphp
                                        {{ $old_changes->$key ?? 'N/A' }}
                                    </td>
                                    <td style="background: #e9ffe9">{{ $value ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        @endif

                        @if ($audit->event == 'updated' ||
                            $audit->event == 'deleted' ||
                            $audit->event == 'deactivated' ||
                            $audit->event == 'activated')
                            <tbody>
                            @if($isOld)
                                @foreach (json_decode($old_values, true) as $key => $value)
                                    <tr>
                                        <th>{{ str_replace('_', ' ', $key) }}</th>
                                        <td style="background: #ffe9e9">{{ $value ?? 'N/A' }}</td>
                                        <td style="background: #e9ffe9">
                                            @php
                                                $new_changes = json_decode($new_values);
                                            @endphp
                                            {{ $new_changes[$key] ?? 'N/A' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @foreach (json_decode($new_values, true) as $key => $value)
                                    <tr>
                                        @php
                                            $old_data = json_decode($old_values);
                                        @endphp
                                        <th>{{ str_replace('_', ' ', $key) }}</th>
                                        @if (is_array($old_data))
                                            <td style="background: {{ count($old_data) > 0 ? compareDualControlValues($old_data, $value) ? '#e9ffe9' : '#ffe9e9' : '#ffe9e9' }}">
                                                {{ $old_data[$key] ?? 'N/A' }}
                                            </td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td style="background: #e9ffe9">{{ $value ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        @endif
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
