@extends('layouts.blank')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
<div class="authentication-wrapper authentication-cover">

    <div class="authentication-inner row m-0">
        <!-- /Left Text -->
        <div class="d-none d-xl-flex col-xl-8 p-0">
            <div class="auth-cover-bg d-flex justify-content-center align-items-center">
                <img src="{{ asset('images/bg-login.webp') }}" alt="auth-login-cover" class="my-5 auth-illustration" data-app-light-img="" data-app-dark-img="" />
                <img src="{{ asset('images/bg-shape-image-light.png') }}" alt="auth-login-cover" class="platform-bg" data-app-light-img="" data-app-dark-img="" />
            </div>
        </div>
        <!-- /Left Text -->

        <!-- Login -->
        <div class="d-flex col-12 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
            <div class="w-px-400 mx-auto mt-12 pt-5">

                <form id="formAuthentication" class="mb-6" action="{{ route('login') }}" method="POST">
                    @csrf
                    <x-input.email />
                    <x-input.password />

                    <div class="my-8">
                        <div class="d-flex justify-content-between">
                            <div class="form-check mb-0 ms-2">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember" />
                                <label class="form-check-label" for="remember"> Remember Me </label>
                            </div>

                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary d-grid w-100">Sign in</button>
                </form>


            </div>
        </div>
        <!-- /Login -->
    </div>
</div>

@stop
