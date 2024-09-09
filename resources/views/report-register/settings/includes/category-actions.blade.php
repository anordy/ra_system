<div>
    <button class="btn btn-primary btn-sm"
            onclick="Livewire.dispatch('showModal', {alias: 'report-register.settings.category.edit-category', params: '{{ encrypt($value) }}' })">
        <i class="bi bi-pen-fill"></i>
    </button>
    <a href="{{ route('report-register.settings.sub-category', encrypt($value)) }}"
       class="btn btn-primary btn-sm">
        <i class="bi bi-eye-fill mr-1"></i>
    </a>
</div>
