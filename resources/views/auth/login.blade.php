@extends('layouts.login')
@section('title')
    Login
@endsection
@section('css')
    <style>
        #user{
            display: block;
            position: absolute;
            top: -60px;
            width: 120px;
            height: 120px;
            padding: 10px;
            border: 2px solid #000;
            border-radius: 50%;
            background: #fff;
        }
    </style>
@endsection
@section('content')
<div class="middle-box ">

    <div class="row d-flex justify-content-center align-items-center">
        <div class="col-md-12">
                <div class="card rounded" style="margin-top: 60px; margin-bottom:10px">
                    <div class="text-center">
                        <img src="{{ asset('images/logo.png') }}" id="user" width="120px" height="120px" style="display: block;position: absolute;top: -60px;width: 120px;height: 120px;padding: 10px;left:35%;border: 1px solid #000;border-radius: 50%;background: #fff;">
                    </div>
                    <h5 class="card-header bg-white text-uppercase text-center" style="padding-top: 70px;">
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