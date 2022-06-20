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
                    <h5 class="card-header bg-white text-uppercase">
                        ZRB Login
                    </h5>

                    <div class="card-body">
                        @if ($errors->any())
                            {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
                        @endif
                        <form method="POST" action="{{ route('login') }}" novalidate>
                            @csrf
                            <div class="">

                                <div class="form-group">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="text" class="form-control  @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autocomplete="email"
                                        autofocus />
                                    <div class="invalid-feedback" style="white-space: nowrap;overflow: scroll">
                                        @error('email')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="typePasswordX">Password</label>
                                    <input type="password" class="form-control" name="password" required
                                        autocomplete="current-password" />
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="captcha">Captcha</label>
                                    <input type="text" id="captcha"
                                        class="form-control @error('captcha') is-invalid @enderror" name="captcha"
                                        value="{{ old('captcha') }}" required autocomplete="captcha" autofocus />
                                    <div class="invalid-feedback" style="white-space: nowrap;overflow: scroll">
                                        @error('captcha')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <span id="captcha-label">{!! captcha_img('flat') !!}</span>
                                    <button type="button" class="btn btn-outline-success" onclick="captchaReload()">
                                        <i class='fas fa-redo'></i>
                                    </button>
                                </div>
                                <button class="btn btn-info px-5" type="submit">Login</button>
                                <p class="small text-start mt-3"><a href="{{ url('password/reset') }}">Forgot
                                        password?</a>
                                </p>
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
