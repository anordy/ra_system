<a href="{{ route('payments.pbz.statement', encrypt($row->id)) }}" class="btn btn-primary btn-sm">
    <i class="bi bi-eye-fill mr-1"></i>
    View
</a>
<button wire:click="confirmPopUpModal('{{ encrypt($row->id) }}')" class="btn btn-danger btn-sm">
    <i class="bi bi-trash3-fill mr-1"></i>
    Delete
</button>