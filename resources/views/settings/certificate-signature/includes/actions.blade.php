@if (approvalLevel(Auth::user()->level_id, \App\Enum\GeneralConstant::MAKER))
    @can('setting-certificate-signature-edit')
        <button onclick="Livewire.emit('showModal', 'settings.certificate-signature.certificate-signature-edit-modal', '{{ encrypt($value) }}')" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil-square mr-1"></i>
            Edit
        </button>
    @endcan

    @can('setting-certificate-signature-view')
        <button onclick="Livewire.emit('showModal', 'settings.certificate-signature.certificate-signature-preview-modal', '{{ encrypt($value) }}')" class="btn btn-primary btn-sm">
            <i class="bi bi-eye mr-1"></i>
            Preview
        </button>
    @endcan
@endif


