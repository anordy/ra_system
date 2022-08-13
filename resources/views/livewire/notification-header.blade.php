<div class="notification">
    <div class="btn-group">
        <a class="bi bi-bell-fill btn btn-link" title="Notifications" href="{{ route('notifications') }}"></a>
        @if ($hasUnreadNotifications)
            <span class="badge">{{ $unreadNotificationsCount }}</span>
        @endif
    </div>
</div>