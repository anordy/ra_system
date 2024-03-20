@if($row->is_citizen == 1)
    <span class="badge badge-info py-1 px-2">
        Local
    </span>
@else
    <span class="badge badge-info py-1 px-2">
        Foreigner
    </span>
@endif