@if(count(request()->route()->parameters))
    <div class="bg-light clearfix mb-2">
        <span></span>
        <a href="{!! URL::previous() !!}" class="btn float-right main-color btn-sm px-3">
            <i class="bi bi-arrow-left pr-2"></i>Back
        </a>
    </div>
@endif

