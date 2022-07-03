@foreach ($this->getEnabledTranstions() as $transition)
<div class="card p-2 m-0 mb-2">
    <div class="row my-2">
        <div class="col-md-4 ">
            <span class="font-weight-bold text-uppercase">Transition Name</span>
            <p class="my-1">{{ $transition->getName() ?? null }}</p>
        </div>
        <div class="col-md-4 ">
            <span class="font-weight-bold text-uppercase">From</span>
            <p class="my-1">{{ $transition->getFroms()[0] ?? null }}</p>
        </div>
        <div class="col-md-4 ">
            <span class="font-weight-bold text-uppercase">To</span>
            <p class="my-1">{{ $transition->getTos()[0] ?? null }}</p>
        </div>
    </div>
</div>
@endforeach