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
                                                            <input type="text" class="promo-input form-control" id="coupon_code">
                                                        </div>
                                                        <div class="field_wrapper"></div>

                                                        <a class="mt-2 mb-2 text-primary" href="#" style="font-size:12px;" onclick="myCoupons()"> or click here to  Select from My Coupons</a>

                                                        <div class="mt-2">
                                                            <button class="btn promo-btn" type="button" id="couponManualBtn">Apply</button>
                                                        </div>
                                                    </div>

                                                    <div id="appliedCouponList">
                                                        @php
                                                            $total_coupon = 0; $total_discount = 0; $total_solo_coupon = 0;
                                                        @endphp
                                                        @foreach($appliedCoupons as $coupon)

                                                        @php
                                                            $total_coupon++;
                                                            $total_discount += $coupon->discount;

                                                            if($coupon->details->combination == 0){
                                                                $total_solo_coupon++;
                                                            }
                                                        @endphp
                                                        <div class="subtotal">
                                                            <input type="hidden" name="couponid[]" value="{{ $coupon->coupon_id }}">
                                                            <input type="hidden" name="couponcode[]" value="{{ $coupon->details->coupon_code }}">
                                                            <input type="hidden" name="coupon_productid[]" value="{{ $coupon->product_id }}">
                                                            <input type="hidden" name="coupon_productdiscount[]" value="{{ $coupon->discount }}">
                                                            <input type="hidden" name="is_sfee[]" value="0">
                                                            <div class="coupon-item mb-3">
                                                                <div class="coupon-item-name text-white" style="background:#b82e24;">
                                                                  <h6 class="p-1 mb-1">{{ $coupon->details->name }}</h6>
                                                              </div>
                                                              <div class="coupon-item-desc mb-1">
                                                                  <small><strong>Jan 1, 2021 - Dec 31, 2021</strong></small>
                                                                  <p class="m-0">{{ $coupon->details->name }}</p>
                                                              </div>
                                                              <div class="coupon-item-btns">
                                                                  <button type="button" class="btn btn-info btn-sm" data-toggle="popover" title="Terms & Condition" data-content="{{ $coupon->details->terms_and_conditions }}">Terms & Conditions</button>
                                                              </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
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
                                            @php $totalQty = 0; $totalAmount = 0; $subTotal = 0; $totalProduct = 0; @endphp
                                            @foreach($products as $product)
                                            @php
                                                $totalProduct += 1;
                                                $totalAmount = $product->price*$product->qty;
                                                $totalQty += $product->qty;
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

                                            <li class="d-flex justify-content-between">
                                                <span id="lispan_shippingfee">ADD: Shipping Fee</span>
                                                <span>
                                                    <input type="hidden" id="input_shippingfee" name="shippingfee">
                                                    <span id="span_shippingfee">0.00</span>
                                                </span>
                                            </li>

                                            <li class="d-flex justify-content-between">
                                                <span id="lispan_servicefee">ADD: Service Fee</span>
                                                <span>
                                                    <input name="servicefee" type="hidden" id="input_servicefee" name="servicefee">
                                                    <span id="span_servicefee">0.00</span>
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

                                            @if($total_discount > 0)
                                                <li class="d-flex justify-content-between">
                                                    <span class="text-danger">LESS: Coupon Discount</span>
                                                    <span>
                                                        <span id="span_coupon_discount">₱ {{ number_format($total_discount,2) }}</span>
                                                    </span>
                                                </li>
                                                <input type="hidden" id="coupon_discount" name="coupon_discount" value="{{$total_discount}}">
                                            @else
                                                <input type="hidden" id="coupon_discount" name="coupon_discount" value="0">
                                            @endif

                                            <li class="d-flex justify-content-between">
                                                <span class="text-danger" id="li_less_sfee" style="display:none;">LESS: Shipping Fee</span>
                                                <span id="span_less_sfee" style="display:none;">
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


                    <input type="hidden" name="" id="coupon_counter" value="{{ $total_coupon }}">
                    <input type="hidden" id="coupon_limit" value="{{ Setting::info()->coupon_limit }}">
                    <input type="hidden" id="solo_coupon_counter" value="{{ $total_solo_coupon }}">
                    <input type="hidden" id="coupon_discount_limit" value="{{ Setting::info()->coupon_discount_limit }}">
                    <input type="hidden" id="coupon_total_discount" name="coupon_total_discount" value="{{ $total_discount }}">


                    <input type="hidden" id="sf_discount_coupon" value="0">
                    <input type="hidden" id="sf_discount_amount" name="sf_discount_amount" value="0">

                    <div id="manual-coupon-details"></div>
                </div>
            </div>
        </section>
    </form>
</main>

@include('theme.stpaul.ecommerce.cart.modals')
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
            var subtotal    = parseFloat($('#input_sub_total').val());
            var shippingfee = parseFloat($('#input_shippingfee').val());
            var servicefee  = parseFloat($('#input_servicefee').val());

            var loyalty     = parseFloat($('#input_loyalty_discount').val());

            //coupons 
            var sfee_discount   = parseFloat($('#sf_discount_amount').val());
            var coupon_discount = parseFloat($('#coupon_discount').val());

            var disc = (loyalty / 100).toFixed(2); //its convert 10 into 0.10
            var loyaltyDiscount = subtotal * disc; // gives the value for subtract from main value

            // total discount (loyalty and coupon)
            var totalDiscount = loyalty+coupon_discount+sfee_discount;

            var grandTotal = (subtotal+shippingfee+servicefee)-totalDiscount;

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


        $('#couponManualBtn').click(function(){
            var couponCode = $('#coupon_code').val();
            var grandtotal = parseFloat($('#input_total_due').val());

            if (!$("input[name='shipOption']:checked").val()) {
                swal({
                    title: '',
                    text: "Please select a shipping method!",         
                });
                return false;
            }

            $.ajax({
                data: {
                    "couponcode": couponCode,
                    "_token": "{{ csrf_token() }}",
                },
                type: "post",
                url: "{{route('add-manual-coupon')}}",
                success: function(returnData) {
                    // coupon validity label
                        if(returnData.coupon_details['start_time'] == null){
                            var couponStartDate = returnData.coupon_details['start_date'];
                        } else {
                            var couponStartDate = returnData.coupon_details['start_date']+' '+returnData.coupon_details['start_time'];
                        }
                        
                        if(returnData.coupon_details['end_date'] == null){
                            var couponEndDate = '';
                        } else {
                            if(returnData.coupon_details['end_time'] == null){
                                var couponEndDate = ' - '+returnData.coupon_details['end_date'];
                            } else {
                                var couponEndDate = ' - '+returnData.coupon_details['end_date']+' '+returnData.coupon_details['end_time'];
                            }
                        }
                        var couponValidity = couponStartDate+''+couponEndDate;
                    //

                    if(returnData['not_allowed']){
                        swal({
                            title: '',
                            text: "Sorry, you are not authorized to use this coupon.",         
                        });
                        return false;
                    }
                    
                    if(returnData['exist']){
                        swal({
                            title: '',
                            text: "Coupon already used.",         
                        }); 
                        return false;
                    }

                    if(returnData['not_exist']){
                        swal({
                            title: '',
                            text: "Coupon not found.",         
                        }); 
                        return false;
                    }

                    if(returnData['expired']){
                        swal({
                            title: '',
                            text: "Coupon is already expired.",         
                        }); 
                        return false;
                    }

                    if (returnData['success']) {

                        // coupon validity label
                            if(returnData.coupon_details['start_time'] == null){
                                var couponStartDate = returnData.coupon_details['start_date'];
                            } else {
                                var couponStartDate = returnData.coupon_details['start_date']+' '+returnData.coupon_details['start_time'];
                            }
                            
                            if(returnData.coupon_details['end_date'] == null){
                                var couponEndDate = '';
                            } else {
                                if(returnData.coupon_details['end_time'] == null){
                                    var couponEndDate = ' - '+returnData.coupon_details['end_date'];
                                } else {
                                    var couponEndDate = ' - '+returnData.coupon_details['end_date']+' '+returnData.coupon_details['end_time'];
                                }
                            }
                            var couponValidity = couponStartDate+''+couponEndDate;
                        //

                        $('#manual-coupon-details').append(
                            '<div id="manual_details'+returnData.coupon_details['id']+'">'+
                            // coupons input
                                '<input type="hidden" id="couponcombination'+returnData.coupon_details['id']+'" value="'+returnData.coupon_details['combination']+'">'+
                                '<input type="hidden" id="sfarea'+returnData.coupon_details['id']+'" value="'+returnData.coupon_details['area']+'">'+
                                '<input type="hidden" id="sflocation'+returnData.coupon_details['id']+'" value="'+returnData.coupon_details['location']+'">'+
                                '<input type="hidden" id="sfdiscountamount'+returnData.coupon_details['id']+'" value="'+returnData.coupon_details['location_discount_amount']+'">'+
                                '<input type="hidden" id="sfdiscounttype'+returnData.coupon_details['id']+'" value="'+returnData.coupon_details['location_discount_type']+'">'+
                                '<input type="hidden" id="discountpercentage'+returnData.coupon_details['id']+'" value="'+returnData.coupon_details['percentage']+'">'+
                                '<input type="hidden" id="discountamount'+returnData.coupon_details['id']+'" value="'+returnData.coupon_details['amount']+'">'+
                                '<input type="hidden" id="couponname'+returnData.coupon_details['id']+'" value="'+returnData.coupon_details['name']+'">'+
                                '<input type="hidden" id="couponcode'+returnData.coupon_details['id']+'" value="'+returnData.coupon_details['coupon_code']+'">'+
                                '<input type="hidden" id="couponterms'+returnData.coupon_details['id']+'" value="'+returnData.coupon_details['terms_and_conditions']+'">'+
                                '<input type="hidden" id="coupondesc'+returnData.coupon_details['id']+'" value="'+returnData.coupon_details['description']+'">'+
                                '<input type="hidden" id="couponvalidity'+returnData.coupon_details['id']+'" value="'+couponValidity+'">'+
                            //
                            '</div>'
                        );

                        if(returnData.coupon_details['location'] == null){
                            swal({
                                title: '',
                                text: "Only shipping fee coupon is allowed.",         
                            });

                        } else {
                            if(returnData.coupon_details['amount'] > 0){ 
                                var amountdiscount = parseFloat(returnData.coupon_details['amount']);
                            }

                            if(returnData.coupon_details['percentage'] > 0){
                                var percent  = parseFloat(returnData.coupon_details['percentage'])/100;
                                var discount = parseFloat(grandtotal)*percent;

                                var amountdiscount = parseFloat(discount);
                            }

                            var total = grandtotal-amountdiscount;
                            if(total.toFixed(2) < 1){
                                swal({
                                    title: '',
                                    text: "The total amount is less than the coupon discount.",         
                                });

                                return false;
                            }
                            
                            use_sf_coupon(returnData.coupon_details['id']);
                        }
                    } 
                }
            });
        });

        function myCoupons(){

            if (!$("input[name='shipOption']:checked").val()) {
                swal({
                    title: '',
                    text: "Please select a shipping method!",         
                });
                return false;
            }

            $.ajax({
                type: "GET",
                url: "{{ route('display.coupons') }}",
                data: {
                    'total_amount' : {{ $subTotal }},
                    'total_qty' : {{ $totalQty }},
                    'page_name' : 'checkout',
                },
                success: function( response ) {
                    $('#coupons_tbl').empty();

                    var arr_selected_coupons = [];
                    $("input[name='couponid[]']").each(function() {
                        arr_selected_coupons.push(parseInt($(this).val()));
                    });

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

                            if(jQuery.inArray(coupon.id, arr_selected_coupons) !== -1){
                                var usebtn = '<button class="btn btn-success btn-sm" disabled>Applied</button>';
                            } else {
                                var usebtn = '<button class="btn btn-success btn-sm" id="couponBtn'+coupon.id+'" onclick="use_sf_coupon('+coupon.id+')"><span id="btnCpnTxt'+coupon.id+'">Use Coupon</span></button>';
                            }

                            $('#coupons_tbl').append(
                                '<div class="coupon-item p-2 border border-info rounded mb-1">'+
                                    // coupons input
                                        '<input type="hidden" id="couponcombination'+coupon.id+'" value="'+coupon.combination+'">'+
                                        '<input type="hidden" id="sfarea'+coupon.id+'" value="'+coupon.area+'">'+
                                        '<input type="hidden" id="sflocation'+coupon.id+'" value="'+coupon.location+'">'+
                                        '<input type="hidden" id="sfdiscountamount'+coupon.id+'" value="'+coupon.location_discount_amount+'">'+
                                        '<input type="hidden" id="sfdiscounttype'+coupon.id+'" value="'+coupon.location_discount_type+'">'+
                                        '<input type="hidden" id="discountpercentage'+coupon.id+'" value="'+coupon.percentage+'">'+
                                        '<input type="hidden" id="discountamount'+coupon.id+'" value="'+coupon.amount+'">'+
                                        '<input type="hidden" id="couponname'+coupon.id+'" value="'+coupon.name+'">'+
                                        '<input type="hidden" id="couponcode'+coupon.id+'" value="'+coupon.coupon_code+'">'+
                                        '<input type="hidden" id="couponterms'+coupon.id+'" value="'+coupon.terms_and_conditions+'">'+
                                        '<input type="hidden" id="coupondesc'+coupon.id+'" value="'+coupon.description+'">'+
                                        '<input type="hidden" id="couponvalidity'+coupon.id+'" value="'+couponValidity+'">'+
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

        function coupon_counter(cid){
            var limit           = $('#coupon_limit').val();
            var totalUsedCoupon = $('#coupon_counter').val();
            var solo_coupon_counter = $('#solo_coupon_counter').val();

            var combination = $('#couponcombination'+cid).val();

            var max_amount_coupon_discount = parseFloat($('#coupon_discount_limit').val());
            var couponTotalDiscount = parseFloat($('#coupon_total_discount').val());

            var sfee_coupon_counter = $('#sf_discount_coupon').val();

            if(sfee_coupon_counter > 1){
                swal({
                    title: '',
                    text: "Only one (1) coupon allowed for shipping fee discount.",         
                });
                return false;
            }

            if(parseInt(totalUsedCoupon) < parseInt(limit)){

                if(couponTotalDiscount > max_amount_coupon_discount){
                    swal({
                        title: '',
                        text: "Maximum total coupon discount reached.",         
                    });
                    return false;
                }

                if(combination == 0){
                    if(totalUsedCoupon > 0){
                        swal({
                            title: '',
                            text: "Coupon cannot be used together with other coupons.",         
                        });
                        return false;
                    } else {
                        $('#solo_coupon_counter').val(1);
                        $('#coupon_counter').val(parseInt(totalUsedCoupon)+1);
                        return true;
                    }
                } else {
                    if(solo_coupon_counter > 0){
                        swal({
                            title: '',
                            text: "Unable to use this coupon. A coupon that cannot be used together with other coupon is already been selected.",         
                        });
                        return false;
                    } else {
                        $('#coupon_counter').val(parseInt(totalUsedCoupon)+1);
                        return true;
                    }
                }
            } else {
                swal({
                    title: '',
                    text: "Maximum of "+limit+" coupon(s) only.",         
                });
                return false;
            }
        }

        function addCommas(nStr){
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }

    // shipping fee coupon rewards
        function use_sf_coupon(cid){
            // check total use shipping fee coupons
            var sfcoupon = parseFloat($('#sf_discount_coupon').val());

            if(sfcoupon == 1){
                swal({
                    title: '',
                    text: "Only one (1) coupon for shipping fee discount.",         
                });
                return false;
            }

            // check if selected coupon applicable on selected delivery location
            var option = $('input[name="shipOption"]:checked').val();
            if(option == 2){
                swal({
                    title: '',
                    text: "Shipping fee coupon discount is not applicable on store pick-up!",         
                });
                return false;
            }
            
            if(coupon_counter(cid)){
                var sfarea  = $('#sfarea'+cid).val();

                if(sfarea == 'local'){
                    var loc = $('#city').val();
                } else {
                    var loc = $('#country').val();
                }

                var couponLocation = $('#sflocation'+cid).val();
                var cLocation = couponLocation.split('|');

                var arr_coupon_location = [];
                $.each(cLocation, function(key, value) {
                    arr_coupon_location.push(value);
                });

                var checker = 0;
                if(sfarea == 'all'){
                    checker = 1;
                } else {
                    if(jQuery.inArray(loc, arr_coupon_location) !== -1){
                        checker = 1;
                    }
                }


                if(checker > 0){

                    var name  = $('#couponname'+cid).val();
                    var terms = $('#couponterms'+cid).val();
                    var desc  = $('#coupondesc'+cid).val();
                    var combination = $('#couponcombination'+cid).val();
                    var validity    = $('#couponvalidity'+cid).val();
                    var code = $('#couponcode'+cid).val();

                    var sf_type = $('#sfdiscounttype'+cid).val();

                    if(sf_type == 'full'){
                        sfee_discount = parseFloat($('#input_shippingfee').val());
                        $('#sf_discount_amount').val(sfee_discount); 
                    }

                    if(sf_type == 'partial'){
                        sfee_discount = parseFloat($('#sfdiscountamount'+cid).val());
                        $('#sf_discount_amount').val(sfee_discount.toFixed(2));
                        
                    }

                    $('#appliedCouponList').append(
                        '<div class="subtotal" id="appliedCouponDiv'+cid+'">'+
                            '<div class="coupon-item">'+
                                // coupon inputs
                                    '<input type="hidden" id="coupon_combination'+cid+'" value="'+combination+'">'+
                                    '<input type="hidden" name="couponid[]" value="'+cid+'">'+
                                    '<input type="hidden" name="couponcode[]" value="'+code+'">'+
                                    '<input type="hidden" name="coupon_productid[]" value="0">'+
                                    '<input type="hidden" name="is_sfee[]" value="1">'+
                                    '<input type="hidden" name="coupon_productdiscount[]" id="coupon_sfee_discount'+cid+'" value="'+sfee_discount+'">'+
                                //
                                '<div class="coupon-item-name text-white" style="background:#b82e24;">'+
                                    '<h6 class="p-1 mb-1">'+name+'</h6>'+
                                '</div>'+
                                '<div class="coupon-item-desc mb-1">'+
                                    '<small><strong>'+validity+'</strong></small>'+
                                    '<p class="m-0">'+desc+'</p>'+
                                '</div>'+
                                '<div class="coupon-item-btns">'+
                                    '<button class="btn btn-danger btn-sm couponRemove" id="'+cid+'"><i class="fa fa-times"></i></button>&nbsp;'+
                                    '<button type="button" class="btn btn-info btn-sm" data-toggle="popover" title="Terms & Condition" data-content="'+terms+'">Terms & Conditions</button>'+
                                '</div>'+
                            '</div>'+
                        '</div>'
                    );
                    $('#sf_discount_coupon').val(1);

                    $('#li_less_sfee').css('display','block');
                    $('#span_less_sfee').css('display','block');
                    $('#span_less_sfee').html('₱ '+addCommas(sfee_discount.toFixed(2)));

                    var coupon_total_discount = parseFloat($('#coupon_total_discount').val());
                    var coupon_sfee_discount = coupon_total_discount+sfee_discount;

                    $('#coupon_total_discount').val(coupon_sfee_discount);

                    $('#couponBtn'+cid).prop('disabled',true);
                    $('#btnCpnTxt'+cid).html('Applied');

                } else {
                    swal({
                        title: '',
                        text: "Selected delivery location is not in the coupon location.",         
                    });
                }
            }
        }

        $(document).on('click', '.couponRemove', function(){  
            var id = $(this).attr("id");  

            var sfee_discount = $('#coupon_sfee_discount'+id).val();
            var coupon_total_discount = parseFloat($('#coupon_total_discount').val());
            var coupon_sfee_discount = coupon_total_discount-sfee_discount;

            $('#coupon_total_discount').val(coupon_sfee_discount);

            $('#li_less_sfee').css('display','none');
            $('#span_less_sfee').css('display','none');

            $('#sf_discount_amount').val(0);
            $('#sf_discount_coupon').val(0);

            var counter = $('#coupon_counter').val();
            $('#coupon_counter').val(parseInt(counter)-1);

            var combination = $('#coupon_combination'+id).val();
            if(combination == 0){
                $('#solo_coupon_counter').val(0);
            }

            $('#appliedCouponDiv'+id).remove();
        });
    //
    </script>
@endsection