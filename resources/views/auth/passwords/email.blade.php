@extends('components.login_master')
@section('content')
    @include('components.alert')
    <section class="vh-100 gradient-custom" id="loginBox">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark-transparent text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate>
                                @csrf

                                <div class="mt-md-4 pb-5">
                                    <h4 class="fw-bold mb-4 text-uppercase">Reset Password</h4>

                                    <div class="form-outline form-white mb-4">
                                        <input type="email" id="typeUsername" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus/>
                                        <label class="form-label" for="typeUsername">Email</label>
                                        <div class="invalid-feedback" style="white-space: nowrap;overflow: scroll">@error('email') {{ $message }} @enderror</div>
                                    </div>

{{--                                    <div class="form-outline form-white mb-4">--}}
{{--                                        <input type="text" id="captcha" class="form-control @error('captcha') is-invalid @enderror" name="captcha" value="{{ old('captcha') }}" required autocomplete="captcha" autofocus/>--}}
{{--                                        <label class="form-label" for="captcha">Captcha</label>--}}
{{--                                        <div class="invalid-feedback" style="white-space: nowrap;overflow: scroll">@error('captcha') {{ $message }} @enderror</div>--}}
{{--                                    </div>--}}

{{--                                    <div class="form-outline form-white mb-4 captcha">--}}
{{--                                        <span>{!! captcha_img('flat') !!}</span>--}}
{{--                                        <button type="button" class="btn btn-success" class="reload" id="reload">--}}
{{--                                            <i class='bx bx-refresh' style="font-size: 24px;"></i>--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
                                    <button class="btn btn-outline-light btn-lg px-5" type="submit">{{ __('Send Password Reset Link') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
