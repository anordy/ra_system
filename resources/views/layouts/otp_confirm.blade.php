@extends('layouts.login')
@section('title', 'OTP Verification')
@section('styles')
    <style nonce="custom_style">
        .card-margin {
            margin-top: 60px;
            margin-bottom: 10px;
        }
        
        .card-header {
            padding-top: 60px;
            padding-bottom: 20px;
        }

        .btn-link {
            font-size: 1em !important;
        }
    </style>
@endsection
@section('content')
    <div class="middle-box ">

        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-12">
                <div class="card rounded card-margin">
                    <div class="card-body">
                        <div class="text-center">
                            <img src="{{ asset('images/logo.png') }}" id="logo" width="120px" height="120px" alt="{{ config('app.name') }}">
                        </div>
                        <h5 class="bg-white text-uppercase text-center">OTP VERIFICATION</h5>
                        @include('layouts.component.messages')
                        <form method="POST" action="{{ route('twoFactorAuth.confirm') }}" novalidate>
                            @csrf
                            <div class="mt-2">
                                <div class="text-center">
                                    <label class="">Please enter verification code sent either on<br> E-mail/Phone
                                        number
                                    </label>
                                    <div id="otp" class="inputs d-flex flex-row justify-content-center mt-2">
                                        <input class="m-2 text-center form-control rounded" type="text" name="first"
                                               id="first" maxlength="1" autofocus="autofocus"/>
                                        <input class="m-2 text-center form-control rounded" type="text" name="second"
                                               id="second" maxlength="1"/>
                                        <input class="m-2 text-center form-control rounded" type="text" name="third"
                                               id="third" maxlength="1"/>
                                        <input class="m-2 text-center form-control rounded" type="text" name="fourth"
                                               id="fourth" maxlength="1"/>
                                        <input class="m-2 text-center form-control rounded" type="text" name="fifth"
                                               id="fifth" maxlength="1"/>
                                        <input class="m-2 text-center form-control rounded" type="text" name="sixth"
                                               id="sixth" maxlength="1"/>
                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-info px-4 validate">Validate</button>
                                    </div>


                                    <div class="mt-2"></div>
                                </div>
                            </div>

                        </form>
                        <div>
                            <form action="{{ route('twoFactorAuth.resend') }}" method="POST" novalidate>
                                @csrf
                                <div class="mt-3 inline-block">
                                    <span>Didn't get the code ?</span>
                                    <button type="submit" title="Re-send" class="btn btn-link">
                                        <i class="bi bi-arrow-counterclockwise mr-1"></i>
                                        Resend Token
                                    </button>
                                </div>
                            </form>

                            <div class="py-1">
                                <a href="{{ route('session.kill') }}" class="btn-link">Click here to return to login page</a>
                            </div>

                            {{-- <div class="mt-1 mb-2">
                                <a href="{{ route('2fa.security-questions') }}" class="btn-link">Login using security questions</a>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="text-center text-white">
                    ©{{ date("Y") }}
                    <a href="https://www.zanrevenue.org/" class="text-bold" target="_blank">Zanzibar Revenue Authority</a>.
                    All Rights Reserved.
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script nonce="custom_script">
        document.addEventListener("DOMContentLoaded", function (event) {

            function OTPInput() {
                const inputs = document.querySelectorAll('#otp > *[id]');
                for (let i = 0; i < inputs.length; i++) {
                    inputs[i].addEventListener('keydown', function (event) {
                        if (event.key === "Backspace") {
                            inputs[i].value = '';
                            if (i !== 0) inputs[i - 1].focus();
                        } else {
                            if (i === inputs.length - 1 && inputs[i].value !== '') {
                                return true;
                            } else if ((event.keyCode > 47 && event.keyCode < 58) || (event.keyCode >= 96 && event.keyCode <= 105)) {
                                inputs[i].value = event.key;
                                if (i !== inputs.length - 1) inputs[i + 1].focus();
                                event.preventDefault();
                            } else if (event.keyCode > 64 && event.keyCode < 91) {
                                inputs[i].value = String.fromCharCode(event.keyCode);
                                if (i !== inputs.length - 1) inputs[i + 1].focus();
                                event.preventDefault();
                            }
                        }
                    });
                }
            }

            OTPInput();
        });
    </script>
@endsection