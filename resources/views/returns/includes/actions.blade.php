@can('setting-return-tax-type-view')
<a href="{{ route('settings.return-config.show', encrypt($row->id)) }}" class="btn btn-info btn-sm" data-toggle="tooltip"
   data-placement="right" title="View"><i class="bi bi-eye mr-1"></i>Configurations</a>
@endcan

@can('setting-return-tax-type-edit')
<a href="{{ route('settings.return-config.edit-tax-type', encrypt($row->id)) }}" class="btn btn-success btn-sm" data-toggle="tooltip"
   data-placement="right" title="Edit"><i class="bi bi-pencil mr-1"></i>Edit</a>
@endcan