<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">VFMS Data Integration {{$location->name}}</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
            @if($location['is_headquarter'])
                    <livewire:approval.znumber-verification :business="$business" />
                @else
                    <livewire:approval.znumber-location-verification :location="$location" />
                @endif
            </div>
        </div>
    </div>
</div>
