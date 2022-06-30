@if($row->duration == null)
    <span class="badge badge-info py-1 px-2"  style="border-radius: 1rem; background: #3582dc59; color: #0a5e9e; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Not required
    </span>
@else
    <span >
        {{$row->duration}}
    </span>
@endif