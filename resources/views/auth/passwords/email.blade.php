@extends('layouts.login')
@section('title')
Forgot Password
@endsection
@section('content')
    <section class="vh-100 gradient-custom" id="loginBox">
        <div class="container py-5">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-md-8">
                    <div class="card">
                        <h5 class="card-header">{{ __('Reset Password') }}</h5>

                        <div class="card-body">
                            @include('layouts.component.messages')

                            <form method="POST" action="{{ route('password.email') }}" >
                                @csrf

                                <div class="form-group row">
                                    <label for="email"
                                        class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-6">
                                        <input type="email" id="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autocomplete="off" />
                                        <div class="invalid-feedback text-wrap overflow-auto">
                                            @error('email')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>


                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button class="btn btn-primary btn-lg px-5"
                                            type="submit">{{ __('Send Password Reset Link') }}</button>
                                    </div>
                                </div>
                                <div class="form-group row mb-0 mt-4">
                                    <div class="col-md-6 offset-md-4">
                                        <p class="text-primary mt-1"><a
                                                href="{{ route('login') }}">Click here to return to login page</a>
                                        </p>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
