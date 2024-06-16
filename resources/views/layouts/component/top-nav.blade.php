@include('layouts.component.loader')
<nav class="top-navbar navbar navbar-expand-lg navbar-light bg-light py-2">
    <div class="container-fluid p-0 flex-nowrap">
        <div class="justify-content-start d-flex align-items-center">
            <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-list"></i>
            </button>
            <span class=" mb-0 px-3 py-0 font-weight-bold h6">@yield('title')</span>
        </div>

        <div class="justify-content-end">
            @livewire('notification.notification-header')
        </div>
    </div>
</nav>

