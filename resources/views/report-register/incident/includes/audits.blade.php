<ul class="timeline">
    @foreach($incident->audits ?? [] as $audit)
        <li>
            <a>{{ $audit->actor_name }}</a>
            <a class="float-right">{{ $audit->created_at->format('d M, Y H:i') }}</a>
            <p>{{ $audit->description ?? 'No Description' }}</p>
        </li>
    @endforeach
</ul>

