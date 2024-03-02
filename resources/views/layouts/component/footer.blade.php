<livewire:modals/>
<script src="{{ asset('plugins/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert/sweetalert2.min.js') }}"></script>
<script src="{{ asset('plugins/fontawesome/js/fontawesome.min.js') }}"></script>
<script src="{{ asset('plugins/fontawesome/js/solid.min.js') }}"></script>
<script src="{{ asset('plugins/fontawesome/js/regular.js') }}"></script>
<script src="{{ asset('js/x-mask.js') }}" defer></script>
<script src="{{ asset('js/alpine.min.js') }}" defer></script>
<script src="{{ asset('js/modals.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
@yield('scripts')
@stack('scripts')
<x-livewire-alert::scripts/>
<script>
    window.onload = function () {
        Livewire.hook('message.sent', () => {
            window.dispatchEvent(
                new CustomEvent('loading', {
                    detail: {
                        loading: true
                    }
                })
            );
        })
        Livewire.hook('message.processed', (message, component) => {
            window.dispatchEvent(
                new CustomEvent('loading', {
                    detail: {
                        loading: false
                    }
                })
            );
        })
    }

    document.addEventListener('livewire:load', function () {
        Livewire.on('alert', function (alert) {
            // create the alert HTML
            var html = '<div class="alert alert-' + alert.type + '">';

            // add the message
            html += '<p>' + alert.message + '</p>';

            // add the close button
            if (alert.options.showCloseButton) {
                html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                html += '<span aria-hidden="true">&times;</span>';
                html += '</button>';
            }

            // close the alert when the close button is clicked
            if (alert.options.showCloseButton) {
                html += '<script>';
                html +=
                    'document.querySelector(".alert .close").addEventListener("click", function() {';
                html += 'this.closest(".alert").remove();';
                html += '});';
                html += '<//script>';
            }

            html += '<//div>';
            document.body.insertAdjacentHTML('beforeend', html);
        });
    });

</script>
