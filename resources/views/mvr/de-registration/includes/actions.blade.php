<a href="{{ route('mvr.de-registration.show', encrypt($row->id)) }}" class="btn btn-primary btn-sm" data-toggle="tooltip"
   data-placement="right" title="View"><i class="fa fa-eye"></i> View </a>

@if($row->status === \App\Enum\MvrRegistrationStatus::CORRECTION)
    <a href="{{ route('mvr.de-registration.update', encrypt($row->id)) }}" class="btn btn-info btn-sm" data-toggle="tooltip"
       data-placement="right" title="Correct"><i class="fa fa-edit"></i> Correct </a>
@endif
