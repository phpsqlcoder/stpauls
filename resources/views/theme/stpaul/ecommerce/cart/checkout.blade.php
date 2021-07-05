@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <style>
        .sub1, .sub2 { display: none; }

        :checked ~ .sub1, :checked ~ .sub2 {
            display: block;
            margin-left: 40px;
        }
    </style>
@endsection

@section('content')
<main>
    <form autocomplete="off" method="post" action="{{ route('cart.temp_sales') }}" id="checkout-form">
    @csrf
        <section id="checkout-wrapper">
            <div class="container">
                <h2 class="checkout-title">Checkout</h2>

                <div class="checkout-info">
                    <div id="responsiveTabs2">
                        <ul>
                            <li>
                                <a href="#tab-1">
                                    <span class="step">1</span>
                                    <span class="title">Delivery Information and Shipping Options</span>
                                </a>
                            </li>
                            <li>
                                <a href="#tab-2">
                                    <span class="step">2</span>
                                    <span class="title">Review and Place Order</span>
                                </a>
                            </li>
                            <li>
                                <a href="#tab-3">
                                    <span class="step">3</span>
                                    <span class="title">Payment Method</span>
                                </a>
                            </li>
                        </ul>

                        <div id="tab-1">
                            <div class="checkout-content">
                                <div class="row">
                                    <div class="col-lg-6 mb-sm-4 mb-xs-4">
                                        <div class="checkout-card form-style fs-sm">
                                            <div class="form-row form-wrap">
                                                <div class="col-md-6">
                                                    <input type="hidden" name="fbId" value="">
                                                    <p>First Name *</p>
                                                    <input required type="text" name="firstname" id="input_fname" class="form-control form-input" value="{{ $customer->firstname }}">
                                                    <p id="p_fname" class="text-danger" style="display: none;"><small>The first name field is required.</small></p>  
                                                </div>
                                                <div class="col-md-6">
                                                    <p>Last Name *</p>
                                                    <input required type="text" name="lastname" id="input_lname" class="form-control form-input" value="{{ $customer->lastname }}">
                                                    <p id="p_lname" class="text-danger" style="display: none;"><small>The last name field is required.</small></p> 
                                                </div>
                                            </div>

                                            <div class="gap-10"></div>
                                            <div class="form-group form-wrap">
                                                <p>Email *</p>
                                                <input type="email" class="form-control form-input" name="email" id="input_email" value="{{ $customer->email }}">
                                                <p id="p_email" class="text-danger" style="display: none;"><small>The email field is required.</small></p>
                                                <p id="p_email_invalid" class="text-danger" style="display: none;"><small>Invalid email format.</small></p>
                                            </div>

                                            <div class="gap-10"></div>
                                            <div class="form-group form-wrap">
                                                <p>Mobile Number *</p>
                                                <input type="text" class="form-control form-input" name="mobile" id="input_mobile" value="{{ $customer->details->mobile }}" maxlength="13">
                                                <p id="p_mobile" class="text-danger" style="display: none;"><small>The mobile field is required.</small></p>
                                            </div>

                                            <div class="gap-10"></div>
                                            <div class="form-group form-wrap">
                                                <p>Country *</p>
                                                <select name="country" id="country" class="form-control form-input">
                                                    <option value="">-- Select Country --</option>
                                                    @foreach(Setting::countries() as $country)
                                                    <option @if($customer->details->country == "" && $country->id == 259) selected 
                                                    @elseif($customer->details->country == $country->id) selected @endif value="{{$country->id}}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                                <p id="alert_countryrate" style="display: none;" class="text-danger"><small>The country selected has no shipping rate in the system. Please expect an updated invoice once the order is confirmed.</small></p>
                                                <p id="p_country" class="text-danger" style="display: none;"><small>The country field is required.</small></p>
                                            </div>

                                            <div id="divIntlAddress" style="display: @if($customer->details->country <> 259 && $customer->details->country != "") block; @else none; @endif">
                                                <div class="gap-10"></div>
                                                <div class="form-group form-wrap">
                                                    <p>Shipping Address *</p>
                                                    <textarea name="billing_address" class="form-control form-input" rows="3" id="billing_address" maxlength="60">{{ $customer->details->intl_address }}</textarea>
                                                    <p id="p_otheradd" class="text-danger" style="display: none;"><small>The shipping address field is required.</small></p>
                                                </div>
                                            </div>

                                            <div id="divLocalAddress" style="display: @if($customer->details->country == '' || $customer->details->country == 259) block; @else none; @endif">
                                                <div class="gap-10"></div>
                                                <div class="form-group form-wrap">
                                                    <p>Province *</p>
                                                    <select name="province" id="province" class="form-control form-input">
                                                        <option value="">-- Select Province --</option>
                                                        @foreach(Setting::provinces() as $province)
                                                        <option @if($customer->details->province == $province->id) selected @endif value="{{$province->id}}">{{ $province->province }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p id="p_province" class="text-danger" style="display: none;"><small>The province field is required.</small></p>
                                                </div>
                                                <div class="gap-10"></div>
                                                <div class="form-group form-wrap">
                                                    <p>City/Municipality *</p>
                                                    <select required class="form-control form-input" name="city" id="city">
                                                    <option value="">-- Select City --</option>
                                                    @if($customer->details->province != '')
                                                        @foreach(Setting::cities() as $city)
                                                            @if($customer->details->province == $city->province)
                                                            <option @if($customer->details->city == $city->id) selected @endif value="{{ $city->id }}">{{ $city->city }}</option>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                    <option value="">-- Select City --</option>
                                                    @endif
                                                    </select>
                                                    <p id="alert_cityrate" @if(\App\ShippingfeeLocations::islocation($customer->details->city,$customer->details->country,$customer->details->province) == 1) style="display: none;" @endif class="text-danger"><small>The city selected has no shipping rate in the system. Please expect an updated invoice once the order is confirmed.</small></p>
                                                    <p id="p_city" class="text-danger" style="display: none;"><small>The city field is required.</small></p>
                                                </div>

                                                <div class="gap-10"></div>
                                                <div class="form-group form-wrap">
                                                    <p>Main Address *</p>
                                                    <input required type="text" class="form-control form-input" name="address" id="input_address" value="{{ $customer->details->address }}" maxlength="40">
                                                    <p id="p_address" class="text-danger" style="display: none;"><small>The main address field is required.</small></p>
                                                </div>

                                                <div class="gap-10"></div>
                                                <div class="form-group form-wrap">
                                                    <p>Alternative Address</p>
                                                    <input type="text" class="form-control form-input" name="barangay" id="input_barangay" value="{{ $customer->details->barangay }}">
                                                </div>
                                            </div>

                                            <div class="gap-10"></div>
                                            <div class="form-group form-wrap">
                                                <p>Zip Code</p>
                                                <input type="text" class="form-control form-input" name="zipcode" id="input_zipcode" value="{{ $customer->details->zipcode }}">
                                            </div>

                                            <div class="gap-10"></div>
                                            <div class="form-group form-wrap">
                                                <p>Other Instruction</p>
                                                <textarea name="other_instruction" class="form-control form-input" rows="3" id="other_instruction" placeholder="You can input your full address here..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="checkout-card">
                                            <p class="mb-3">Select a shipping method:</p>
                                            @php
                                                $cities = \App\ShippingfeeLocations::nearby_provinces();
                                                $nearby_cities = "";
                                                $list = explode('|',$cities);
                                                foreach($list as $city){
                                                    $nearby_cities .= $city.',';
                                                }

                                                $stp_allowed_days = "";
                                                $stp_days = explode('|',$stp->allowed_days);

                                                foreach($stp_days as $day){
                                                    $stp_allowed_days .= date('N',strtotime($day)).',';
                                                }

                                                $sdd_arr = [];
                                                $sdd_days = explode('|',$sdd->allowed_days);

                                                foreach($sdd_days as $day){
                                                    array_push($sdd_arr,$day);
                                                }

                                            @endphp

                                            <!-- Store Pick Up -->
                                            <input type="hidden" id="time_from" value="{{ $stp->allowed_time_from }}">
                                            <input type="hidden" id="time_to" value="{{ $stp->allowed_time_to }}">
                                            <input type="hidden" id="array_days" value="{{ rtrim($stp_allowed_days,',') }}">
                                            <input type="hidden" id="array_cities" value="{{ rtrim($nearby_cities,',') }}">
                                            
                                            <div class="tab-wrap custom vertical">
                                                @if($amount <= $cod->maximum_purchase)
                                                    @if($customer->details->country == '')
                                                        <input type="radio" id="tab1" name="shipOption" value="1" class="tab">
                                                        <label style="display: none;" id="cod_label" for="tab1">Cash On Delivery (COD) <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                        <div class="tab__content">
                                                            <h3>Cash on Delivery</h3>
                                                            <div class="alert alert-info" role="alert">
                                                                <h4 class="alert-heading">Reminder!</h4>
                                                                <p>{!! $cod->reminder !!}</p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <!-- If customer country is Philippines -->
                                                        @if($customer->details->country == 259)
                                                        <!-- If metro manila -->
                                                            @if($customer->details->province == 49)
                                                                <!-- If COD for metro manila is allowed-->
                                                                @if($cod->within_metro_manila == 1)
                                                                    <input type="radio" id="tab1" name="shipOption" value="1" class="tab">
                                                                    <label id="cod_label" for="tab1">Cash On Delivery (COD) <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                                    <div class="tab__content">
                                                                        <h3>Cash on Delivery</h3>
                                                                        <div class="alert alert-info" role="alert">
                                                                            <h4 class="alert-heading">Reminder!</h4>
                                                                            <p>{!! $cod->reminder !!}</p>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            <!-- If not metro manila -->
                                                            @else
                                                                <!-- If COD allowed for outside metro manila -->
                                                                @if($cod->outside_metro_manila == 1)
                                                                    <input type="radio" id="tab1" name="shipOption" value="1" class="tab">
                                                                    <label id="cod_label" for="tab1">Cash On Delivery (COD) <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                                    <div class="tab__content">
                                                                        <h3>Cash on Delivery</h3>
                                                                        <div class="alert alert-info" role="alert">
                                                                            <h4 class="alert-heading">Reminder!</h4>
                                                                            <p>{!! $cod->reminder !!}</p>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <input type="radio" id="tab1" name="shipOption" value="1" class="tab">
                                                                    <label style="display: none;" id="cod_label" for="tab1">Cash On Delivery (COD) <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                                    <div class="tab__content">
                                                                        <h3>Cash on Delivery</h3>
                                                                        <div class="alert alert-info" role="alert">
                                                                            <h4 class="alert-heading">Reminder!</h4>
                                                                            <p>{!! $cod->reminder !!}</p>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                            <!-- end if metro manila -->
                                                        @else
                                                            <input type="radio" id="tab1" name="shipOption" value="1" class="tab">
                                                            <label style="display: none;" id="cod_label" for="tab1">Cash On Delivery (COD) <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                            <div class="tab__content">
                                                                <h3>Cash on Delivery</h3>
                                                                <div class="alert alert-info" role="alert">
                                                                    <h4 class="alert-heading">Reminder!</h4>
                                                                    <p>{!! $cod->reminder !!}</p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endif
                                                
                                                @if($forPickupCounter == $totalproducts)
                                                    @if($customer->details->country == 259 || $customer->details->country == '')
                                                    <input type="radio" id="tab2" name="shipOption" value="2" class="tab">
                                                    <label id="stp_label" for="tab2">Store Pick-up <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                    <div class="tab__content">
                                                        <h3>Store Pick-up</h3>
                                                        <div class="alert alert-info" role="alert">
                                                            <h4 class="alert-heading">Reminder!</h4>
                                                            <p>{!! $stp->reminder !!}</p>
                                                        </div>
                                                        <div class="form-row form-style fs-sm">
                                                            <div class="col">
                                                                <label>Select Branch*</label>
                                                                <select class="form-control form-input" name="branch" id="selbranch">
                                                                    <option selected value="0">-- Select Branch --</option>
                                                                    @foreach(Setting::branches() as $branch)
                                                                    <option value="{{$branch->name}}">{{ $branch->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <p id="stp_branch" class="text-danger" style="display: none;"><small>The branch field is required.</small></p>
                                                            </div>
                                                        </div>
                                                        <div class="gap-10"></div>
                                                        <div class="form-row form-style fs-sm">
                                                            <div class="col-lg-12 mb-sm-2">
                                                                <label>Pick-up Date *</label>
                                                                <input type="date" name="pickup_date" onchange="pickupDate()" id="pickup_date" class="form-control form-input"
                                                                    min="{{date('Y-m-d',strtotime(today()->addDays(1)))}}" max="{{date('Y-m-d',strtotime(today()->addDays(30)))}}">
                                                                <p id="stp_date" class="text-danger" style="display: none;"><small>The date field is required.</small></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @else
                                                    <input type="radio" id="tab2" name="shipOption" value="2" class="tab">
                                                    <label style="display: none;" id="stp_label" for="tab2">Store Pick-up <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                    <div class="tab__content">
                                                        <h3>Store Pick-up</h3>
                                                        <div class="alert alert-info" role="alert">
                                                            <h4 class="alert-heading">Reminder!</h4>
                                                            <p>{!! $stp->reminder !!}</p>
                                                        </div>
                                                        <div class="form-row form-style fs-sm">
                                                            <div class="col">
                                                                <label>Select Branch*</label>
                                                                <select class="form-control form-input" name="branch" id="selbranch">
                                                                    <option selected value="0">-- Select Branch --</option>
                                                                    @foreach(Setting::branches() as $branch)
                                                                    <option value="{{$branch->name}}">{{ $branch->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <p id="stp_branch" class="text-danger" style="display: none;"><small>The branch field is required.</small></p>
                                                            </div>
                                                        </div>
                                                        <div class="gap-10"></div>
                                                        <div class="form-row form-style fs-sm">
                                                            <div class="col-lg-12 mb-sm-2">
                                                                <label>Pick-up Date *</label>
                                                                <input type="date" name="pickup_date" onchange="pickupDate()" id="pickup_date" class="form-control form-input"
                                                                    min="{{date('Y-m-d',strtotime(today()->addDays(1)))}}" max="{{date('Y-m-d',strtotime(today()->addDays(30)))}}">
                                                                <p id="stp_date" class="text-danger" style="display: none;"><small>The date field is required.</small></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                @endif

                                                @if($amount <= $sdd->maximum_purchase)
                                                    @if(in_array(date('D'),$sdd_arr))
                                                        @if(date('H:i') > $sdd->allowed_time_from && date('H:i') < $sdd->allowed_time_to)
                                                            @if($customer->details->country == '')
                                                                <input type="radio" id="tab3" name="shipOption" value="4" class="tab">
                                                                <label id="sdd_label" for="tab3">Same Day Delivery <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                                <div class="tab__content">
                                                                    <div class="alert alert-info" role="alert">
                                                                        <h4 class="alert-heading">Reminder!</h4>
                                                                        <p>{!! $sdd->reminder !!}</p>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="checkbox" class="form-check-input" name="bookingType" id="exampleCheck1">
                                                                        <label class="form-check-label" for="exampleCheck1"><strong>Book Your Own Rider</strong></label>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                @if($customer->details->country == 259)
                                                                    @if($customer->details->city != '')
                                                                        @if(\App\ShippingfeeLocations::checkNearbyProvinces($customer->details->cities->city) > 0)
                                                                            <input type="radio" id="tab3" name="shipOption" value="4" class="tab">
                                                                            <label id="sdd_label" for="tab3">Same Day Delivery <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                                            <div class="tab__content">
                                                                                <div class="alert alert-info" role="alert">
                                                                                    <h4 class="alert-heading">Reminder!</h4>
                                                                                    <p>{!! $sdd->reminder !!}</p>
                                                                                </div>
                                                                                <div class="form-check">
                                                                                    <input type="checkbox" class="form-check-input" name="bookingType" id="exampleCheck1">
                                                                                    <label class="form-check-label" for="exampleCheck1"><strong>Book Your Own Rider</strong></label>
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <input type="radio" id="tab3" name="shipOption" value="4" class="tab">
                                                                            <label style="display: none;" id="sdd_label" for="tab3">Same Day Delivery <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                                            <div class="tab__content">
                                                                                <div class="alert alert-info" role="alert">
                                                                                    <h4 class="alert-heading">Reminder!</h4>
                                                                                    <p>{!! $sdd->reminder !!}</p>
                                                                                </div>
                                                                                <div class="form-check">
                                                                                    <input type="checkbox" class="form-check-input" name="bookingType" id="exampleCheck1">
                                                                                    <label class="form-check-label" for="exampleCheck1"><strong>Book Your Own Rider</strong></label>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @else
                                                                        <input type="radio" id="tab3" name="shipOption" value="4" class="tab">
                                                                        <label style="display: none;" id="sdd_label" for="tab3">Same Day Delivery <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                                        <div class="tab__content">
                                                                            <div class="alert alert-info" role="alert">
                                                                                <h4 class="alert-heading">Reminder!</h4>
                                                                                <p>{!! $sdd->reminder !!}</p>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input type="checkbox" class="form-check-input" name="bookingType" id="exampleCheck1">
                                                                                <label class="form-check-label" for="exampleCheck1"><strong>Book Your Own Rider</strong></label>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @else
                                                                    <input type="radio" id="tab3" name="shipOption" value="4" class="tab">
                                                                    <label style="display: none;" id="sdd_label" for="tab3">Same Day Delivery <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                                    <div class="tab__content">
                                                                        <div class="alert alert-info" role="alert">
                                                                            <h4 class="alert-heading">Reminder!</h4>
                                                                            <p>{!! $sdd->reminder !!}</p>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input type="checkbox" class="form-check-input" name="bookingType" id="exampleCheck1">
                                                                            <label class="form-check-label" for="exampleCheck1"><strong>Book Your Own Rider</strong></label>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif

                                                <input type="radio" id="tab4" name="shipOption" value="3" class="tab">
                                                <label for="tab4">Door-to-door (D2D) <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                {{--<div class="tab__content">
                                                    <h3>Door-to-door</h3>
                                                </div>--}}
                                            </div>

                                            <ul class="list-unstyled lh-7 pd-r-10" style="display: none;">
                                                <li class="d-flex justify-content-between">
                                                    <span>Total Puchase Amount</span>
                                                    <span>
                                                        <input type="text" id="total_purchased_amount" value="{{$amount}}">
                                                    </span>
                                                </li>

                                                <li class="d-flex justify-content-between">
                                                    <span>Total Weight (Kg)</span>
                                                    <span>
                                                        <input type="text" id="total_weight" value="{{number_format(($weight/1000),2,'.','')}}">
                                                    </span>
                                                </li>

                                                <li class="d-flex justify-content-between">
                                                    <span>COD Within Metro Manila</span>
                                                    <span>
                                                        <input type="text" id="within_metro_manila" value="{{$cod->within_metro_manila}}">
                                                    </span>
                                                </li>
                                                <li class="d-flex justify-content-between">
                                                    <span>COD Outside Metro Manila</span>
                                                    <span>
                                                        <input type="text" id="outside_metro_manila" value="{{$cod->outside_metro_manila}}">
                                                    </span>
                                                </li>

                                                <li class="d-flex justify-content-between">
                                                    <span>COD Minimum Purchase</span>
                                                    <span>
                                                        <input type="text" id="cod_min_purchase" value="{{ $cod->minimum_purchase }}">
                                                    </span>
                                                </li>

                                                <li class="d-flex justify-content-between">
                                                    <span>COD Maximum Purchase</span>
                                                    <span>
                                                        <input type="text" id="cod_max_purchase" value="{{ $cod->maximum_purchase }}">
                                                    </span>
                                                </li>

                                                <li class="d-flex justify-content-between">
                                                    <span>COD Service Fee</span>
                                                    <span>
                                                        <input type="text" id="cod_service_fee" value="{{ $cod->service_fee }}">
                                                    </span>
                                                </li>

                                                <li class="d-flex justify-content-between">
                                                    <span>SDD Service Fee</span>
                                                    <span>
                                                        <input type="text" id="sdd_service_fee" value="{{ $sdd->service_fee }}">
                                                    </span>
                                                </li>

                                                <li class="d-flex justify-content-between">
                                                    <span>Shipping Fee</span>
                                                    <span>
                                                        <input type="text" id="shipping_fee" value="{{\App\ShippingfeeLocations::shipping_fee($customer->details->province,$customer->details->city,$customer->details->country,number_format(($weight/1000),2,'.','')) }}">
                                                    </span>
                                                </li>
                                                <li class="d-flex justify-content-between">
                                                    <span>Is Location</span>
                                                    <span>
                                                        <input type="text" id="islocation" name="islocation" value="{{ \App\ShippingfeeLocations::islocation($customer->details->city,$customer->details->country,$customer->details->province) }}">
                                                    </span>
                                                </li>
                                                <li class="d-flex justify-content-between">
                                                    <span>SDD Maximum Purchase</span>
                                                    <span>
                                                        <input type="text" id="sdd_max_purchase" value="{{ $sdd->maximum_purchase }}">
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="checkout-card mt-3">
                                            <p class="mb-3">Select a coupon:</p>
                                            <div id="cart-wrapper" class="p-0">
                                            <div class="summary-wrap">
                                                <div class="promo-code">
                                                    <label for="promo">Enter promo code or <span class="white-spc">gift card</span></label>
                                                    <div class="input-group">
                                                        <input type="text" class="promo-input form-control" name="promo" id="promo">
                                                    </div>
                                                    <div class="field_wrapper"></div>

                                                    <a class="mt-2 mb-2 text-primary" href="#" style="font-size:12px;" onclick="myCoupons()"> or click here to  Select from My Coupons</a>

                                                    <div class="mt-2">
                                                        <button class="btn promo-btn" type="button">Apply</button>
                                                    </div>

                                                </div>

                                                <div class="subtotal">
                                                    <div class="coupon-item mb-3">
                                                        <div class="coupon-item-name text-white" style="background:#b82e24;">
                                                          <h6 class="p-1 mb-1">HAPPY50</h6>
                                                      </div>
                                                      <div class="coupon-item-desc mb-1">
                                                          <small><strong>Jan 1, 2021 - Dec 31, 2021</strong></small>
                                                          <p class="m-0">Maecenas egestas mollis eros, et hendrerit justo aliquam</p>
                                                      </div>
                                                      <div class="coupon-item-btns">
                                                          <button class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
                                                          <button type="button" class="btn btn-info btn-sm" data-toggle="popover" title="Terms & Condition" data-content="And here's some amazing content. It's very engaging. Right?">Terms & Conditions</button>
                                                      </div>
                                                    </div>
                                                </div>

                                                <div class="subtotal">
                                                    <div class="coupon-item mb-3">
                                                        <div class="coupon-item-name text-white" style="background:#b82e24;">
                                                          <h6 class="p-1 mb-1">HAPPY50</h6>
                                                      </div>
                                                      <div class="coupon-item-desc mb-1">
                                                          <small><strong>Jan 1, 2021 - Dec 31, 2021</strong></small>
                                                          <p class="m-0">Maecenas egestas mollis eros, et hendrerit justo aliquam</p>
                                                      </div>
                                                      <div class="coupon-item-btns">
                                                          <button class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
                                                          <button type="button" class="btn btn-info btn-sm" data-toggle="popover" title="Terms & Condition" data-content="And here's some amazing content. It's very engaging. Right?">Terms & Conditions</button>
                                                      </div>
                                                    </div>
                                                </div>

                                                
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="checkout-nav">
                                <a class="checkout-back-btn" href="{{ route('cart.front.show') }}"><span class="lnr lnr-chevron-left"></span> Back to Cart</a>
                                <a class="checkout-next-btn" href="" id="billingNxtBtn" style="color:white;font-size:1em;font-weight: 700;">Next <span class="lnr lnr-chevron-right"></span></a>
                            </div>
                        </div>

                        <div id="tab-2">
                            <div class="checkout-content">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="subtitle">Billed To</label>
                                        <h3 class="customer-name"><span id="customer-name"></span></h3>
                                        <p class="customer-address">Delivery Type: <span id="delivery-type"></span></p>
                                        <p class="customer-address"><span id="customer-address"></span></p>
                                        <p class="customer-phone" >Mobile No: <span id="customer-phone"></span></p>
                                        <p class="customer-email" id="customer-email">Email: <span id="customer-email"></span></p>
                                    </div>
                                </div>

                                <div class="gap-40"></div>

                                <div class="table-responsive mg-t-40">
                                    <table class="table tbl-responsive table-invoice bd-b order-table">
                                        <thead>
                                            <tr>
                                                <th class="w-25">Items</th>
                                                <th class="w-15">Weight (kg)</th>
                                                <th class="w-15 text-center">Qty</th>
                                                <th class="w-15 text-right">Unit Price</th>
                                                <th class="w-15 text-right">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $totalAmount = 0; $subTotal = 0; $totalProduct = 0; @endphp
                                            @foreach($products as $product)
                                            @php
                                                $totalProduct += 1;
                                                $totalAmount = $product->price*$product->qty;
                                                $subTotal   += $totalAmount;
                                            @endphp
                                            <tr id="cart_{{$product->id}}">
                                                <td class="tx-nowrap text-danger">{{ $product->product->name }}</td>
                                                <td>
                                                    {{ number_format(($product->product->weight/1000),2) }}
                                                    <input type="hidden" id="product_weight_{{$product->product_id}}" value="{{$product->product->weight}}">
                                                    <input type="hidden" class="input_product_total_weight" id="total_product_weight_{{$product->product_id}}" value="{{$product->product->weight*$product->qty}}">
                                                </td>
                                                <td class="text-center">
                                                    {{ $product->qty }}
                                                    <input type="hidden" name="productid[]" value="{{ $product->product_id }}">
                                                    <input type="hidden" name="qty[]" value="{{ $product->qty }}" id="product_qty_{{$product->product_id}}">
                                                </td>
                                                <td class="text-right">₱ {{ number_format($product->price,2) }}
                                                    <input type="hidden" name="product_price[]" id="product_price_{{$product->product_id}}" value="{{ $product->price }}">
                                                </td>
                                                <td class="text-right">
                                                    <input type="hidden" class="input_product_total_amount" id="input_product_total_amount_{{$product->product_id}}" value="{{$totalAmount}}">
                                                    ₱ <span id="product_total_amount_{{$product->product_id}}">{{ number_format($totalAmount,2) }}</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                            <input type="hidden" id="total_product" value="{{$totalProduct}}">
                                        </tbody>
                                    </table>
                                </div>

                                <div class="checkout-bt row justify-content-between">
                                    <div class="col-sm-12 col-lg-6 order-2 order-sm-0 mg-t-40 mg-sm-t-0">
                                        <div class="gap-30"></div>
                                        <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Other Instructions</label>
                                        <p id="p_instructions"></p>
                                    </div>
                                    <!-- col -->
                                    <div class="col-sm-12 col-lg-4 order-1 order-sm-0">
                                        <div class="gap-30"></div>
                                        <ul class="list-unstyled lh-7 pd-r-10">
                                            <li class="d-flex justify-content-between">
                                                <span>Total Weight</span>
                                                <span>
                                                    <input type="hidden" id="input_total_weight" value="{{($weight/1000)}}" name="total_weight">
                                                    <span id="total-weight">{{ number_format(($weight/1000),2) }} </span>kg
                                                </span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span>Sub-Total</span>
                                                <span>
                                                    <input type="hidden" id="input_sub_total" value="{{$subTotal}}" name="subtotal">
                                                    ₱ <span id="sub-total">{{ number_format($subTotal,2) }}</span>
                                                </span>
                                            </li>
                                            @if($loyalty_discount > 0)
                                            <li class="d-flex justify-content-between">
                                                <span class="text-danger">LESS: Loyalty Discount ({{number_format($loyalty_discount,0)}}%)</span>
                                                <span>
                                                    <input type="hidden" id="input_loyalty_discount" name="loyaltydiscount" value="{{$loyalty_discount}}">
                                                    <input type="hidden" id="input_discount_amount" name="discount_amount">
                                                    <span class="text-danger" id="span_discount"> </span>
                                                </span>
                                            </li>
                                            @else
                                                <input type="hidden" id="input_loyalty_discount" name="loyaltydiscount" value="0">
                                                <input type="hidden" id="input_discount_amount" name="discount_amount">
                                            @endif

                                            <li class="d-flex justify-content-between">
                                                <span id="lispan_shippingfee">ADD: Shipping Fee</span>
                                                <span>
                                                    <input type="hidden" id="input_shippingfee" name="shippingfee">
                                                    <span id="span_shippingfee">0.00</span>
                                                </span>
                                            </li>

                                            @if($ckCouponDiscount > 0)
                                            <li class="d-flex justify-content-between">
                                                <span id="lispan_shippingfee">LESS: Coupon Discount</span>
                                                <span>
                                                    <span id="span_shippingfee">₱ {{ number_format($ckCouponDiscount,2) }}</span>
                                                </span>
                                            </li>
                                            <input type="hidden" id="coupon_discount" value="{{$ckCouponDiscount}}">
                                            @else
                                                <input type="hidden" id="coupon_discount" value="0">
                                            @endif

                                            <li class="d-flex justify-content-between">
                                                <span id="lispan_servicefee">ADD: Service Fee</span>
                                                <span>
                                                    <input name="servicefee" type="hidden" id="input_servicefee" name="servicefee">
                                                    <span id="span_servicefee">0.00</span>
                                                </span>
                                            </li>
                                            
                                            <li class="d-flex justify-content-between">
                                                <strong>TOTAL DUE</strong>
                                                <strong>
                                                    <input type="hidden" name="net_amount" id="input_total_due" name="totalDue">
                                                    ₱ <span id="totalDue"></span>
                                                </strong>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="checkout-nav">
                                <a class="checkout-back-btn" href=""><span class="lnr lnr-chevron-left"></span> Back</a>
                                <a class="checkout-next-btn" href="" id="btnReviewOrder" style="color:white;font-size:1em;font-weight: 700;"><span id="spanReviewOrder">Next</span> <span class="lnr lnr-chevron-right"></span></a>
                            </div>
                        </div>

                        <div id="tab-3">
                            <div class="checkout-content">
                                <h3>Payment Method</h3>
                                @foreach($payment_method as $method)
                                <div id="payment_method_{{$method->id}}">
                                    <label for="method{{$method->id}}">
                                        <input type="radio" name="payment_method" value="{{ $method->id }}" id="method{{$method->id}}"/> {{ $method->name }}
                                        <div class="sub1">
                                        @foreach($method->paymentList as $list)
                                        <div class="mt-1">
                                            <label for="paylist{{$list->id}}">
                                            <input type="radio" name="payment_option" value="{{$list->name}}" id="paylist{{$list->id}}"/> {{ $list->name }}
                                            <div class="sub2">
                                                <div class="mt-1">
                                                    @if($list->type == 'bank')
                                                    <label>Account Name : <span class="text-nowrap">{{ $list->account_name }}</span></label><br>
                                                    @endif
                                                    <label>Account # : <span class="text-nowrap">{{ $list->account_no }}</span></label><br>
                                                    @if($list->type == 'remittance')
                                                    <label>Recipient : <span class="text-nowrap">{{ $list->recipient }}</span></label><br>
                                                    @if($list->qrcode != '')
                                                    <label>QR Code : </label><br>
                                                    <img style="width: 100%;max-width: 150px;" src="{{ asset('storage/qrcodes/'.$list->id.'/'.$list->qrcode) }}">
                                                    @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    </label>
                                </div>   
                                @endforeach    
                            </div>
                            <div class="checkout-nav">
                                <a class="checkout-back-btn" href=""><span class="lnr lnr-chevron-left"></span> Back</a>
                                <a class="checkout-finish-btn" href="" id="btnPlaceOrder">Next <span class="lnr lnr-chevron-right"></span></a>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>
        </section>
    </form>
</main>
@endsection

@section('pagejs')
    <script src="{{ asset('theme/stpaul/plugins/responsive-tabs/js/jquery.responsiveTabs.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#input_mobile").keypress(function (e) {
                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });

            $('select[name="country"]').on('change', function() {
                var country = $(this).val();
                var weight  = $('#total_weight').val();
                var city    = $('#city').val(); 

                if(country != ""){
                    if(country == 259){
                        $('#divLocalAddress').css('display','block');
                        $('#divIntlAddress').css('display','none');

                        $('#alert_countryrate').css('display','none');

                        $('#shipping_fee').val(0);

                    } else {

                        $('#cod_label').css('display','none');
                        $('#stp_label').css('display','none');
                        $('#sdd_label').css('display','none');

                        $('#divLocalAddress').css('display','none');
                        $('#divIntlAddress').css('display','block');

                        $.ajax({
                            dataType: "json",
                            type: "GET",
                            url: "{{ route('ajax.get-city-rates') }}",
                            data: {
                                'city' : city,
                                'country' : country,
                                'weight' : weight
                            },
                            success: function(response) {
                                
                                if(response.rate == 0){
                                   $('#alert_countryrate').css('display','block');
                                } else {
                                    $('#alert_countryrate').css('display','none');
                                }

                                $('#islocation').val(response.islocation);
                                $('#shipping_fee').val(response.rate);
                            }
                        });
                    }
   
                } else {
                    swal({
                        title: '',
                        text: "Please select a country.",         
                    })
                }
            });

            $('select[name="province"]').on('change', function() {
                var provinceID = $(this).val();
                var codMaxPurchase = $('#cod_max_purchase').val();
                var allowCODMetroManila   = $('#within_metro_manila').val();
                var allowCODOutsideManila = $('#outside_metro_manila').val();
                var totalPuchasedAmount   = $('#total_purchased_amount').val();
                

                if(provinceID) {

                    if(provinceID == 49){
                        if(allowCODMetroManila == 1 ){
                            if(parseFloat(totalPuchasedAmount) <= parseFloat(codMaxPurchase)){
                                $('#cod_label').css('display','block');
                            } else {
                                $('#cod_label').css('display','none');
                            }
                        } else {
                            $('#cod_label').css('display','none');
                        }
                    } else {
                        if(allowCODOutsideManila == 1){
                            if(parseFloat(totalPuchasedAmount) <= parseFloat(codMaxPurchase)){
                                $('#cod_label').css('display','block');
                            } else {
                                $('#cod_label').css('display','none');
                            }
                        } else {
                            $('#cod_label').css('display','none');
                        }
                    }

                    var url = "{{ route('ajax.get-cities', ':provinceID') }}";
                    url = url.replace(':provinceID',provinceID);
                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                            $('select[name="city"]').empty();
                            $('select[name="city"]').append('<option value="">-- Select City --</option>');
                            $.each(data, function(key, value) {
                                $('select[name="city"]').append('<option value="'+value.id+'">'+value.city+'</option>');
                            });
                        }
                    });
                } else {
                    swal({
                        title: '',
                        text: "Please select a province.",         
                    })
                    $('select[name="city"]').empty();
                }
            });

            $('select[name="city"]').on('change', function() {
                var city  = $(this).val();
                var weight  = $('#total_weight').val();
                var country = $('#country').val();

                var province = $('#province').val();  

                var cityName = $("#city option:selected" ).text();
                var sddMaxPurchase = $('#sdd_max_purchase').val();
                var totalPuchasedAmount   = $('#total_purchased_amount').val();

                $.ajax({
                    dataType: "json",
                    type: "GET",
                    url: "{{ route('ajax.get-city-rates') }}",
                    data: {
                        'province' : province,
                        'city' : city,
                        'country' : country,
                        'weight' : weight
                    },
                    success: function(response) {
                        var cities = $('#array_cities').val();
                        var x = cities.split(',');
                        
                        var arr_cities = [];
                        $.each(x, function(index, value){
                            arr_cities.push(value);
                        });

                        if($.inArray(cityName, arr_cities) !== -1){
                            if(parseFloat(totalPuchasedAmount) <= parseFloat(sddMaxPurchase)){
                                $('#sdd_label').css('display','block');
                            } else {
                                $('#sdd_label').css('display','none');
                            }
                        } else {
                            $('#sdd_label').css('display','none');
                        }
                        
                        if(response.islocation == 0){
                            $('#alert_cityrate').css('display','block');
                        } else {
                            $('#alert_cityrate').css('display','none');
                        }

                        $('#islocation').val(response.islocation);
                        $('#shipping_fee').val(response.rate);
                    }
                });
            });
        });
    </script>
@endsection

@section('customjs')
    <script>
        function IsEmail(email) {
            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if(!regex.test(email)) {
                return false;
            } else{
                return true;
            }
        }

        function pickupDate(){
            var allowed_days = [];

            var days = $('#array_days').val();
            var inputdate = $('#pickup_date').val();
            var day = new Date(inputdate).getUTCDay()+1;
            var x = days.split(',');
            
            $.each(x, function(index, value){
                allowed_days.push(value);
            });

            if(day == 1){
                var d = 7;
            } else {
                var d = day-1;
            }

            if(allowed_days.includes(""+d+"")){
  
            } else {
                $('#pickup_date').val('');

                swal({
                    title: '',
                    text: "Sorry! We are not available on that date.",         
                })
            }
        }

        $('#billingNxtBtn').click(function(){
            /* BEGIN BILLING VALIDATION */
                var regex = '/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/';
                var name     = $('#input_fname').val()+' '+$('#input_lname').val(), 
                fname    = $('#input_fname').val(),
                lname    = $('#input_lname').val(),
                email    = $('#input_email').val(), 
                mobile   = $('#input_mobile').val(), 
                address  = $('#input_address').val(),
                province = $('#province').val(),
                city     = $('#city').val(),
                country  = $('#country').val(),
                intl_add = $('#billing_address').val();


                if(country == 259){
                    // Philippines
                    if(fname.length === 0 || lname.length === 0 || email.length === 0 || IsEmail(email) == false || mobile.length === 0 || province === "" || city === "" || address.length === 0){
                        $(this).removeClass('checkout-next-btn');
                    } else {
                        select_shipping_method();
                    }

                } else {
                    if(fname.length === 0 || lname.length === 0 || email.length === 0 || IsEmail(email) == false || mobile.length === 0 || country === "" || intl_add.length === 0){
                        $(this).removeClass('checkout-next-btn');
                    } else {
                        select_shipping_method();
                    }
                }
                

                if(fname.length === 0){ $('#p_fname').show(); } else { $('#p_fname').hide(); }
                if(lname.length === 0){ $('#p_lname').show(); } else { $('#p_lname').hide(); }
                if(mobile.length === 0){ $('#p_mobile').show(); } else { $('#p_mobile').hide(); }
                if(email.length === 0){ 
                    $('#p_email').show(); 
                } else { 
                    $('#p_email').hide(); 
                    if(IsEmail(email)==false){ 
                        $('#p_email_invalid').show(); 
                    } else { 
                        $('#p_email_invalid').hide(); 
                    }
                }

                if(country === ""){ $('#p_country').show(); } else { $('#p_country').hide(); }

                if(country == 259){
                    if(province === ""){ $('#p_province').show(); } else { $('#p_province').hide(); }
                    if(city === ""){ $('#p_city').show(); } else { $('#p_city').hide(); }
                    if(address.length === 0){ $('#p_address').show(); } else { $('#p_address').hide(); }
                } else {
                    if(intl_add.length === 0){ $('#p_otheradd').show(); } else { $('#p_otheradd').hide(); }
                }
            /* END BILLING VALIDATION */

            /* BEGIN SHIPPING VALIDATION */
                var option = $('input[name="shipOption"]:checked').val();

                if(option == 1){
                    $('#spanReviewOrder').html('Place Order');
                    $('#btnReviewOrder').removeClass('checkout-next-btn');
                } else {
                    $('#spanReviewOrder').html('Next');
                    $('#btnReviewOrder').addClass('checkout-next-btn');
                }

                if(option == 2){
                    var stp_branch = $('#selbranch').val(), stp_date = $('#pickup_date').val();

                    if($('#selbranch').val() == 0){ 
                        $('#stp_branch').show(); 
                    } else { 
                        $('#stp_branch').hide(); 
                    }

                    if($('#pickup_date').val() == ''){ 
                        $('#stp_date').show(); 
                    } else { 
                        $('#stp_date').hide(); 
                    }

                    if(stp_branch == 0 || stp_date.length === 0){
                        $(this).removeClass('checkout-next-btn');
                    } else {
                       $(this).addClass('checkout-next-btn'); 
                    }
                }
            /* END SHIPPING VALIDATION */


            /* BEGIN SHIPPING & SERVICE FEE VALIDATION */
                shippingFee(option);
                totalDue();
            /* END SHIPPING VALIDATION */

            /* BEGIN ORDER SUMMARY */
                if($('#country').val() == 259){
                    $('#customer-address').html($('#input_address').val()+' '+$('#input_barangay').val()+', '+$("#city option:selected" ).text()+' '+$("#province option:selected" ).text()+', '+$('#input_zipcode').val()+' '+$("#country option:selected" ).text());
                } else {
                    $('#customer-address').html($('#billing_address').val()+', '+$('#input_zipcode').val()+', '+$("#country option:selected" ).text());
                }

                if(option == 1){
                    $('#delivery-type').html('Cash On Delivery');
                }

                if(option == 2){
                    $('#delivery-type').html('Store Pick-up');
                }

                if(option == 3){
                    $('#delivery-type').html('Door-to-door (D2D)');
                }

                if(option == 4){
                    $('#delivery-type').html('Same Day Delivery');
                }
                
                $('#customer-email').html(email);
                $('#customer-name').html(name);
                $('#p_instructions').html($('#other_instruction').val());
                $('#customer-phone').html(mobile);
            /* END ORDER SUMMARY */
        });

        function select_shipping_method(){
            if(!$("input[name='shipOption']:checked").val()) {        
                swal({
                    title: '',
                    text: "Please select a shipping method!",         
                });

                $('#billingNxtBtn').removeClass('checkout-next-btn');
            } else {
                $('#billingNxtBtn').addClass('checkout-next-btn');
            }
        }

        function shippingFee(option){

            var codServiceFee = $('#cod_service_fee').val();
            var sddServiceFee = $('#sdd_service_fee').val();
            var shippingfee   = $('#shipping_fee').val();

            if (shippingfee == 0){
                $('#lispan_shippingfee').hide();
                $('#span_shippingfee').hide();
                $('#input_shippingfee').val(0);
            }

            if(option == 1 || option == 3 || option == 4){

                if(option == 4){
                    if($('#exampleCheck1').is(":checked")){
                        if(sddServiceFee > 0){
                            $('#input_servicefee').val(sddServiceFee);
                            $('#span_servicefee').html('₱ '+FormatAmount(sddServiceFee,2));
                            $('#lispan_servicefee').show();
                            $('#span_servicefee').show();

                        } else {
                            $('#lispan_servicefee').hide();
                            $('#span_servicefee').hide();
                            $('#input_servicefee').val(0); 

                        }   

                        $('#lispan_shippingfee').hide();
                        $('#span_shippingfee').hide();
                        $('#input_shippingfee').val(0);
                        
                    } else {
                        $('#lispan_servicefee').hide();
                        $('#span_servicefee').hide();
                        $('#input_servicefee').val(0);


                        $('#input_shippingfee').val(shippingfee);
                        $('#span_shippingfee').html('₱ '+FormatAmount(shippingfee,2));
                        $('#lispan_shippingfee').show();
                        $('#span_shippingfee').show();
                    }
                    

                } else {
                    if(option == 1){
                        $('#input_servicefee').val(codServiceFee);
                        $('#span_servicefee').html('₱ '+FormatAmount(codServiceFee,2));
                        $('#lispan_servicefee').show();
                        $('#span_servicefee').show();
                    } else {
                        $('#lispan_servicefee').hide();
                        $('#span_servicefee').hide();
                        $('#input_servicefee').val(0);
                    }

                    $('#input_shippingfee').val(shippingfee);
                    $('#span_shippingfee').html('₱ '+FormatAmount(shippingfee,2));
                }
                

            } else {
                $('#lispan_servicefee').hide();
                $('#span_servicefee').hide();
                $('#input_servicefee').val(0);

                $('#input_shippingfee').val(0);
                $('#span_shippingfee').html('0.00');
            }

        }

        function totalDue(){
            var subtotal    = $('#input_sub_total').val();
            var shippingfee = $('#input_shippingfee').val();
            var servicefee  = $('#input_servicefee').val();

            var loyalty     = parseFloat($('#input_loyalty_discount').val());
            var coupon      = parseFloat($('#coupon_discount').val());


            var disc = (loyalty / 100).toFixed(2); //its convert 10 into 0.10
            var loyaltyDiscount = subtotal * disc; // gives the value for subtract from main value

            // total discount (loyalty and coupon)
            var totalDiscount = loyalty+coupon;

            var grandTotal = (parseFloat(subtotal)+parseFloat(shippingfee)+parseFloat(servicefee))-parseFloat(totalDiscount);

            $('#input_discount_amount').val(loyaltyDiscount);
            $('#span_discount').html('₱ '+FormatAmount(loyaltyDiscount,2));
            $('#input_total_due').val(grandTotal);
            $('#totalDue').html(FormatAmount(grandTotal,2));
        }

        $('#btnReviewOrder').click(function(){
            var option  = $("input:radio[name='shipOption']:checked").val();

            if(option == 1){
                $("#checkout-form").submit();
            }
        });

        $('#btnPlaceOrder').click(function(){
            var option = $('input[name="payment_method"]:checked').val();

            if (!$("input[name='payment_method']:checked").val()) {

                swal({
                    title: '',
                    text: "Please select a payment method!",         
                });

               $(this).removeClass('checkout-next-btn');
            }
            else {
                if(option == 1){
                    // swal({
                    //     showConfirmButton: false,
                    //     title: '',
                    //     text: "Please wait we are redirecting you to the payment gateway.",         
                    // });
                    $("#checkout-form").submit();
                } else {
                    if (!$("input[name='payment_option']:checked").val()) {
                       
                       swal({
                            title: '',
                            text: "Please select a payment method!",         
                        });

                       $(this).removeClass('checkout-next-btn');
                    } else {
                      $(this).addClass('checkout-next-btn');  
                      $("#checkout-form").submit();
                    }
                }
            }
        });


        function FormatAmount(number, numberOfDigits) {

            var amount = parseFloat(number).toFixed(numberOfDigits);
            var num_parts = amount.toString().split(".");
            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            return num_parts.join(".");
        }


        function myCoupons(){
            var hasProduct = $('#cproducts').val();
            if(hasProduct == ''){
                swal({
                    title: '',
                    text: "Your shopping cart is empty. Please add at least one (1) product.",         
                });
                return false;
            }

            let totalAmount = 0;  
            let totalQty = 0; 
            for(x = 1; x <= {{ $totalCartProducts }}; x++){          
                totalAmount += parseFloat($('#input_order'+x+'_product_total_price').val());
                totalQty    += parseFloat($('#order'+x+'_qty').val());
            }

            $.ajax({
                type: "GET",
                url: "{{ route('display.coupons') }}",
                data: {
                    'total_amount' : totalAmount,
                    'total_qty' : totalQty,
                    'page_name' : 'cart',
                },
                success: function( response ) {
                    $('#coupons_tbl').empty();

                    // array selected coupon : used to check if coupon is already selected
                        var arr_selected_coupons = [];
                        $("input[name='couponid[]']").each(function() {
                            arr_selected_coupons.push(parseInt($(this).val()));
                        });
                    //

                    // array cart product id, brand, category
                        var arr_cart_products = [];
                        $("input[name='productid[]']").each(function() {
                            arr_cart_products.push(parseInt($(this).val()));
                        });

                        var arr_cart_brands = [];
                        $("input[name='productbrand[]']").each(function() {
                            if($(this).val() != ''){
                                arr_cart_brands.push($(this).val());
                            }
                        });

                        var arr_cart_categories = [];
                        $("input[name='productcatid[]']").each(function() {
                            arr_cart_categories.push(parseInt($(this).val()));
                        });
                    //

                    $.each(response.coupons, function(key, coupon) {
                        // coupon validity label
                            if(coupon.start_time == null){
                                var couponStartDate = coupon.start_date;
                            } else {
                                var couponStartDate = coupon.start_date+' '+coupon.start_time;
                            }
                            
                            if(coupon.end_date == null){
                                var couponEndDate = '';
                            } else {
                                if(coupon.end_time == null){
                                    var couponEndDate = ' - '+coupon.end_date;
                                } else {
                                    var couponEndDate = ' - '+coupon.end_date+' '+coupon.end_time;
                                }
                            }
                            var couponValidity = couponStartDate+''+couponEndDate;
                        //
                        

                        if(jQuery.inArray(coupon.id, response.availability) !== -1){ 
                            // condition
                                if(jQuery.inArray(coupon.id, arr_selected_coupons) !== -1){
                                    var usebtn = '<button class="btn btn-danger btn-sm" disabled>Applied</button>';
                                } else {
                                    if(coupon.amount_discount_type == 1){
                                        var qty_counter = 0;
                                        if(coupon.free_product_id != null){
                                            qty_counter++;
                                            var usebtn = '<button class="btn btn-danger btn-sm" id="couponBtn'+coupon.id+'" onclick="free_product_coupon('+coupon.id+')"><span id="btnCpnTxt'+coupon.id+'">Use Coupon</span></button>';

                                        } else {
                                            qty_counter++;
                                            var usebtn = '<button class="btn btn-danger btn-sm" id="couponBtn'+coupon.id+'" onclick="use_coupon_total_amount('+coupon.id+')"><span id="btnCpnTxt'+coupon.id+'">Use Coupon</span></button>';
                                        }
                                    } else {

                                        if(coupon.product_discount == 'current'){
                                            // products
                                                if(coupon.purchase_product_id != null){
                                                    var product_counter = 0;
                                                    var arr_purchase_products = [];
                                                    var coupon_purchase_product = coupon.purchase_product_id.split('|');

                                                    // check if customer buys product under set products
                                                    $.each(coupon_purchase_product, function(key, productID) {
                                                        if(productID != ''){
                                                            arr_purchase_products.push(parseInt(productID));    
                                                        }

                                                        if(jQuery.inArray(parseInt(productID), arr_cart_products) !== -1){
                                                            product_counter++;
                                                        }
                                                    });

                                                    if(product_counter > 0){
                                                        var qty_counter = 0;
                                                        // each cart product
                                                        $.each(arr_cart_products, function(key, product) {

                                                            if(jQuery.inArray(parseInt(product), arr_purchase_products) !== -1){
                                                                var iteration   = $('#iteration'+parseInt(product)).val();
                                                                var product_qty = $('#order'+iteration+'_qty').val();
                                                                // total amount purchase

                                                                // total qty purchase
                                                                // enable Use Coupon button if cart qty is greater than set coupon purchase qty 
                                                                if(product_qty > parseFloat(coupon.purchase_qty)){
                                                                    qty_counter++;
                                                                }
                                                            }
                                                        });
                                                    }
                                                }
                                            //

                                            // product categories
                                                if(coupon.purchase_product_cat_id != null){
                                                    var category_counter = 0;
                                                    var arr_purchase_categories = [];
                                                    var category_split = coupon.purchase_product_cat_id.split('|');

                                                    // check if customer buys product under set category
                                                    $.each(category_split, function(key, value) {
                                                        if(value != ''){
                                                            arr_purchase_categories.push(parseInt(value));    
                                                        }
                                                        
                                                        if(jQuery.inArray(parseInt(value), arr_cart_categories) !== -1){
                                                            category_counter++;
                                                        }
                                                    });

                                                    if(category_counter > 0){
                                                        var qty_counter = 0;
                                                        // each cart category
                                                        $.each(response.cart_per_category, function(key, category) {

                                                            if(jQuery.inArray(parseInt(category), arr_purchase_categories) !== -1){
                                                                var category_qty = response.cart_qty_per_category[key];
                                                                // total amount purchase

                                                                // total qty purchase
                                                                // enable Use Coupon button if cart qty is greater than set coupon purchase qty 
                                                                if(category_qty > parseFloat(coupon.purchase_qty)){
                                                                    qty_counter++;
                                                                }
                                                            }
                                                        });
                                                    }
                                                }
                                            //

                                            // product brands
                                                if(coupon.purchase_product_brand != null){
                                                    var brand_counter = 0;
                                                    var arr_purchase_brands = [];
                                                    var brand_split = coupon.purchase_product_brand.split('|');

                                                    // check if customer buys product under set category
                                                    $.each(brand_split, function(key, brand) {
                                                        if(brand != ''){
                                                            arr_purchase_brands.push(brand);    
                                                        }
                                                        
                                                        if(jQuery.inArray(brand, response.cart_per_brand) !== -1){
                                                            brand_counter++;
                                                        }
                                                    });

                                                    if(brand_counter > 0){
                                                        var qty_counter = 0;
                                                        // each cart brand
                                                        $.each(response.cart_per_brand, function(key, brand) {

                                                            if(jQuery.inArray(brand, arr_purchase_brands) !== -1){
                                                                var brand_qty = response.cart_qty_per_brand[key];
                                                                // total amount purchase

                                                                // total qty purchase
                                                                // enable Use Coupon button if cart qty is greater than set coupon purchase qty 
                                                                if(brand_qty > parseFloat(coupon.purchase_qty)){
                                                                    qty_counter++;
                                                                }
                                                            }
                                                        });
                                                    }
                                                }
                                            //
                                        }

                                        if(coupon.product_discount == 'specific'){
                                            var product_counter = 0;
                                            // check if customer buys product under set category
                                            if(jQuery.inArray(parseInt(coupon.discount_product_id), arr_cart_products) !== -1){
                                                product_counter++;
                                            }

                                            if(product_counter > 0){
                                                var qty_counter = 0;

                                                var iteration = $('#iteration'+parseInt(coupon.discount_product_id)).val();
                                                var product_qty = $('#quantity'+iteration).val();
                                                // total amount purchase

                                                // total qty purchase
                                                // enable Use Coupon button if cart qty is greater than set coupon purchase qty 
                                                if(product_qty > parseFloat(coupon.purchase_qty)){
                                                    qty_counter++;
                                                }

                                                if(qty_counter > 0){
                                                    var usebtn = '<button class="btn btn-danger btn-sm" id="couponBtn'+coupon.id+'" onclick="use_coupon_on_product('+coupon.id+')"><span id="btnCpnTxt'+coupon.id+'">Use Coupon</span></button>';
                                                } else {
                                                    var usebtn = '<button class="btn btn-danger btn-sm" disabled>Use Coupon</button>';
                                                }
                                            }
                                        }

                                        if(qty_counter > 0){
                                            var usebtn = '<button class="btn btn-danger btn-sm" id="couponBtn'+coupon.id+'" onclick="use_coupon_on_product('+coupon.id+')"><span id="btnCpnTxt'+coupon.id+'">Use Coupon</span></button>';
                                        } else {
                                            var usebtn = '<button class="btn btn-secondary btn-sm" disabled>Use Coupon</button>';
                                        }
                                    }
                                }
                            //
                            if(qty_counter > 0){
                                // coupon met the required conditions
                                $('#coupons_tbl').append(
                                    '<div class="coupon-item p-2 border border-info rounded mb-1">'+
                                        // coupons input
                                            // coupon details
                                            '<input type="hidden" id="couponvalidity'+coupon.id+'" value="'+couponValidity+'">'+
                                            '<input type="hidden" id="couponcode'+coupon.id+'" value="'+coupon.coupon_code+'">'+
                                            '<input type="hidden" id="couponname'+coupon.id+'" value="'+coupon.name+'">'+
                                            '<input type="hidden" id="coupondesc'+coupon.id+'" value="'+coupon.description+'">'+
                                            '<input type="hidden" id="couponterms'+coupon.id+'" value="'+coupon.terms_and_conditions+'">'+

                                            // coupon combination and remaining usage
                                            '<input type="hidden" id="remainingusage'+coupon.id+'" value="'+response.remaining[key]+'">'+
                                            '<input type="hidden" id="couponcombination'+coupon.id+'" value="'+coupon.combination+'">'+

                                            // coupon condition : purchase products, categories, brands, total qty purchase, total amount purchase
                                            '<input type="hidden" id="couponproducts'+coupon.id+'" value="'+coupon.purchase_product_id+'">'+
                                            '<input type="hidden" id="purchaseproductid'+coupon.id+'" value="'+coupon.purchase_product_id+'">'+
                                            '<input type="hidden" id="couponcategories'+coupon.id+'" value="'+coupon.purchase_product_cat_id+'">'+
                                            '<input type="hidden" id="couponbrands'+coupon.id+'" value="'+coupon.purchase_product_brand+'">'+
                                            '<input type="hidden" id="purchaseqty'+coupon.id+'" value="'+coupon.purchase_qty+'">'+
                                            '<input type="hidden" id="purchaseamount'+coupon.id+'" value="'+coupon.purchase_amount+'">'+

                                            // where to apply discount either current product or specific
                                            '<input type="hidden" id="productdiscount'+coupon.id+'" value="'+coupon.product_discount+'">'+
                                            '<input type="hidden" id="discountproductid'+coupon.id+'" value="'+coupon.discount_product_id+'">'+
                                            
                                            // coupon discount amount
                                            '<input type="hidden" id="discountpercentage'+coupon.id+'" value="'+coupon.percentage+'">'+
                                            '<input type="hidden" id="discountamount'+coupon.id+'" value="'+coupon.amount+'">'+
                                            '<input type="hidden" id="couponfreeproductid'+coupon.id+'" value="'+coupon.free_product_id+'">'+
                                        //
                                        '<div class="coupon-item-name text-white rounded" style="background:#b82e24;">'+
                                            '<small><h6 class="p-1 mb-1">'+coupon.name+'</h6></small>'+
                                        '</div>'+
                                        '<div class="coupon-item-desc mb-1">'+
                                            '<small><strong>'+couponStartDate+''+couponEndDate+'</strong>'+
                                            '<p class="m-0">'+coupon.description+'</p></small>'+
                                        '</div>'+
                                        '<div class="coupon-item-btns">'+usebtn+'&nbsp;'+
                                            '<button type="button" class="btn btn-info btn-sm" data-toggle="popover" title="Terms & Condition" data-content="'+coupon.terms_and_conditions+'">Terms & Conditions</button>'+
                                        '</div>'+
                                    '</div>'
                                );
                            } else {
                                // coupon did not met the required conditions
                                $('#coupons_tbl').append(
                                    '<div class="coupon-item p-2 border border-secondary rounded mb-1">'+
                                        '<div class="coupon-item-name text-white rounded bg-secondary">'+
                                            '<small><h6 class="p-1 mb-1">'+coupon.name+'</h6></small>'+
                                        '</div>'+
                                        '<div class="coupon-item-desc mb-1">'+
                                            '<small><strong>'+couponStartDate+''+couponEndDate+'</strong>'+
                                            '<p class="m-0">'+coupon.description+'</p></small>'+
                                        '</div>'+
                                        '<div class="coupon-item-btns">'+usebtn+'&nbsp;'+
                                            '<button type="button" class="btn btn-info btn-sm" data-toggle="popover" title="Terms & Condition" data-content="'+coupon.terms_and_conditions+'">Terms & Conditions</button>'+
                                        '</div>'+
                                    '</div>'
                                );
                            }
                            
                        } else {
                            // coupon did not met the required conditions
                            $('#coupons_tbl').append(
                                '<div class="coupon-item p-2 border border-secondary rounded mb-1">'+
                                    '<div class="coupon-item-name text-white rounded bg-secondary">'+
                                        '<small><h6 class="p-1 mb-1">'+coupon.name+'</h6></small>'+
                                    '</div>'+
                                    '<div class="coupon-item-desc mb-1">'+
                                        '<small><strong>'+couponStartDate+''+couponEndDate+'</strong>'+
                                        '<p class="m-0">'+coupon.description+'</p></small>'+
                                    '</div>'+
                                    '<div class="coupon-item-btns"><button class="btn btn-secondary btn-sm" disabled>Use Coupon</button>&nbsp;'+
                                        '<button type="button" class="btn btn-info btn-sm" data-toggle="popover" title="Terms & Condition" data-content="'+coupon.terms_and_conditions+'">Terms & Conditions</button>'+
                                    '</div>'+
                                '</div>'
                            );
                        }

                        $('[data-toggle="popover"]').popover();
                    });

                    $('#couponsModal').modal('show');
                }
            });
        }
    </script>
@endsection