<nav class="top-navbar navbar navbar-expand-lg navbar-light bg-light py-2">
    <div class="container-fluid p-0 flex-nowrap">
        <div class="justify-content-start">
            <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-list"></i>
            </button>
            <span class="navbar-brand mb-0 px-3 py-0" style="font-size: 1rem; font-weight: 500; color: #863d3c;">@yield('title')</span>
        </div>

        <div class="justify-content-end">
            @livewire('notification-header')
        </div>
    </div>
</nav>