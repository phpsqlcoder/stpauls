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
                                <h2 class="lgin-title">Forgot Password?</h2>
                            </div>
                        </div>
                        <div class="gap-10"></div>
                        <div id="form-wrapper">
                            <form autocomplete="off" method="POST" action="{{ route('ecommerce.send_reset_link_email') }}">
                            @csrf
                                <div class="row form-style-login">
                                    <div class="col-md-12">
                                        <div class="gap-10"></div>
                                        <p class="text-dark"><strong>Enter your registered email address to receive instructions on how to reset your password.</strong></p>
                                        @if (session('error'))
                                            <div class="gap-20"></div>
                                            <div class="alert alert-danger" role="alert">
                                                <span class="fa fa-info-circle"></span> {{ session('error') }}
                                            </div>
                                        @endif

                                        @if (session('status'))
                                            <div class="gap-20"></div>
                                            <div class="alert alert-success" role="alert">
                                                <span class="fa fa-check-circle"></span> {{ session('status') }}
                                            </div>
                                        @endif
                                        <div class="gap-20"></div>
                                    </div>
                                    @if (session('status') <> 'success')
                                    <div class="form-group col-md-12">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <p>Email *</p>
                                                <input required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" type="email" class="form-control form-input @error('email') is-invalid @enderror" placeholder="Please enter your Email" name="email" id="email">
                                                @hasError(['inputName' => 'email'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-lg-6 col-md-7">
                                                <div class="gap-10"></div>
                                                <button type="submit" class="btn btn-md primary-btn btn-block">Submit</button>
                                                <div class="gap-20"></div>
                                                <p class="text-left"><a href="{{ route('customer-front.login') }}">Go Back</a></p>
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