@extends('layouts.login')

@section('content')
<div class="container h-50">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Change Password</div>

                <div class="card-body">
                    <div class="py-3">

                        <strong>Welcome to ZRB, Since this is your first login you need to set new password for security purposes</strong>
                    </div>

                    <form method="POST" action="{{ route('password.save-changed') }}">
                        @csrf

                        <input type="hidden" name="user_id" value="{{ $id }}">

                        <div class="form-group row"  x-data="{pw: ''}">
                            <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>
                            <div class="col-md-6">
                                <input 
                                    x-model="pw" 
                                    x-bind:class="pw.length > 5 ? 'bg-green-300' : 'bg-gray-200'" 
                                    type="password" name="password" id="password" 
                                    class="form-control @error('password') is-invalid @enderror"
                                    x-effect="console.log(pw)"
                                >
                                <div class="mt-1 d-flex justify-content-between">
                                    <div class="progress col-sm-3" style="height: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar" x-bind:style="true && {'width' : '100%'}" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                      <div class="progress col-sm-3" style="height: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                      <div class="progress col-sm-3" style="height: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                      <div>
                                        <span x-text="pw"></span>
                                      </div>
                                    {{-- <span class=""
                                          x-bind:class="pw.length > 5 ? 'bg-primary' : 'bg-danger'"></span> --}}
                                    {{-- <span class="h-1 w-1/3 rounded mx-3"
                                          x-bind:class="pw.length > 5 && pw.match(/[\w]+/) ? 'bg-green-300' : 'bg-gray-200'"></span>
                                    <span class="h-1 w-1/3 rounded"
                                          x-bind:class="pw.length > 5 && pw.match(/[!@#$%^&*(),.?:{}|<>]/) ? 'bg-green-300' : 'bg-gray-200'"></span> --}}
                                </div>
                
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                
                        <div class="form-group row">
                            <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                
                            <div class="col-md-6">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
                
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
