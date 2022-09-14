<a href="{{ route('petroleum.certificateOfQuantity.show', encrypt($value)) }}" class="btn btn-success btn-sm"
    data-toggle="tooltip" data-placement="right" title="View">View</a>

@if ($row->status != 'filled')
    <a href="{{ route('petroleum.certificateOfQuantity.edit', encrypt($value)) }}" class="btn btn-info btn-sm"
        data-toggle="tooltip" data-placement="right" title="View">Edit</a>
@endif

<a href="{{ route('petroleum.certificateOfQuantity.certificate', encrypt($value)) }}" class="btn btn-secondary btn-sm"
    data-toggle="tooltip" data-placement="right" title="View">Print Certificate</a>
