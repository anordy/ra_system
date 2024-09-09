<div>
    <button class="btn btn-primary btn-sm"
            onclick="Livewire.emit('showModal', 'report-register.settings.category.edit-category', '{{ encrypt($value) }}')">
        <i class="bi bi-pen-fill"></i>
    </button>
    <a href="{{ route('report-register.settings.sub-category', encrypt($value)) }}"
       class="btn btn-primary btn-sm">
        <i class="bi bi-eye-fill mr-1"></i>
    </a>
</div>
