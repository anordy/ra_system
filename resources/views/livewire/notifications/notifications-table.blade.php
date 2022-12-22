<div>
    <div>
        <div class="text-left"><button class="btn btn-sm btn-outline-danger mb-2" @if($notifications->isEmpty()) disabled @endif wire:click="deleteSelected">Delete
                Selected</button></div>
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th><input type="checkbox" @if($notifications->isEmpty()) disabled @endif></th>
                    <th>Time</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($notifications->isNotEmpty())
                @foreach ($notifications as $row)
                <tr>
                    <td><input type="checkbox" wire:model="selectedItems.{{ $row->id }}"></td>
                    <td>{!! $row->seen <= 1 ? '<strong>' : '' !!}{{ $row->created_at->diffForHumans() ?? '' }}{!!
                            $row->seen <= 1 ? '</strong>' : '' !!}</td>
                    <td>{!! $row->seen <= 1 ? '<strong>' : '' !!}{{ $row->data->subject ?? '' }}{!! $row->seen <= 1 ?
                            '</strong>' : '' !!}</td>
                    <td>{!! $row->seen <= 1 ? '<strong>' : '' !!}{{ $row->data->message ?? '' }}{!! $row->seen <= 1 ?
                            '</strong>' : '' !!}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-success" wire:click="read({{$row}})">
                            {!! $row->seen <= 1 ? '<strong>view</strong>' : 'view' !!}
                        </button>
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