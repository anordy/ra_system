@extends('layouts.login')
@section('content')
    <div class="middle-box ">

        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-12">

                <div class="card rounded">
                    <div class="d-flex justify-content-center pb-2">
                        <img alt="image" class="rounded" width="160px" height="160px"
                            src="{{ asset('images/logo.png') }}" />
                    </div>
                </div>
                <div class="card rounded">
                    <h6 class="card-header bg-white text-uppercase">
                        ZRB VERIFICATION
                    </h6>

                    <div class="card-body">
                        @if ($errors->any())
                            {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
                        @endif
                        <form method="POST" action="{{ route('twoFactorAuth.confirm') }}" novalidate>
                            @csrf
                            <div class="text-center">
                                <label class="">Please enter verification code sent either on<br> E-mail/Phone number
                                </label>
                                <div id="otp" class="inputs d-flex flex-row justify-content-center mt-2">
                                    <input class="m-2 text-center form-control rounded" type="text" name="first"
                                        id="first" maxlength="1" />
                                    <input class="m-2 text-center form-control rounded" type="text" name="second"
                                        id="second" maxlength="1" />
                                    <input class="m-2 text-center form-control rounded" type="text" name="third"
                                        id="third" maxlength="1" />
                                    <input class="m-2 text-center form-control rounded" type="text" name="fourth"
                                        id="fourth" maxlength="1" />
                                    <input class="m-2 text-center form-control rounded" type="text" name="fifth"
                                        id="fifth" maxlength="1" />
                                    <input class="m-2 text-center form-control rounded" type="text" name="sixth"
                                        id="sixth" maxlength="1" />
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-info px-4 validate">Validate</button>
                                </div>

                                <div class="mt-3">
                                    <span>Didn't get the code </span>
                                    <a href="#" class="text-decoration-none ms-3"> Resend Code</a>
                                </div>
                                <div class="mt-3">
                                    <a href="#" class="text-decoration-none ms-3"> Login</a>
                                </div>
                                <div class="mt-2"></div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 text-center small mt-4">
                Copyright ZRB © 2022 - {{ date('Y') }}
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {

            function OTPInput() {
                const inputs = document.querySelectorAll('#otp > *[id]');
                for (let i = 0; i < inputs.length; i++) {
                    inputs[i].addEventListener('keydown', function(event) {
                        if (event.key === "Backspace") {
                            inputs[i].value = '';
                            if (i !== 0) inputs[i - 1].focus();
                        } else {
                            if (i === inputs.length - 1 && inputs[i].value !== '') {
                                return true;
                            } else if (event.keyCode > 47 && event.keyCode < 58) {
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

        function captchaReload() {
            $.ajax({
                type: 'GET',
                url: '{{ route('captcha.reload') }}',
                success: function(data) {
                    $("#captcha-label").html(data.captcha);
                }
            });
        }
    </script>
@endsection
