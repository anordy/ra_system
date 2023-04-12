@if ($row->vetting_status == \App\Enum\VettingStatus::VETTED)
    <span class="p-2 badge badge-success">
        Vetted
    </span>
@elseif ($row->vetting_status == \App\Enum\VettingStatus::CORRECTION)
    <span class="p-2 badge badge-warning">
        On Correction
    </span>
@elseif ($row->vetting_status == \App\Enum\VettingStatus::CORRECTED)
    <span class="p-2 badge badge-primary">
        Corrected
    </span>
@else
    <span class="p-2 badge badge-warning">
        {{ $row->vetting_status }}
    </span>
@endif
