<div>
    <div>
        <div class="text-left"><button class="btn btn-sm btn-outline-danger mb-2" wire:click="deleteSelected">Delete
                Selected</button></div>
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>Time</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($notifications) > 0)
                @foreach ($notifications as $row)
                <tr>
                    <th><input type="checkbox" wire:model="selectedItems.{{ $row->id }}"></th>
                    <td> @if($row->read_at)<strong>{{ $row->created_at->diffForHumans() ?? '' }}</strong>@else {{ $row->created_at->diffForHumans() ?? '' }} @endif </td>
                    <td> @if($row->read_at)<strong>{{ $row->data->subject ?? '' }}</strong>@else {{ $row->data->subject ?? '' }} @endif </td>
                    <td> @if($row->read_at)<strong>{{ $row->data->message ?? '' }}</strong>@else {{ $row->data->message ?? '' }} @endif </td>
                    <td>
                        <button class="btn btn-sm btn-outline-success" wire:click="read({{$row}})">@if($row->read_at)<strong>view</strong> @else view @endif</button>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="5" class="text-center">There are no new notifications for you at the moment</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>