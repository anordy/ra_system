@foreach($incident->attachments ?? [] as $attachment)
    <div class="col-md-3">
        <div
                class="p-2 mb-3 d-flex rounded-sm align-items-center highlighted-file-box">
            <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
            <a target="_blank"
               href="{{ route('report-register.file', encrypt($attachment->path)) }}"
               class="ml-1 font-weight-bold">
                {{ $attachment->name ?? 'N/A' }}
                <i class="bi bi-arrow-up-right-square ml-1"></i>
            </a>
        </div>
    </div>
@endforeach
