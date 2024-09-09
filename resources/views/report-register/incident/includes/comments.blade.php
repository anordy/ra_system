<h6>{{count($incident->comments ?? [])}} Comments</h6>

@foreach($incident->comments ?? [] as $comment)
    <div class="row">
        <div class="col-md-7">
            <hr>
            <a class="float-left px-2"><img class="media-object bg-secondary p-2"
                                       alt="{{ $comment->commenter_initials }}"></a>
            <div class="media-body px-2">
                <h5>{{ $comment->commenter_name }}</h5>
                <p class="px-5">{{ $comment->comment }}</p>
                <ul class="list-unstyled list-inline media-detail float-left">
                    <li><i class="bi bi-calendar-date"></i> {{ $comment->created_at->format('d M, Y H:i:s')  }}</li>
                </ul>
            </div>
        </div>
    </div>
@endforeach


