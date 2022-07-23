@extends('layouts.login')

@section('content')
<div class="container">
    <div class="row d-flex justify-content-center align-items-center pt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Change Password</div>

                <div class="card-body">
                    <div class="py-3">

                        <h6 class="text-info text-center">Welcome to ZRB, Since this is your first login you need to set new password for security purposes</h6>
                    </div>

                    <form method="POST" action="{{ route('password.save-changed') }}">
                        @csrf

                        <input type="hidden" name="user_id" value="{{ $id }}">

                        <div x-data="{password:'', password_confirm: ''}">
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>
                                <div class="col-md-6">
                                    <input x-model="password" type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Confirm
                                    Password</label>

                                <div class="col-md-6">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        x-model="password_confirm"
                                        class="form-control @error('password_confirmation') is-invalid @enderror">
                                    @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="offset-sm-4 px-3">
                                    <div class="m-0">
                                        <span x-bind:class="password.length >= 8 ? 'text-success':'text-danger'">
                                            <i class=" pr-2"
                                                x-bind:class="password.length >=8 ? 'bi bi-check-circle-fill':'bi bi-x-circle-fill'"></i>
                                            <span>At least 8 characters required</span>
                                        </span>
                                    </div>
                                    <div class="m-0">
                                        <span x-bind:class="password.match(/[A-Z]/) ? 'text-success':'text-danger'">
                                            <i class=" pr-2"
                                                x-bind:class="password.match(/[A-Z]+/) ? 'bi bi-check-circle-fill':'bi bi-x-circle-fill'"></i>
                                            <span> Must contain Uppercase</span>
                                        </span>
                                    </div>
                                    <div class="m-0">
                                        <span :class="password.match(/[0-9]/) ? 'text-success':'text-danger'">
                                            <i class=" pr-2"
                                                x-bind:class="password.match(/[0-9]+/) ? 'bi bi-check-circle-fill':'bi bi-x-circle-fill'"></i>
                                            <span> Must contain a number</span>
                                        </span>
                                    </div>
                                    <div class="m-0">
                                        <span
                                            x-bind:class="password.match(/[!@#$%^&*(),.?:{}|<>]/) ? 'text-success':'text-danger'">
                                            <i class=" pr-2"
                                                x-bind:class="password.match(/[!@$%^&*(),.?:{}|<>]/) ? 'bi bi-check-circle-fill':'bi bi-x-circle-fill'"></i>
                                            <span> Must contain Special character</span>
                                        </strong>
                                    </div>
                                    <div class="m-0">
                                        <span
                                            x-bind:class="password.length>=8 && password == password_confirm ? 'text-success':'text-danger'">
                                            <i class=" pr-2"
                                                x-bind:class="password.length >= 8 && password == password_confirm ? 'bi bi-check-circle-fill':'bi bi-x-circle-fill'"></i>
                                            <span> Passwords must match</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit"
                                        x-bind:disabled="!(password.length >=8 && password == password_confirm && password.match(/[!@$%^&*(),.?:{}|<>]/) && password.match(/[A-Z]+/)) && password.match(/[0-9]/)"
                                        class="btn btn-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection