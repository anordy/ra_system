@if($row->duration == null)
    <span class="badge badge-info py-1 px-2">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Not applicable
    </span>
@else
    <span >
        {{$row->duration}}
    </span>
@endif