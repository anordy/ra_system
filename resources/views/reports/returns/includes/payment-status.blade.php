@if($row->paid_at == null )
    <span class="badge badge-danger">Not Paid</span>
@else
    <span class="badge badge-success">Paid</span>
@endif