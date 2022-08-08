<div class="row">
    <div class="col-12">
        <a href="{{ route('reliefs.applications.show', encrypt($row->id)) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-eye-fill mr-1"></i> View
        </a>
        @if ($row->status != "approved")
        <a href="{{ route('reliefs.applications.edit', encrypt($row->id)) }}" class="btn btn-outline-warning btn-sm">
            <i class="bi bi-pencil-square mr-1"></i> Edit
        </a>
        <a href="" wire:click="deleteRelief({{ $row->id }})" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-x-circle-fill mr-1"></i> Delete
        </a>
        @endif
       
    </div>
</div>





