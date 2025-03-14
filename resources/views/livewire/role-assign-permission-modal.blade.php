<div>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Assign Permissions</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <table class="table table-sm table-bordered w-100" id="datatable">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Module</th>
                                <th>CRUD Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($modules as $module)
                                <tr>
                                    <td widht="5%">{{ $module->id }}</td>
                                    <td width="25%">{{ $module->name }}</td>
                                    <td>
                                        <div class="row">
                                            @foreach ($permissions as $permission)
                                                @if ($permission->sys_module_id == $module->id)
                                                    @if ($role->hasAccess($permission->name))
                                                        <div class="col-md-4 col-sm-6">
                                                            <label>
                                                                <input type="checkbox" value="{{ $permission->id }}"  wire:model.defer="selectedPermissions">
                                                                {{  str_replace("_"," ",$permission->name) }}
                                                            </label>
                                                        </div>
                                                    @else
                                                        <div class="col-md-4 col-sm-6">
                                                            <label>
                                                                <input type="checkbox" value="{{ $permission->id }}" wire:model.defer="selectedPermissions">
                                                                {{  str_replace("_"," ",$permission->name) }}
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
                    <div wire:loading.delay wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Save changes</button>
            </div>
        </div>
    </div>
</div>
