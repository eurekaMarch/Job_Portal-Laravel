@extends('front.layouts.app')

@section('main')
    <section class="section-5">
        <div class="container my-5">
            <div class="py-lg-2">&nbsp;</div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row d-flex justify-content-center">
                <div class="col-md-5">
                    <div class="card shadow border-0 p-5">
                        <h1 class="h3">Login</h1>
                        <form action="{{ route('authenticate') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="" class="mb-2">Email*</label>
                                <input type="text" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    placeholder="example@example.com">

                                @error('email')
                                    <p class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="" class="mb-2">Password*</label>
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter Password">

                                @error('password')
                                    <p class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="justify-content-between d-flex">
                                <button class="btn btn-primary mt-2">Login</button>
                                <a href="forgot-password.html" class="mt-3">Forgot Password?</a>
                            </div>
                        </form>
                    </div>
                    <div class="mt-4 text-center">
                        <p>Do not have an account? <a href="{{ route('registration') }}">Register</a></p>
                    </div>
                </div>
            </div>
            <div class="py-lg-5">&nbsp;</div>
        </div>
    </section>
@endsection
