    <a href="{{ route('chartered.show', encrypt($row->id)) }}" class="btn btn-info btn-sm" data-toggle="tooltip"
        data-placement="right" title="View">
        {{ __('View') }}
    </a>

    @if ($row->vetting_status == \App\Enum\VettingStatus::CORRECTION)
        <a href="{{ route('chartered.edit', encrypt($value)) }}" class="btn btn-success btn-sm"
            data-toggle="tooltip" data-placement="right" title="Edit">
            Edit
        </a>
    @endif
