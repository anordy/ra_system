<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Enroll Finger</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row p-2">

                    <div class="col-md-12 mb-2 d-flex justify-content-end">
                        <input type="submit" class="btn btn-outline-info btn-sm" name="host_connect" id="host_connect"
                            value="Reconnect!" /> &nbsp;
                        <input type="submit" class="btn btn-outline-danger btn-sm" name="host_close" id="host_close"
                            value="Close!" />
                    </div>

                    @if ($errors->any())
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif


                    <div class="col-md-12 mb-2">
                        <table width="100%" border="1" cellspacing="0" class="p-2">
                            <tr align="center">
                                <td width="30%" class="p-2">
                                    @if ($image)
                                        <img src="data:image/png;base64,{{ $image }}" alt=""
                                            width="256" height="288" id="imgDiv" align="middle" />
                                    @else
                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAkCAYAAABIdFAMAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHhJREFUeNo8zjsOxCAMBFB/KEAUFFR0Cbng3nQPw68ArZdAlOZppPFIBhH5EAB8b+Tlt9MYQ6i1BuqFaq1CKSVcxZ2Acs6406KUgpt5/LCKuVgz5BDCSb13ZO99ZOdcZGvt4mJjzMVKqcha68iIePB86GAiOv8CDADlIUQBs7MD3wAAAABJRU5ErkJggg=="
                                            alt="" width="256" height="288" id="imgDiv"
                                            align="middle" />
                                    @endif

                                </td>
                            </tr>

                            <tr>
                                <td align="center" class="p-2">
                                    <input type="text" id="state" value="" readonly style="width: 50%"
                                        class="form-control text-center" />
                                </td>
                            </tr>
                            <td align="center" class="p-2">
                                <input type="button" value="Enrol Template" id="EnrollTemplate"
                                    class="btn btn-outline-info btn-sm">
                            </td>

                        </table>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" class="form-control" wire:model='image' />
                            <input type="hidden" class="form-control" wire:model='template' />
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit'>Save changes</button>
            </div>
        </div>
    </div>
</div>
