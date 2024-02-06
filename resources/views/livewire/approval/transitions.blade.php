<div class="row px-3">
    @foreach ($this->getEnabledTransitions() as $transition)
        <div class="col-md-6 card rounded-0">
            <div class="row my-2">
                <div class="col-md-4 ">
                    <span class="font-weight-bold text-uppercase">Transition Name</span>
                    <p class="m-0 text-uppercase">{{ str_replace('_', ' ', $transition->getName()) ?? null }}</p>
                </div>
                <div class="col-md-4 ">
                    <span class="font-weight-bold text-uppercase">From</span>
                    <p class="m-0 text-uppercase">{{ str_replace('_', ' ', $transition->getFroms()[0]) ?? null }}</p>
                </div>
                <div class="col-md-4 ">
                    <span class="font-weight-bold text-uppercase">To</span>
                    <p class="m-0 text-uppercase">{{ str_replace('_', ' ', $transition->getTos()[0]) ?? null }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>
