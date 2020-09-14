@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('content')
 <main>
    <section id="default-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-12">
                    <div id="login">
                        <div class="row align-items-center">
                            <div class="col-md-12 col-sm-12">
                                <h2 class="lgin-title">Reset Password?</h2>
                            </div>
                        </div>
                        <div class="gap-10"></div>
                        <div id="form-wrapper">
                            <form autocomplete="off" method="POST" action="{{ route('ecommerce.reset_password_post') }}">
                            @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="gap-10"></div>
                                        <p class="text-dark"><strong>Please enter the account that you want to reset the password.</strong></p>
                                        @if ($errors->any())
                                            <div class="alert alert-danger" role="alert">
                                                <span class="fa fa-info-circle"></span>
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        @if (session('error'))
                                            <div class="gap-20"></div>
                                            <div class="alert alert-danger" role="alert">
                                                {{ session('error') }}
                                            </div>
                                        @endif
                                        @if (session('status'))
                                            <div class="gap-20"></div>
                                            <div class="alert alert-success" role="alert">
                                                {{ session('status') }}
                                            </div>
                                        @endif
                                        <div class="gap-20"></div>
                                    </div>
                                    @if (session('status') <> 'success')
                                    <div class="form-group col-md-12">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <p>Email *</p>
                                                <input type="hidden" name="token" value="{{ $token }}">
                                                <input readonly type="email" class="form-control form-input" name="email" id="email" value="{{ request('email') }}">
                                                <div class="gap-10"></div>
                                                <p>New Password *</p>
                                                <input required type="password" class="form-control form-input" name="password">
                                                <div class="gap-10"></div>
                                                <p>Confirm Password *</p>
                                                <input required type="password" class="form-control form-input" name="password_confirmation">
                                                <div class="gap-10"></div>
                                            </div>
                                            <div class="col-lg-6 col-md-7">
                                                <div class="gap-10"></div>
                                                <button type="submit" class="btn btn-md primary-btn btn-block">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
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