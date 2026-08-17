<!doctype html>
<html lang="en" data-bs-theme="blue-theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AMS | Audit Management System</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('template/assets/images/logo-mbi.png') }}" type="image/png">

    {{-- Loader --}}
    <link href="{{ asset('template/assets/css/pace.min.css') }}" rel="stylesheet">
    <script src="{{ asset('template/assets/js/pace.min.js') }}"></script>

    {{-- Plugins --}}
    <link href="{{ asset('template/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('template/assets/plugins/metismenu/metisMenu.min.css') }}">

    <link rel="stylesheet" href="{{ asset('template/assets/plugins/metismenu/mm-vertical.css') }}">

    {{-- Bootstrap --}}
    <link href="{{ asset('template/assets/css/bootstrap.min.css') }}" rel="stylesheet">

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">

    {{-- Main CSS --}}
    <link href="{{ asset('template/assets/css/bootstrap-extended.css') }}" rel="stylesheet">

    <link href="{{ asset('template/sass/main.css') }}" rel="stylesheet">

    <link href="{{ asset('template/sass/dark-theme.css') }}" rel="stylesheet">

    <link href="{{ asset('template/sass/blue-theme.css') }}" rel="stylesheet">

    <link href="{{ asset('template/sass/responsive.css') }}" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
        }

        .login-logo {
            max-width: 90px;
            height: auto;
        }

        .login-image {
            max-height: 450px;
            width: auto;
        }

        @media (max-width: 991.98px) {
            .login-card {
                margin-top: 30px !important;
                margin-bottom: 30px !important;
            }
        }
    </style>

</head>

<body>

    {{-- =========================================================
        AUTHENTICATION
    ========================================================== --}}

    <div class="mx-3 mx-lg-0">

        <div class="card login-card my-5 col-xl-9 col-xxl-8 mx-auto rounded-4 overflow-hidden p-4">

            <div class="row g-4">

                {{-- =====================================================
                    LOGIN FORM
                ====================================================== --}}
                <div class="col-lg-6 d-flex">

                    <div class="card-body">

                        {{-- Logo --}}
                        <img src="{{ asset('template/assets/images/logo-mbi.png') }}" class="login-logo mb-4"
                            alt="Logo MBI">

                        <h4 class="fw-bold">
                            Audit Management System
                        </h4>

                        <p class="mb-4 text-muted">
                            Sila log masuk menggunakan akaun anda.
                        </p>


                        {{-- =================================================
                            SESSION STATUS
                        ================================================== --}}
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif


                        {{-- =================================================
                            GENERAL VALIDATION ERROR
                        ================================================== --}}
                        @if ($errors->any())

                            <div class="alert alert-danger">

                                <strong>
                                    Log masuk tidak berjaya.
                                </strong>

                                <ul class="mb-0 mt-2">

                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach

                                </ul>

                            </div>

                        @endif


                        {{-- =================================================
                            LOGIN FORM
                        ================================================== --}}
                        <div class="form-body mt-4">

                            <form method="POST" action="{{ route('login') }}" class="row g-3">

                                @csrf


                                {{-- NO PEKERJA --}}
                                <div class="col-12">

                                    <label for="no_pekerja" class="form-label">

                                        No. Pekerja

                                    </label>

                                    <input type="text" class="form-control @error('no_pekerja') is-invalid @enderror"
                                        id="no_pekerja" name="no_pekerja" value="{{ old('no_pekerja') }}"
                                        placeholder="Masukkan No. Pekerja" required autofocus autocomplete="username">

                                    @error('no_pekerja')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                {{-- PASSWORD --}}
                                <div class="col-12">

                                    <label for="password" class="form-label">

                                        Password

                                    </label>

                                    <div class="input-group" id="show_hide_password">

                                        <input type="password"
                                            class="form-control border-end-0 @error('password') is-invalid @enderror"
                                            id="password" name="password" placeholder="Masukkan Password" required
                                            autocomplete="current-password">

                                        <a href="javascript:;" class="input-group-text bg-transparent">

                                            <i class="bi bi-eye-slash-fill"></i>

                                        </a>

                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                </div>


                                {{-- REMEMBER ME --}}
                                <div class="col-md-6">

                                    <div class="form-check form-switch">

                                        <input class="form-check-input" type="checkbox" id="remember" name="remember">

                                        <label class="form-check-label" for="remember">

                                            Remember Me

                                        </label>

                                    </div>

                                </div>


                                {{-- FORGOT PASSWORD --}}
                                {{-- <div class="col-md-6 text-md-end">

                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}">
                                            Forgot Password?
                                        </a>
                                    @endif

                                </div> --}}


                                {{-- LOGIN BUTTON --}}
                                <div class="col-12">

                                    <div class="d-grid">

                                        <button type="submit" class="btn btn-grd-primary">

                                            Log Masuk

                                        </button>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                    RIGHT IMAGE
                ====================================================== --}}
                <div class="col-lg-6 d-lg-flex d-none">

                    <div class="p-3 rounded-4 w-100 d-flex align-items-center justify-content-center bg-grd-primary">

                        <img src="{{ asset('template/assets/images/auth/reset-password1.png') }}"
                            class="img-fluid login-image" alt="AMS Login">

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        JAVASCRIPT
    ========================================================== --}}

    <script src="{{ asset('template/assets/js/jquery.min.js') }}"></script>

    <script src="{{ asset('template/assets/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            $("#show_hide_password a").on('click', function(event) {

                event.preventDefault();

                let password = $('#show_hide_password input');
                let icon = $('#show_hide_password i');

                if (password.attr("type") === "text") {

                    password.attr('type', 'password');

                    icon.addClass("bi-eye-slash-fill");
                    icon.removeClass("bi-eye-fill");

                } else {

                    password.attr('type', 'text');

                    icon.removeClass("bi-eye-slash-fill");
                    icon.addClass("bi-eye-fill");

                }

            });

        });
    </script>

</body>

</html>
