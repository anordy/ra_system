<div>
    <table class="table table-sm table-bordered table-hover">
        <label>Places Configurations</label>
        <tr>
            <th>Name</th>
            <th>Owner</th>
            <th>Operator Type</th>
            <th>Operators</th>
            <th>Action</th>
        </tr>

        @foreach ($places as $key => $row)
            <tr>
                <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                <td>{{ ucfirst($row['owner'] ?? '') }}</td>
                <td>{{ ucfirst($row['operator_type'] ?? '') }}</td>
                <td>
                    {{ getOperators($row['owner'] ?? null, $row['operator_type'] ?? null, $row['operators'] ?? null) }}
                </td>
                <td>
                    @if ($row['owner'] != 'taxpayer')
                        <button class="btn btn-outline-info btn-sm"
                            onclick="Livewire.emit('showModal', 'workflow.workflow-place-update-modal',{{ $workflow->id }},'{{ $key }}')"><i
                                class="fa fa-cog"></i>
                            Configure </button>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>

    <table class="table table-sm table-bordered">
        <label>Trasition Configurations</label>
        <tr>
            <th>Name</th>
            <th>From</th>
            <th>to</th>
        </tr>

        @foreach ($transitions as $key => $row)
            <tr>
                <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $row['from'] ?? '')) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $row['to'] ?? '')) }}</td>
            </tr>
        @endforeach

    </table>
</div>
