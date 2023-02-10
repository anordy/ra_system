<div class="ball-spinner" x-data="{ loading: false }" x-show="loading" @loading.window="loading = $event.detail.loading">
    <div class="la-ball-clip-rotate-multiple la-2x">
        <div></div>
        <div></div>
    </div>
</div>
{{--<div class="loading-container" x-data="{ loading: false }" x-show="loading" @loading.window="loading = $event.detail.loading">--}}
{{--    <div class="progress-bar">--}}
{{--      <div class="progress-bar-value"></div>--}}
{{--    </div>--}}
{{--</div>--}}