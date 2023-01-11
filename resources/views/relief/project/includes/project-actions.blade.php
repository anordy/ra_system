@can('relief-projects-configure')
    <a href="{{ route('reliefs.projects.edit', encrypt($row->id)) }}" class="btn btn-info btn-sm" data-toggle="tooltip"
        data-placement="right" title="View">Configure</a>
@endcan
