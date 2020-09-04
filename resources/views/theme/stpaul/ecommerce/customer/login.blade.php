@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')


@section('content')
<main>
    <section id="default-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-12">
                    @if(Session::has('warning'))
                    <div class="alert alert-warning alert-dismissible fade show">
                        <strong>Warning!</strong> This account is inactive. To re-activate this account please click this <a href="{{ route('ecommerce.reactivate-account') }}"><b>link</b>.</a>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                    @endif
                    <div id="login">
                        <div class="row align-items-center">
                            <div class="col-md-8 col-sm-12">
                                <h2 class="lgin-title">Welcome to St Pauls! Please Login.</h2>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <p class="text-right" id="new-mem">New member? <a href="#">Register</a> here.</p>
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
                                        <input type="password" name="password" class="form-control form-input" placeholder="Please enter your Password">
                                        <div class="gap-10"></div>
                                        <p class="text-right"><a href="#">Forgot Password?</a></p>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <div class="gap-20"></div>
                                        <button type="submit" class="btn btn-md primary-btn btn-block">login</button>
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
