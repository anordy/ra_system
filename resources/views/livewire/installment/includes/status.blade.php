@if ($extension->status  == 'approved')
    <span class="badge badge-success p-2  opacity-75" style="opacity: 75%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{$extension->status }}
    </span>
@elseif ($extension->status  == 'rejected')
    <span class="badge badge-danger p-2 bg-opacity-25" style="opacity: 75%">
        <i class="bi bi-trash mr-1 "></i>
        {{$extension->status }}
    </span>
@elseif ($extension->status  == 'pending')
    <span class="badge badge-warning p-2 bg-opacity-25" style="opacity: 75%">
        <i class="bi bi-clock"> </i>
        {{$extension->status }}
    </span>

@else
    <span class="badge badge-info p-2 bg-opacity-25 " style="opacity: 75%">
        <i class="bi bi-exclamation"></i>
        {{ $extension->status  }}
    </span>
@endif
