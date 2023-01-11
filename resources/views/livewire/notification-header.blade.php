<div class="notification">
    <div class="btn-group">
        <a style="color: #863d3c; font-size: 16px;" class="bi bi-bell-fill btn btn-link" title="Notifications" href="{{ route('notifications') }}"></a>
        @if ($hasUnreadNotifications)
            <span class="badge">{{ $unreadNotificationsCount }}</span>
        @endif
    </div>
    <div class="btn-group">
        <a style="color: #863d3c; font-size: 16px;" class="bi bi-gear-wide-connected btn btn-link" title="Settings" href="{{ route('account') }}"></a>
    </div>
</div>