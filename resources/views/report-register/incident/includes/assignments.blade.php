<ul class="timeline">
    @foreach($incident->assignees ?? [] as $assignment)
        <li>
            <a>Performed Assignment</a>
            <a class="float-right">{{ $assignment->created_at->format('d M, Y H:i') }}</a>
            <p>{{ $assignment->assigner->fullname ?? '' }} assigned {{ $assignment->assignee->fullname ?? '' }}</p>
        </li>
    @endforeach
</ul>

