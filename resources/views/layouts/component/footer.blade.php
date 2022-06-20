<livewire:modals />

<script src="{{ asset('plugins/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert/sweetalert2.min.js') }}"></script>
<script src="{{ asset('plugins/fontawesome/js/fontawesome.min.js') }}"></script>
<script src="{{ asset('plugins/fontawesome/js/solid.min.js') }}"></script>
<script src="{{ asset('plugins/fontawesome/js/regular.js') }}"></script>
<script src="{{ asset('js/alpine.min.js') }}"></script>
<script src="{{ asset('js/modals.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<x-livewire-alert::scripts />

@yield('scripts')
