@if ($row->status === 'complete')
<span class="badge badge-success py-1 px-2 green-status">
    Paid
</span>
@else
<span class="badge badge-success py-1 px-2 danger-status">
    Not Paid
</span>
@endif