<a href="{{ route('taxagents.verification-show', \Illuminate\Support\Facades\Crypt::encrypt($row->id)) }}" class="btn btn-outline-primary rounded-0 btn-sm">
    <i class="bi bi-eye mr-1"></i>
</a>

<button class="btn btn-outline-info btn-sm" wire:click="approve({{$row->id}})"><i class="fa fa-check"></i></button>
<button class="btn btn-outline-danger btn-sm" wire:click="reject({{$row->id}})"><i class="bi bi-x-circle"></i></button>
