@props(['name', 'action', 'params' => [], 'actionMessage', 'icon' => null])

<div class="d-inline-block" x-data="{
        confirmAction() {
            Swal.fire({
                title: 'Are you sure?',
                text: '{{ $actionMessage }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed!',
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('{{ $action }}', ...{{ json_encode($params) }}); // Call the Livewire method with parameters
                }
            });
        }
    }">

    <button @click="confirmAction()" class="{{ $attributes->get('class', 'btn') }}">
        @if($icon)
            <i class="bi {{ $icon }} me-1"></i>
        @endif
        {{ $name }}
    </button>
</div>
