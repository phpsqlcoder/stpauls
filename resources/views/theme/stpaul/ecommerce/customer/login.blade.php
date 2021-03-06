@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')


@section('content')
<main>
    <section id="default-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-12">
                    @if(Session::has('warning'))
                    <div class="alert alert-warning" role="alert">
                        <span class="fa fa-exclamation"></span>
                        This account is inactive. To re-activate this account please click this <a href="{{ route('ecommerce.reactivate-account') }}"><b>link</b>.</a>
                    </div>
                    @endif

                    @if($message = Session::get('error'))
                        <div class="alert alert-danger" role="alert">
                            <span class="fa fa-exclamation"></span> {{ $message }}
                        </div>
                    @endif

                    @if($message = Session::get('success'))
                        <div class="alert alert-success" role="alert">
                            <span class="fa fa-check"></span> {{ $message }}
                        </div>
                    @endif
                    <div id="login">
                        <div class="row align-items-center">
                            <div class="col-md-8 col-sm-12">
                                <h2 class="lgin-title">Welcome to ST PAULS! Please Login.</h2>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <p class="text-right" id="new-mem">New member? <a href="{{ route('customer-front.sign-up') }}">Register</a> here.</p>
                            </div>
                        </div>
                        <div id="form-wrapper">
                            <form autocomplete="off" method="post" action="{{ route('customer-front.customer_login') }}">
                                @csrf
                                <div class="row form-style-login">
                                    <div class="form-group col-md-7">
                                        <p>Email*</p>
                                        <input type="email" name="email" class="form-control form-input" placeholder="Please enter your Email">
                                        <div class="gap-10"></div>
                                        <p>Password*</p>
                                        <div class="form-group position-relative">
                                            <input type="password" name="password" class="form-control form-input password-field" id="password" placeholder="Please enter your Password">
                                        </div>
                                        <div class="gap-10"></div>
                                        <p class="text-right"><a href="{{ route('ecommerce.forgot_password') }}">Forgot Password?</a></p>
                                        <p class="text-right"><a href="{{ route('ecommerce.reactivate-account') }}">Re-activate Account?</a></p>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <div class="gap-20"></div>
                                        <button type="submit" type="login" class="btn btn-md primary-btn btn-block">login</button>
                                        <div class="gap-10"></div>
                                        <p class="text-center">Or, login with</p>
                                        <div class="gap-10"></div>
                                        <a href="{{ route('social.oauth-login','facebook') }}" class="btn btn-primary btn-block fb text-white"><i class="fab fa-facebook-f pr-3"></i>Facebook</a>
                                        <a href="{{ route('social.oauth-login', 'google') }}" class="btn btn-primary btn-block gl text-white"><i class="fab fa-google-plus-g pr-3"></i>Google</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
