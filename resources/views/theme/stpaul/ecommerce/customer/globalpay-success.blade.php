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
                                <h2 class="text-center"><strong>Thank you for your order</strong></h2>
                            </div>
                        </div>
                        <div class="gap-20"></div>
                        <div id="form-wrapper">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="gap-40"></div>
                                    <div class="success-icon">
                                        <span class="lnr lnr-check"></span>
                                    </div>
                                    <h5 class="success-msg">Order Received</h5>

                                </div>
                                <div class="col-lg-12">
                                    <div class="gap-40"></div>
                                    <div class="order-no">
                                        <h4>Order No. {{ $sales->order_number }}</h4>
                                    </div>
                                    <div class="gap-40"></div>
                                </div>
                                <div class="col-md-7 mb-4">
                                    <p>For more details, track your delivery status under <strong>My Account > My Orders</strong></p>
                                </div>
                                <div class="col-md-5 mb-4 align-self-center">
                                    <a href="{{ route('account-order-info',$sales->id) }}" class="btn btn-md primary-btn btn-block">View Order</a>
                                </div>
                                <div class="col-lg-12">
                                    <div class="gap-20"></div>
                                    <hr>
                                    <div class="gap-40"></div>
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
