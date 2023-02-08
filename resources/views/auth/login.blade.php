@extends('layouts.login')
@section('title')
    Login
@endsection

@section('styles')
    <style nonce="custom_style">
        .card-body-margin {
            margin-left: 10px;
            margin-right: 10px;
        }

        .card-margin {
            margin-top: 60px;
            margin-bottom: 10px;
        }

        .card-header {
            padding-top: 60px;
            padding-bottom: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="middle-box ">

        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-12">
                <div class="card rounded card-margin">
                    <div class="card-body card-body-margin">
                        <div class="text-center">
                            <img src="{{ asset('images/logo.jpg') }}" id="logo" width="120px" height="120px">
                        </div>
                        <h5 class="bg-white text-uppercase text-center card-header">
                            ZIDRAS Staff Login
                        </h5>
                        @if (session()->has('success'))
                            <div class="alert alert-success">
                                {{ session()->get('success') }}
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session()->get('error') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('login') }}" autocomplete="off">
                            @csrf
                            <div class="mt-2">
                                <div class="form-group">
                                    <label class="form-label" for="email">Email Address</label>
                                    <input type="text" class="form-control  @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autofocus />
                                    <div class="d-block small text-danger mt-1">
                                        @error('email')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="typePasswordX">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password" required/>
                                    <div class="d-block small text-danger mt-1">
                                        @error('password')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label" for="captcha">Captcha</label>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
                                            <span id="captcha_label">{!! captcha_img('flat') !!}</span>
                                        </div>
                                        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                                            <button type="button" class="btn btn-outline-success ml-2 flex-1"
                                                    id="captchaReload">
                                                <i class='bi bi-arrow-clockwise mr-1'></i>
                                                Reload
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" id="captcha"
                                        class="form-control @error('captcha') is-invalid @enderror" name="captcha" required />
                                    <div class="d-block small text-danger mt-1">
                                        @error('captcha')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>

                                <button class="btn btn-info px-5" type="submit">Login</button>
                                <p class="text-primary mt-1 float-right text-decoration-none"><a
                                        href="{{ url('password/reset') }}">Forgot
                                        password ?</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body text-center text-white">
                    ©2022
                    <a href="https://www.zanrevenue.org/" class="text-bold" target="_blank">Zanzibar Revenue Authority</a>.
                    All Rights Reserved.
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script nonce="custom_script">
        $('#captchaReload').on('click', function() {
            $.ajax({
                type: 'GET',
                url: '{{ route('captcha.reload') }}',
                beforeSend: function () {
                    $('#captcha_label').html('<div class="text-center px-4"><div class="spinner-border spinner-border-sm"></div></div>')
                },
                success: function (data) {
                    $("#captcha_label").html(data.captcha);
                },
                error: error => {
                    var message = 'Something went wrong, contact support!'
                    if (error.status == 429){
                        message = 'Too Many Requests'
                    } else if (error.status == 500){
                        message = 'Internal Server error'
                    }
                    $("#captcha_label").html('<div class="text-center px-2 py-1 text-danger">' + message + '</div>')
                }
            });
        })
    </script>
@endsection
