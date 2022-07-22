@extends('layouts.login')
@section('content')
    <section class="vh-100 gradient-custom" id="loginBox">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark-transparent" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            @include('layouts.component.messages')
                            <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate>
                                @csrf

                                <div class="mt-md-4 pb-5">
                                    <h4 class="fw-bold mb-4 text-uppercase">Reset Password</h4>

                                    <div class="form-outline form-white mb-4">
                                        <label class="form-label" for="typeUsername">Email</label>
                                        <input type="email" id="typeUsername" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus/>
                                        <div class="invalid-feedback" style="white-space: nowrap;overflow: scroll">@error('email') {{ $message }} @enderror</div>
                                    </div>
                                    <button class="btn btn-outline-dark btn-lg px-5" type="submit">{{ __('Send Password Reset Link') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
