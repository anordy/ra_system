@extends('layouts.login')

@section('title')
Reset
@endsection

@section('content')
<div class="container">
    <div class="row d-flex justify-content-center align-items-center pt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @include('layouts.component.messages')

                  

                    <div x-data="{password:'', password_confirm: ''}">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
    
                                <div class="col-md-6">
                                    <label class="form-control" name="email">{{ $email }}</label>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
    
                                <div class="col-md-6">
                                    <input id="password" x-model="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
    
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
    
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" x-model="password_confirm" class="form-control" name="password_confirmation" required autocomplete="new-password">
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
                                    <button type="submit" class="btn btn-primary"
                                    x-bind:disabled="!(password.length >= 8 && password == password_confirm && password.match(/[!@$%^&*(),.?:{}|<>]/) && password.match(/[A-Z]+/)) && password.match(/[0-9]/)">
                                        {{ __('Reset Password') }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
