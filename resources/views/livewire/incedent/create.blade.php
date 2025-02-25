<div class="card">

    <div class="card-header text-uppercase font-weight-bold bg-white">
        {{ __('Create Incedent') }}

        <div class="col-md-12 d-flex justify-content-end">
            <span class="font-weight-bold text-uppercase mr-2">{{ __('Real Issue') }}</span>
            <p class="my-1 mb-1"><input type="checkbox" wire:model.lazy="isRealIssue"></p>
        </div>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered align-middle">
                    <tbody>
                        <tr class="text-center">
                            <th colspan="2" class="table-active">Name of the Incedent</th>
                            <th colspan="1" class="table-active">Source Channel</th>
                            <th colspan="1" class="table-active">Reported By</th>
                            <th colspan="1" class="table-active">Problem Owner</th>
                            <th colspan="1" class="table-active">Incedent Report Date</th>
                        </tr>

                        <tr>
                            <td colspan="2" >
                                <div class="input-group">
                                    <input type="text" class="form-control @error('name')  is-invalid @enderror"
                                        wire:model="name" />
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </td>
                            <td>
                                <div class="form-group col-md-12">
                                    <select class="form-control" id="bankChannelId" wire:model="bankChannelId">
                                        @foreach ($bankChannels as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bankChannelId')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </td>

                            <td>
                                <div class="form-group col-md-12">
                                    <select class="form-control" wire:model="reportedBy">
                                        <option>--select--</option>
                                        @foreach ($users as $row)
                                            <option value="{{ $row->id }}">{{ $row->fullname }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('reportedBy')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </td>
                            <td>
                                <div class="form-group col-md-12">
                                    <select class="form-control" wire:model="ownerId">
                                        <option>--select--</option>
                                        @foreach ($users as $row)
                                            <option value="{{ $row->id }}">{{ $row->fullname }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ownerId')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="date" class="form-control @error('reportDate')  is-invalid @enderror"
                                        wire:model="reportDate" />
                                    @error('reportDate')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </td>
                        </tr>
                        <tr class="text-center">
                            <th colspan="1" class="table-active">Impact Revenue</th>
                            <th colspan="1" class="table-active">Impact Customer</th>
                            <th colspan="1" class="table-active">Impact System</th>
                            <th colspan="1" class="table-active">Affected System</th>
                            <th colspan="2" class="table-active">Affected Revenue Stream</th>
                        </tr>

                        <tr>
                            <td>
                                <div class="form-group col-md-12">
                                    <select class="form-control" wire:model="impactRevenue">
                                        <option>--select--</option>
                                        <option value="high">High
                                        </option>
                                        <option value="low">
                                            Medium
                                        </option>
                                        <option value="low">
                                            Low
                                        </option>
                                    </select>
                                    @error('impactRevenue')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </td>
                            <td>
                                <div class="form-group col-md-12">
                                    <select class="form-control" wire:model="impactCustomer">
                                        <option>--select--</option>

                                        <option value="high">High
                                        </option>
                                        <option value="low">
                                            Medium
                                        </option>
                                        <option value="low">
                                            Low
                                        </option>
                                    </select>
                                    @error('impactCustomer')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </td>
                            <td>
                                <div class="form-group col-md-12">
                                    <select class="form-control" wire:model.live="impactSystem">
                                        <option>--select--</option>

                                        <option value="high">High
                                        </option>
                                        <option value="low">
                                            Medium
                                        </option>
                                        <option value="low">
                                            Low
                                        </option>
                                    </select>
                                    @error('impactSystem')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            <td>
                                <div class="form-group col-md-12">
                                    <select class="form-control" wire:model="bankSystemId" id="bankSystemId">
                                        <option>--select--</option>
                                        @foreach ($systems as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bankSystemId')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </td>
                            <td colspan="2">
                                <div class="input-group">
                                    <input type="text"  class="form-control @error('affectedRevenue')  is-invalid @enderror"
                                        wire:model="affectedRevenue" />
                                    @error('affectedRevenue')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </td>
                           
                        </tr>


                        <tr class="text-center">
                            <th colspan="2" class="table-active">Describe Action Taken (Options/Resolution)</th>
                            <th colspan="2" class="table-active">Describe the symptom of the Incident <br> and how it is identified</th>
                            <th colspan="2" class="table-active">Addittional value (RA)</th>

                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="form-group col-lg-12">
                                    <textarea class="form-control" wire:model="actionTaken" id="description" rows="4" required></textarea>
                                    @error('actionTaken')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </td>
                            <td colspan="2">
                                <div class="form-group col-lg-12">
                                    <textarea class="form-control" wire:model="symptomIncedent" id="symptomIncedent" rows="4" required></textarea>
                                    @error('symptomIncedent')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </td>
                            <td colspan="2">
                                <div class="form-group col-lg-12">
                                    <textarea class="form-control" wire:model="additionRA" id="additionRA" rows="4" required></textarea>
                                    @error('additionRA')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </td>
                            
                        </tr>

                        {{-- <tr class="text-center">
                            <th colspan="2" class="table-active">Revenue Leakage</th>
                            <th colspan="1" class="table-active">Detected</th>
                            <th colspan="1" class="table-active">Prevented</th>
                            <th colspan="1" class="table-active">Recovered</th>
                            <th colspan="1" class="table-active"></th>
                        </tr> --}}

                        {{-- <tr>
                            <td colspan="2" class="text-center">
                                <div class="form-group col-md-12">
                                    <select class="form-control" wire:model.live="impactSystem">
                                        <option>--select--</option>

                                        <option value="TZS">TZS
                                        </option>
                                        <option value="USD">USD
                                            
                                        </option>
                                        <option value="GBP">
                                            GBP
                                        </option>
                                    </select>
                                    @error('impactSystem')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" x-data x-mask:dynamic="$money($input)" value="10"
                                        class="form-control @error('revenuDetected')  is-invalid @enderror"
                                        wire:model="revenuDetected" />
                                    @error('revenuDetected')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </td>

                            <td>
                                <div class="input-group">
                                    <input type="text" x-data x-mask:dynamic="$money($input)" value="10"
                                        class="form-control @error('revenuePrevented')  is-invalid @enderror"
                                        wire:model="revenuePrevented" />
                                    @error('revenuePrevented')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" x-data x-mask:dynamic="$money($input)" value="10"
                                        class="form-control @error('revenueRecovered')  is-invalid @enderror"
                                        wire:model="revenueRecovered" />
                                    @error('revenueRecovered')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </td>
                           <td colspan="2" class="table-active"></td>
                        </tr> --}}
                        <tr class="text-center">
                            <th colspan="1" class="table-active">Type</th>
                            <th colspan="1" class="table-active">Currency</th>
                            <th colspan="1" class="table-active">Detected</th>
                            <th colspan="1" class="table-active">Prevented</th>
                            <th colspan="1" class="table-active">Recovered</th>
                            <th colspan="1" class="table-active">
                                <button class="btn btn-secondary mr-2" wire:click="addEntry()">
                                <i class="bi bi-plus-circle mr-1"></i>
                                Add 
                            </button></th>
                        </tr>
                        @foreach ($leakages as $i => $row)
                        <tr>
                                <td>
                                    <div class="form-group col-md-12">
                                        <select class="form-control" wire:model.lazy="leakages.{{ $i }}.type" id="leakages.{{ $i }}.type">
                                            <option>--select--</option>
                                            <option value="Revenue Loss">Revenue Loss</option>
                                            <option value="Overcharging">Overcharging</option>
                                        </select>
                                        @error('leakages.{{ $i }}.type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                               
                            </td>
                            <td>
                                <div class="form-group col-md-12">
                                    <select class="form-control" wire:model.lazy="leakages.{{ $i }}.currency" id="leakages.{{ $i }}.currency">
                                        <option>--select--</option>
                                        @foreach ($currencies as $row)
                                            <option value="{{ $row->code }}">{{ $row->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('leakages.' . $i . '.currency')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </td>
                            <td>
                                <div class="input-group @error('row.' . $i) is-invalid @enderror">
                                    <input class="form-control @error('leakages.' . $i . '.detected') is-invalid @enderror"
                                            wire:model.lazy="leakages.{{ $i }}.detected" x-data x-mask:dynamic="$money($input)" />
                                    @error('leakages.' . $i . '.detected')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </td>
                            <td>
                                <div class="input-group @error('row.' . $i) is-invalid @enderror">
                                    <input
                                            class="form-control @error('leakages.' . $i . '.prevented') is-invalid @enderror"
                                            wire:model.lazy="leakages.{{ $i }}.prevented" x-data x-mask:dynamic="$money($input)" />
                                    @error('leakages.' . $i . '.prevented')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </td>
                            <td>
                                <div class="input-group @error('row.' . $i) is-invalid @enderror">
                                    <input
                                            class="form-control @error('leakages.' . $i . '.recovered') is-invalid @enderror"
                                            wire:model.lazy="leakages.{{ $i }}.recovered" x-data x-mask:dynamic="$money($input)" />
                                    @error('leakages.' . $i . '.recovered')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </td>
                            <td style="min-width: 100%">
                                @if (count($leakages) > 1)
                                    <div class="text-right mt-2">
                                        <button class="btn btn-danger btn-sm"
                                                wire:click="removeRow({{ $i }})">
                                            <i class="bi bi-x-lg mr-1"></i>
                                            <small> {{ __('Remove') }} </small>
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>


        </div>

        <div class="d-flex justify-content-end">
            
            <button class="btn btn-primary rounded-0" wire:click="submit()" wire:loading.attr="disable">
                <i class="bi bi-arrow-return-right mr-2" wire:loading.remove wire:target="submit"></i>
                <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                   wire:target="search"></i>
                {{ __('Submit') }}
            </button>
        </div>
    </div>

</div>
