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
                                @php
                                    $payment = \App\EcommerceModel\SalesPayment::where('sales_header_id',$sales->id)->count();
                                @endphp

                                @if($sales->payment_method == 1 || $payment > 0)
                                    @if($sales->sdd_booking_type == 1)
                                    <h2 class="text-center"><strong>Thank you for your payment.<br>Please wait for the payment confirmation before booking your rider.</strong></h2>
                                    @else
                                    <h2 class="text-center"><strong>Thank you for your payment.<br>ST PAULS personnel will contact you once the order is "Ready for Delivery".</strong></h2>
                                    @endif
                                @endif
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
                                    @if($sales->payment_method == 1 || $payment > 0)
                                    <h5 class="success-msg">Payment for Verification</h5>
                                    @else
                                    <h5 class="success-msg">Order Received</h5>
                                    @endif
                                </div>
                                <div class="col-lg-12">
                                    <div class="gap-40"></div>
                                    <div class="order-no">
                                        <h4>Order No. {{ $sales->order_number }}</h4>
                                    </div>
                                    <div class="gap-40"></div>
                                </div>
                                <div class="col-md-12 mb-4">
                                    @if($sales->sdd_booking_type == 1 && $payment > 0)
                                    <p class="font-weight-bold"><b>To book a rider, click on the &nbsp;<span class="lnr lnr-bicycle text-md text-first-color font-weight-bold mr-2"></span> and enter the Rider's Information.</b></p>
                                    @else
                                    <p class="font-weight-bold">For <b class="text-danger">unpaid orders</b>, click on <b class="text-danger">MY ORDER</b> button below. Click on the <span class="d-inline-block ml-1 mr-1" style="transform:translateY(6px)"><span class="c-icon c-icon-peso-red"></span></span> button to attach your proof of payment. <b>To view the status of your order, click on &nbsp;<span class="d-inline-block mr-1" style="transform:translateY(6.5px)"><span class="lnr lnr-eye text-md text-first-color font-weight-bold"></span></span>&nbsp;or&nbsp;<span class="d-inline-block ml-1" style="transform:translateY(2px)"><span class="lnr lnr-car text-md text-first-color font-weight-bold mr-2"></span></span>.</b></p>
                                    @endif
                                </div>
                                <div class="col-md-12 mb-4">
                                    <a href="{{ route('account-my-orders') }}" class="btn btn-md primary-btn btn-block text-white">My Order</a>
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
