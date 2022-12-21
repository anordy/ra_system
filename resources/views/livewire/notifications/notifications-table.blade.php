<div>
    <div>
        <div class="text-left"><button class="btn btn-sm btn-outline-danger mb-2" wire:click="deleteSelected">Delete
                Selected</button></div>
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th scope="col"><input type="checkbox"></th>
                    <th scope="col">Subject</th>
                    <th scope="col">Message</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($notifications as $row)
                <tr>
                    <th><input type="checkbox" wire:model="selectedItems.{{ $row->id }}"></th>
                    <td>{{ $row->data->subject ?? '' }}</td>
                    <td>{{ $row->data->message ?? '' }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-success" wire:click="read({{$row}})">view</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>