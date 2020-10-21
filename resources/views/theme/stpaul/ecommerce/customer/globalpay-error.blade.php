@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/jssocials/jssocials-theme-flat.min.css') }}" />
@endsection

@section('content')
<main>
    <section id="default-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-12">
                    <div id="order-detail">
                        <div class="row align-items-center">
                            <div class="col-lg-12">
                                <h2 class="text-center"><strong>Errors have occured</strong></h2>
                            </div>
                        </div>
                        <div class="gap-20"></div>
                        <div id="form-wrapper">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="gap-40"></div>
                                    <div class="success-icon" style="background: #b81600;">
                                        <span class="lnr lnr-cross text-white"></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="gap-20"></div>
                                    @php
                                        $bankError = [201, 203, 204, 205, 208, 210, 211]
                                    @endphp
                                    <h5 class="success-msg" style="color: black;">
                                        @if(in_array($responsecode,$bankError))
                                            Transaction (ID: {{$orderno}}) rejected, please contact your bank.
                                        @else
                                            Transaction (ID: {{$orderno}}) unsuccessful, please try again.
                                        @endif
                                    </h5>
                                    <hr>
                                    <div class="gap-40"></div>
                                </div>
                                <div class="col-md-12 mb-4 align-self-center">
                                    <a href="{{ route('account-my-orders') }}" class="btn btn-md primary-btn btn-block text-white">View My Orders</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
