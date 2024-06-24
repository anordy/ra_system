@if ($row->status === 'complete')
<span class="badge badge-success py-1 px-2">
    Paid
</span>
@else
<span class="badge badge-success py-1 px-2">
    Not Paid
</span>
@endif