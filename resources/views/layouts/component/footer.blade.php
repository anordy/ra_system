<livewire:modals />

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

<x-livewire-alert::scripts />

@yield('scripts')

<script>
    window.onload = function() {
        Livewire.hook('message.sent', () => {
            window.dispatchEvent(
                new CustomEvent('loading', { detail: { loading: true }})
            );
        })
        Livewire.hook('message.processed', (message, component) => {
            window.dispatchEvent(
                new CustomEvent('loading', { detail: { loading: false }})
            );
        })
    }
</script>
