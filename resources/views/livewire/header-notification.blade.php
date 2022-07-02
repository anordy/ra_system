<div class="notification">
    <div class="btn-group">
        <button class="bi bi-bell btn btn-link" title="Notifications" href="#" id="dropdownMenuTop"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
        @if($hasUnreadNotifications)
            <span class="badge">{{ $unreadNotificationsCount }}</span>
        @endif
        <div class="dropdown-menu dropdown-menu-right"
            style="width: 340px !important;">
            @if($hasUnreadNotifications)
                @foreach ($unreadNotifications as $row)
                <button class="dropdown-item" type="button" style="white-space: normal;">
                    <small style="">{{ $row->data['messageLong']}}</small> <br>
                    <a class="btn btn-link p-0 m-0" href="{{ $row->data['href'] }}" wire:click="viewNotification({{ $row }})">
                        <small>{{$row->data['hrefText']}} </small> 
                    </a>
                    <small class="text-muted float-right">{{ $row->created_at->diffForHumans() }}</small>
                </button>
                <div class="dropdown-divider"></div>
                @endforeach
            @else
                <button class="dropdown-item" type="button" disabled>
                    <span >You have no new notification</span> <br>
                </button>
            @endif


            {{-- <div class="dropdown-divider"></div> --}}
            {{-- <button class="dropdown-item" type="button">
                <a class="btn btn-link" href="">View All</a>
            </button> --}}
        </div>
    </div>
</div>