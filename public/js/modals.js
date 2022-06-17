let modalsElement = document.getElementById('laravel-livewire-modals');

$(modalsElement).on('hidden.bs.modal', event => {
    Livewire.emit('resetModal');
});

Livewire.on('showBootstrapModal', () => {
    $(modalsElement).modal('show');
});

Livewire.on('hideModal', () => {
    $(modalsElement).modal('hide');
});