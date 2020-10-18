<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="">
    <meta name="author">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/img/favicon.png">

    <title>Admin Panel | {{ Setting::info()->company_name }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage').'/icons/'.Setting::getFaviconLogo()->website_favicon }}">

    <!-- vendor css -->
    <link href="{{ asset('lib/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">

    <!-- DashForge CSS -->
    <link href="{{ asset('css/dashforge.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashforge.auth.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-admin.css') }}" rel="stylesheet">
</head>

<body id="scroll1">

<div class="content-auth">
    <div class="row no-gutters">
        <div class="col-lg-6">
            <div class="signin-hero"></div>
        </div>
        <div class="col-lg-6">
            <div class="sign-wrapper">
                @if($message = Session::get('error'))
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i data-feather="alert-circle" class="mg-r-10"></i> {{ $message }}
                    </div>
                @endif

                @if($message = Session::get('msg'))
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i data-feather="alert-circle" class="mg-r-10"></i> {{ $message }}
                    </div>
                @endif
                <div class="wd-100p">
                    <h3 class="mg-b-3">Create Password</h3>
                    <p class="tx-color-03 tx-14 mg-b-40">{{ __('passwords.reset_title') }}</p>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="email">{{ __('passwords.email') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" readonly>
                                @hasError(['inputName' => 'email'])
                                @endhasError
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="password">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @hasError(['inputName' => 'password'])
                                @endhasError
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
                                @hasError(['inputName' => 'password_confirmation'])
                                @endhasError
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Set Password</button>
                                <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('login') }}">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- sign-wrapper -->
        </div>
    </div>
</div><!-- content -->

<script src="{{ asset('lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('lib/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('lib/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

<script src="{{ asset('js/dashforge.js') }}"></script>

</body>

</html>
