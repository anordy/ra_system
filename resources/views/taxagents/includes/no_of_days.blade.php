@if($row->is_citizen == 1)
    <span class="badge badge-info py-1 px-2"  style="border-radius: 1rem; background: #3582dc59; color: #0a5e9e; font-size: 85%">
        Local
    </span>
@else
    <span class="badge badge-info py-1 px-2">
        Foreigner
    </span>
@endif