<a href="{{ route('mvr.temporary-transports.show', encrypt($row->id)) }}" class="btn btn-primary btn-sm" data-toggle="tooltip"
   data-placement="right" title="View"><i class="fa fa-eye"></i> View </a>

@if($row->status === \App\Enum\MvrTemporaryTransportStatus::CORRECTION)
    <a href="{{ route('mvr.registration.update', encrypt($row->id)) }}" class="btn btn-info btn-sm" data-toggle="tooltip"
       data-placement="right" title="Correct"><i class="fa fa-edit"></i> Correct </a>
@endif
