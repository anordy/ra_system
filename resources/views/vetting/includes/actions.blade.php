@if (
    $row->vetting_status == \App\Enum\VettingStatus::SUBMITTED ||
        $row->vetting_status == \App\Enum\VettingStatus::CORRECTED)
    <a href="{{ route('tax_vettings.show', encrypt($value)) }}" class="btn btn-info btn-sm" data-toggle="tooltip"
        data-placement="right" title="View">
        Review & Approve
    </a>
@else
    <a href="{{ route('tax_vettings.show', encrypt($value)) }}" class="btn btn-info btn-sm" data-toggle="tooltip"
        data-placement="right" title="View">
        View
    </a>
@endif
