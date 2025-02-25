@props(['name', 'action'])

<div x-data="{
        confirmAction() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you really want to proceed?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire[props.action](...props.params); // Call the Livewire method with parameters
                }
            });
        }
    }" x-bind:props="{{ json_encode($attributes->get('props')) }}">

    <button @click="confirmAction" class="btn btn-primary">
        {{ $name }}
    </button>
</div>
