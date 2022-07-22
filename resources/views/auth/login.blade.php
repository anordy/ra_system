@extends('layouts.login')
@section('title')
    Login
@endsection
@section('content')
<div class="middle-box ">

    <div class="row d-flex justify-content-center align-items-center">
        <div class="col-md-12">

            <div class="card rounded my-2">
                <div class="d-flex justify-content-center pb-2">
                    <img alt="image" class="rounded" width="160px" height="160px"
                        src="{{ asset('images/logo.png') }}" />
                </div>
            </div>
            <div class="card rounded my-2">
                <h5 class="card-header bg-white text-uppercase">
                    ZRB Staff Login
                </h5>

                <div class="card-body">
                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                    {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
                    @endif
                    <form method="POST" action="{{ route('login') }}" novalidate>
                        @csrf
                        <div class="">

                            <div class="form-group">
                                <label class="form-label" for="email">Email Address</label>
                                <input type="text" class="form-control  @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus />
                                <div class="invalid-feedback">
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

                            <div class="form-group mb-3">
                                <label class="form-label" for="captcha">Captcha</label>
                                <div class="d-flex">
                                    <span id="captcha_label">{!! captcha_img('flat') !!}</span>
                                    <button style="flex: 1" type="button" class="btn btn-outline-success ml-2" onclick="captchaReload()">
                                        <i class='bi bi-arrow-clockwise mr-1'></i>
                                        Reload
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" id="captcha"
                                       class="form-control @error('captcha') is-invalid @enderror" name="captcha" required autofocus />
                                <div class="invalid-feedback">
                                    @error('captcha')
                                    {{ $message }}
                                    @enderror
                                </div>
                            </div>

                            <button class="btn btn-info px-5" type="submit">Login</button>
                            <p class="text-primary mt-1 float-right"><a style="text-decoration: none" href="{{ url('password/reset') }}">Forgot
                                    password ?</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-rounded">
                <div class="card">
                    <div class="card-body text-center">
                        Copyright ZRB © 2022 - {{ date('Y') }}
                    </div>
                </div>
            </div>

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
                    $("#captcha_label").html(data.captcha);
                }
            });
        }
</script>
@endsection