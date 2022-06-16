
let modalsElement = document.getElementById('laravel-livewire-modals');

modalsElement.addEventListener('hidden.bs.modal', () => {
    Livewire.emit('resetModal');
});

Livewire.on('showBootstrapModal', () => {
    $(modalsElement).modal('show');
});

Livewire.on('hideModal', () => {
    $(modalsElement).modal('hide');
});