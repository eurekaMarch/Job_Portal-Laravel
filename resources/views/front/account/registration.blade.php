@extends('front.layouts.app')

@section('main')
    <section class="section-5">
        <div class="container my-5">
            <div class="py-lg-2">&nbsp;</div>
            <div class="row d-flex justify-content-center">
                <div class="col-md-5">
                    <div class="card shadow border-0 p-5">
                        <h1 class="h3">Register</h1>
                        <form action="" id="registrationForm">
                            <div class="mb-3">
                                <label for="" class="mb-2">Name*</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Enter Name">
                                <p id="error"></p>
                            </div>
                            <div class="mb-3">
                                <label for="" class="mb-2">Email*</label>
                                <input type="text" name="email" id="email" class="form-control"
                                    placeholder="Enter Email">
                                <p id="error"></p>
                            </div>
                            <div class="mb-3">
                                <label for="" class="mb-2">Password*</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Enter Password">
                                <p id="error"></p>
                            </div>
                            <div class="mb-3">
                                <label for="" class="mb-2">Confirm Password*</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                                    placeholder="Please Confirm Password">
                                <p id="error"></p>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Register</button>
                        </form>
                    </div>
                    <div class="mt-4 text-center">
                        <p>Have an account? <a href="{{ route('login') }}">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $("#registrationForm").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('submitRegistration') }}',
                type: 'POST',
                data: $("#registrationForm").serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == false) {
                        const errors = response.errors;

                        if (errors.name) {
                            $("#name").addClass('is-invalid')
                                .siblings("#error")
                                .addClass('invalid-feedback')
                                .html(errors.name)
                        } else {
                            $("#name").removeClass('is-invalid')
                                .siblings("#error")
                                .removeClass('invalid-feedback')
                                .html('')
                        }

                        if (errors.email) {
                            $("#email").addClass('is-invalid')
                                .siblings("#error")
                                .addClass('invalid-feedback')
                                .html(errors.email)
                        } else {
                            $("#email").removeClass('is-invalid')
                                .siblings("#error")
                                .removeClass('invalid-feedback')
                                .html('')
                        }

                        if (errors.password) {
                            $("#password").addClass('is-invalid')
                                .siblings("#error")
                                .addClass('invalid-feedback')
                                .html(errors.password)
                        } else {
                            $("#password").removeClass('is-invalid')
                                .siblings("#error")
                                .removeClass('invalid-feedback')
                                .html('')
                        }

                        if (errors.confirm_password) {
                            $("#confirm_password").addClass('is-invalid')
                                .siblings("#error")
                                .addClass('invalid-feedback')
                                .html(errors.confirm_password)
                        } else {
                            $("#confirm_password").removeClass('is-invalid')
                                .siblings("#error")
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                    } else {
                        $("#name").removeClass('is-invalid')
                            .siblings("#error")
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#email").removeClass('is-invalid')
                            .siblings("#error")
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#password").removeClass('is-invalid')
                            .siblings("#error")
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#confirm_password").removeClass('is-invalid')
                            .siblings("#error")
                            .removeClass('invalid-feedback')
                            .html('')

                        window.location.href = "{{ route('login') }}";
                    }

                }
            })
        })
    </script>
@endsection
