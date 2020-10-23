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
                                    <div class="col-lg-6 mb-xs-4">
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
                                                    <option @if($customer->details->country == $country->id) selected @endif value="{{$country->id}}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                                <p id="alert_countryrate" style="display: none;" class="text-danger"><small>The country selected has no shipping rate in the system. Please expect an updated invoice once the order is confirmed.</small></p>
                                                <p id="p_country" class="text-danger" style="display: none;"><small>The country field is required.</small></p>
                                            </div>

                                            <div id="divIntlAddress" style="display: @if($customer->details->country <> 259 && $customer->details->country != "") block; @else none; @endif">
                                                <div class="gap-10"></div>
                                                <div class="form-group form-wrap">
                                                    <p>Billing Address *</p>
                                                    <textarea name="billing_address" class="form-control form-input" rows="3" id="billing_address">{{ $customer->details->intl_address }}</textarea>
                                                    <p id="p_otheradd" class="text-danger" style="display: none;"><small>The address field is required.</small></p>
                                                </div>
                                            </div>

                                            <div id="divLocalAddress" style="display: @if($customer->details->country == 259) block; @else none; @endif">
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
                                                    <p id="alert_cityrate" style="display: none;" class="text-danger"><small>The city selected has no shipping rate in the system. Please expect an updated invoice once the order is confirmed.</small></p>
                                                    <p id="p_city" class="text-danger" style="display: none;"><small>The city field is required.</small></p>
                                                </div>

                                                <div class="gap-10"></div>
                                                <div class="form-group form-wrap">
                                                    <p>Address Line 1 *</p>
                                                    <input required type="text" class="form-control form-input" name="address" id="input_address" value="{{ $customer->details->address }}">
                                                    <p id="p_address" class="text-danger" style="display: none;"><small>The address line 1 field is required.</small></p>
                                                </div>

                                                <div class="gap-10"></div>
                                                <div class="form-group form-wrap">
                                                    <p>Address Line 2 *</p>
                                                    <input required type="text" class="form-control form-input" name="barangay" id="input_barangay" value="{{ $customer->details->barangay }}">
                                                    <p id="p_barangay" class="text-danger" style="display: none;"><small>The address line 2 field is required.</small></p>
                                                </div>
                                            </div>

                                            <div class="gap-10"></div>
                                            <div class="form-group form-wrap">
                                                <p>Zip Code *</p>
                                                <input required type="text" class="form-control form-input" name="zipcode" id="input_zipcode" value="{{ $customer->details->zipcode }}">
                                                <p id="p_zipcode" class="text-danger" style="display: none;"><small>The zip code field is required.</small></p>
                                            </div>

                                            <div class="gap-10"></div>
                                            <div class="form-group form-wrap">
                                                <p>Other Instruction</p>
                                                <textarea name="other_instruction" class="form-control form-input" rows="3" id="other_instruction"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="checkout-card">
                                            <p class="mb-3">Select a shipping method:</p>
                                            @php
                                                $stp_allowed_days = "";

                                                $stp_days = explode('|',$stp->allowed_days);

                                                foreach($stp_days as $day){
                                                    $stp_allowed_days .= date('N',strtotime($day)).',';
                                                }
                                            @endphp
                                            <!-- Store Pick Up -->
                                            <input type="hidden" id="time_from" value="{{ $stp->allowed_time_from }}">
                                            <input type="hidden" id="time_to" value="{{ $stp->allowed_time_to }}">
                                            <input type="hidden" id="array_days" value="{{ rtrim($stp_allowed_days,',') }}">
                                            
                                            <div class="tab-wrap vertical">
                                                @if($customer->details->country == '')
                                                    <input type="radio" id="tab1" name="shipOption" value="1" class="tab">
                                                    <label id="cod_label" for="tab1">Cash On Delivery (COD) <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                    <div class="tab__content">
                                                        <h3>Cash on Delivery</h3>
                                                        <div class="alert alert-info" role="alert">
                                                            <h4 class="alert-heading">Reminder!</h4>
                                                            <p>{{ $cod->reminder }}</p>
                                                        </div>
                                                    </div>
                                                @else
                                                    @if($customer->details->country == 259)
                                                        @if($cod->outside_metro_manila == 1)
                                                            @if($amount <= $cod->maximum_purchase)
                                                            <input type="radio" id="tab1" name="shipOption" value="1" class="tab">
                                                            <label id="cod_label" for="tab1">Cash On Delivery (COD) <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                            <div class="tab__content">
                                                                <h3>Cash on Delivery</h3>
                                                                <div class="alert alert-info" role="alert">
                                                                    <h4 class="alert-heading">Reminder!</h4>
                                                                    <p>{{ $cod->reminder }}</p>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        @else
                                                            @if($customer->details->province == 49)
                                                            <input type="radio" id="tab1" name="shipOption" value="1" class="tab">
                                                            <label id="cod_label" for="tab1">Cash On Delivery (COD) <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                            <div class="tab__content">
                                                                <h3>Cash on Delivery</h3>
                                                                <div class="alert alert-info" role="alert">
                                                                    <h4 class="alert-heading">Reminder!</h4>
                                                                    <p>{{ $cod->reminder }}</p>
                                                                </div>
                                                            </div>
                                                            @else
                                                            <input type="radio" id="tab1" name="shipOption" value="1" class="tab">
                                                            <label style="display: none;" id="cod_label" for="tab1">Cash On Delivery (COD) <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                            <div class="tab__content">
                                                                <h3>Cash on Delivery</h3>
                                                                <div class="alert alert-info" role="alert">
                                                                    <h4 class="alert-heading">Reminder!</h4>
                                                                    <p>{{ $cod->reminder }}</p>
                                                                </div>
                                                            </div>
                                                            @endif
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
                                                            <p>{{ $stp->reminder }}</p>
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
                                                            <div class="col-lg-6 mb-sm-2">
                                                                <label>Date *</label>
                                                                <input type="date" name="pickup_date" onchange="pickupDate()" id="pickup_date" class="form-control form-input"
                                                                    min="{{date('Y-m-d',strtotime(today()))}}">
                                                                <p id="stp_date" class="text-danger" style="display: none;"><small>The date field is required.</small></p>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <label>Time *</label>
                                                                <input type="time" name="pickup_time" onchange="pickupTime()" id="pickup_time" class="form-control form-input">
                                                                <p id="stp_time" class="text-danger" style="display: none;"><small>The date field is required.</small></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                @endif
                                                
                                                @if($customer->details->country == 259 || $customer->details->country == '')
                                                    @if($amount <= $sdd->maximum_purchase)
                                                    <input type="radio" id="tab3" name="shipOption" value="4" class="tab">
                                                    <label id="sdd_label" for="tab3">Same Day Delivery <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                    <div class="tab__content">
                                                        <div class="alert alert-info" role="alert">
                                                            <h4 class="alert-heading">Reminder!</h4>
                                                            <p>{{ $sdd->reminder }}</p>
                                                        </div>
                                                    </div>
                                                    @endif
                                                @endif

                                                <input type="radio" id="tab4" name="shipOption" value="3" class="tab">
                                                <label for="tab4">Door-to-door (D2D) <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                <div class="tab__content">
                                                    <h3>Door-to-door</h3>
                                                </div>
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
                                                    <span>Shipping Fee</span>
                                                    <span>
                                                        <input type="text" id="shipping_fee" value="{{\App\ShippingfeeLocations::shipping_fee($customer->details->city,$customer->details->country,number_format(($weight/1000),2,'.','')) }}">
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
                                    </div>
                                </div>
                            </div>
                            <div class="checkout-nav">
                                <span></span>
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
                                                <th></th>
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
                                                    <input type="hidden" name="productid[]" value="{{ $product->product_id }}">
                                                    <div class="quantity">
                                                        <input type="number" name="qty[]" min="1" step="1" value="{{ $product->qty }}" data-inc="1" id="product_qty_{{$product->product_id}}" onchange="updateAmount('{{$product->product_id}}')">
                                                        <div class="quantity-nav">
                                                            <div class="quantity-button quantity-up" id="{{$product->product_id}}">+</div>
                                                            <div class="quantity-button quantity-down" id="{{$product->product_id}}">-</div>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" id="prevqty{{$product->product_id}}" value="{{ $product->qty }}">
                                                    <input type="hidden" id="maxorder{{$product->product_id}}" value="{{ $product->product->Maxpurchase }}">
                                                </td>
                                                <td class="text-right">₱ {{ number_format($product->price,2) }}
                                                    <input type="hidden" name="product_price[]" id="product_price_{{$product->product_id}}" value="{{ $product->price }}">
                                                </td>
                                                <td class="text-right">
                                                    <input type="hidden" class="input_product_total_amount" id="input_product_total_amount_{{$product->product_id}}" value="{{$totalAmount}}">
                                                    ₱ <span id="product_total_amount_{{$product->product_id}}">{{ number_format($totalAmount,2) }}</span>
                                                </td>
                                                <td><a href="" onclick="deleteProduct('{{$product->id}}');">x</a></td>
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
                                                <span class="text-danger">LESS: Loyalty Discount ({{number_format($loyalty_discount,0)}}%)</span>
                                                <span>
                                                    <input type="hidden" id="input_loyalty_discount" name="loyaltydiscount" value="{{$loyalty_discount}}">
                                                    <input type="hidden" id="input_discount_amount" name="discount_amount">
                                                    <span class="text-danger" id="span_discount"> </span>
                                                </span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span>ADD: Shipping Fee</span>
                                                <span>
                                                    <input type="hidden" id="input_shippingfee" name="shippingfee">
                                                    ₱ <span id="span_shippingfee">0.00</span>
                                                </span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span>ADD: Service Fee</span>
                                                <span>
                                                    <input name="servicefee" type="hidden" id="input_servicefee" name="servicefee">
                                                    ₱ <span id="span_servicefee">0.00</span>
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
                                    <input type="radio" name="payment_method" value="{{ $method->id }}" id="method{{$method->id}}"/>
                                    <label for="method{{$method->id}}">{{ $method->name }}</label>
                                    <div class="sub1">
                                        @foreach($method->paymentList as $list)
                                        <div>
                                            <input type="radio" name="payment_option" value="{{$list->name}}" id="paylist{{$list->id}}"/>
                                            <label for="paylist{{$list->id}}">{{ $list->name }}</label>
                                            <div class="sub2">
                                                <div>
                                                    @if($list->type == 'bank')
                                                    <label>Account Name : {{ $list->account_name }}</label><br>
                                                    @endif
                                                    <label>Account # : {{ $list->account_no }}</label><br>
                                                    @if($list->type == 'remittance')
                                                    <label>Recipient : {{ $list->recipient }}</label><br>
                                                    @if($list->qrcode != '')
                                                    <label>QR Code : </label><br>
                                                    <img style="width: 100%;max-width: 150px;" src="{{ asset('storage/qrcodes/'.$list->id.'/'.$list->qrcode) }}">
                                                    @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>   
                                @endforeach    
                            </div>
                            <div class="checkout-nav">
                                <a class="checkout-back-btn" href=""><span class="lnr lnr-chevron-left"></span> Back</a>
                                <a class="checkout-finish-btn" href="" id="btnPlaceOrder">Place Order <span class="lnr lnr-chevron-right"></span></a>
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
        $('.quantity-up').click(function(){
            var id = $(this).attr("id");
            if(id){
                var qty = $('#product_qty_'+id).val();
                var maxorder = $('#maxorder'+id).val();


                if(maxorder == 0){
                    swal({
                        title: '',
                        text: "Sorry. Currently, there is no sufficient stocks for the item you wish to order.",         
                    });

                    $('#product_qty_'+id).val(qty-1);
                } else {
                    var stock = maxorder-1;
                    $('#prevqty'+id).val(qty);
                    $('#maxorder'+id).val(stock);
                }  
            }
        });

        $('.quantity-down').click(function(){
            var id = $(this).attr("id");
            if(id){
                var qty = $('#product_qty_'+id).val();
                var prevqty = $('#prevqty'+id).val();

                var maxorder = $('#maxorder'+id).val();
                var stock = parseFloat(maxorder)+1;


                if(prevqty == 1){

                } else {
                    $('#prevqty'+id).val(prevqty-1);
                    $('#maxorder'+id).val(stock);
                }   
            }
        });

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
                var city    = 0; 
                
                if(country != ""){

                    if(country == 259){
                        $('#cod_label').css('display','block');
                        $('#stp_label').css('display','block');
                        $('#sdd_label').css('display','block');

                        $('#divLocalAddress').css('display','block');
                        $('#divIntlAddress').css('display','none');

                        $('#shipping_fee').val(0);

                    } else {
                        $('#cod_label').css('display','none');
                        $('#stp_label').css('display','none');
                        $('#sdd_label').css('display','none');

                        $('#divLocalAddress').css('display','none');
                        $('#divIntlAddress').css('display','block');

                        $.ajax({
                            type: "GET",
                            url: "{{ route('ajax.get-city-rates') }}",
                            data: {
                                'city' : city,
                                'country' : country,
                                'weight' : weight
                            },
                            success: function(response) {
                                $('#alert_cityrate').css('display','none');
                                if(response.rate == 0){
                                    $('#alert_countryrate').css('display','block');
                                } else {
                                    $('#alert_countryrate').css('display','none');
                                }

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

            $('select[name="city"]').on('change', function() {
                var city  = $(this).val();
                var weight  = $('#total_weight').val();
                var country = $('#country').val(); 

                $.ajax({
                    type: "GET",
                    url: "{{ route('ajax.get-city-rates') }}",
                    data: {
                        'city' : city,
                        'country' : country,
                        'weight' : weight
                    },
                    success: function(response) {
                        $('#alert_countryrate').css('display','none');
                        if(response.rate == 0){
                            $('#alert_cityrate').css('display','block');
                        } else {
                            $('#alert_cityrate').css('display','none');
                        }

                        $('#shipping_fee').val(response.rate);
                    }
                });
            });


            $('select[name="province"]').on('change', function() {
                var provinceID = $(this).val();
                if(provinceID) {

                    if(provinceID == 49 && $('#within_metro_manila').val() == 1){
                        $('#cod_label').css('display','block');
                    } else {
                        if($('#outside_metro_manila').val() == 1){
                            $('#cod_label').css('display','block');
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
                    $('select[name="city"]').empty();
                }
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

        function pickupTime(){
            var inputtime = $('#pickup_time').val()+":00";
            var time_from = $('#time_from').val()+":00";
            var time_to   = $('#time_to').val()+":00";

            var fr_time = time_from;
            var a = fr_time.split(':');
            // minutes are worth 60 seconds. Hours are worth 60 minutes.
            var fr_time = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);

            var to_time = time_to;
            var b = to_time.split(':');
            // minutes are worth 60 seconds. Hours are worth 60 minutes.
            var to_time = (+b[0]) * 60 * 60 + (+b[1]) * 60 + (+b[2]);

            var sl_time = inputtime;
            var c = sl_time.split(':');
            // minutes are worth 60 seconds. Hours are worth 60 minutes.
            var sl_time = (+c[0]) * 60 * 60 + (+c[1]) * 60 + (+c[2]);

            if(sl_time >= fr_time && sl_time <= to_time ){
                
            } else {
                this.value = '';
                swal({
                    title: '',
                    text: "Sorry! We are not available on that time.",         
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
                barangay = $('#input_barangay').val(),
                zipcode  = $('#input_zipcode').val(),
                province = $('#province').val(),
                city     = $('#city').val(),
                country  = $('#country').val(),
                intl_add = $('#billing_address').val();


                if(country == 259){
                    // Philippines
                    if(fname.length === 0 || lname.length === 0 || email.length === 0 || IsEmail(email) == false || mobile.length === 0 || province === "" || city === "" || address.length === 0 || barangay.length === 0 || zipcode.length === 0){
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
                    if(barangay.length === 0){ $('#p_barangay').show(); } else { $('#p_barangay').hide(); }
                    if(address.length === 0){ $('#p_address').show(); } else { $('#p_address').hide(); }
                    if(zipcode.length === 0){ $('#p_zipcode').show(); } else { $('#p_zipcode').hide(); }
                } else {
                    if(intl_add.length === 0){ $('#p_otheradd').show(); } else { $('#p_otheradd').hide(); }
                }
                
            /* END BILLING VALIDATION */

            /* BEGIN SHIPPING VALIDATION */
                var option = $('input[name="shipOption"]:checked').val();

                if(option == 1){
                    $('#btnReviewOrder').removeClass('checkout-next-btn');
                    $('#spanReviewOrder').html('Place Order');
                } else {
                    $('#btnReviewOrder').addClass('checkout-next-btn');
                    $('#spanReviewOrder').html('Next');
                }

                if(option == 2){
                    var stp_branch = $('#selbranch').val(), stp_date = $('#pickup_date').val(), stp_time = $('#pickup_time').val();

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

                    if($('#pickup_time').val() == ''){ 
                        $('#stp_time').show(); 
                    } else { 
                        $('#stp_time').hide(); 
                    }

                    if(stp_branch == 0 || stp_date.length === 0 || stp_time.length === 0){
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

            /* BEGIN ORDER REVIEW INITIALIZE */
                if($('#country').val() == 259){
                    $('#customer-address').html($('#input_address').val()+' '+$('#input_barangay').val()+', '+$("#province option:selected" ).text()+' '+$("#city option:selected" ).text()+', '+$('#input_zipcode').val()+' '+$("#country option:selected" ).text());
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
            /* END ORDER REVIEW INITIALIZE */
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

            var codServiceFee    = $('#cod_service_fee').val();
            var shippingfee   = $('#shipping_fee').val();

            if(option == 1 || option == 3 || option == 4){

                if(option == 1){
                    $('#input_servicefee').val(codServiceFee);
                    $('#span_servicefee').html(FormatAmount(codServiceFee,2));
                } else {
                    $('#input_servicefee').val(0);
                    $('#span_servicefee').html('0.00');
                }

                $('#input_shippingfee').val(shippingfee);
                $('#span_shippingfee').html(FormatAmount(shippingfee,2));

            } else {
                $('#input_servicefee').val(0);
                $('#span_servicefee').html('0.00');

                $('#input_shippingfee').val(0);
                $('#span_shippingfee').html('0.00');
            }

        }

        function updateAmount(id){
            var qty   = $('#product_qty_'+id).val();
            var price = $('#product_price_'+id).val();
            var weight= $('#product_weight_'+id).val()

            var totalAmount = parseFloat(price)*parseFloat(qty);
            var totalWeight = parseFloat(weight)*parseFloat(qty);

            $('#product_total_amount_'+id).html(FormatAmount(totalAmount,2));
            $('#input_product_total_amount_'+id).val(totalAmount.toFixed(2));

            $('#total_product_weight_'+id).val(totalWeight);

            subTotal();
        }

        function subTotal(){
            var totalAmount = 0;
            var totalWeight = 0;

            $(".input_product_total_amount").each(function() {
                if(!isNaN(this.value) && this.value.length!=0) {
                    totalAmount += parseFloat(this.value);
                }
            });

            $(".input_product_total_weight").each(function() {
                if(!isNaN(this.value) && this.value.length!=0) {
                    totalWeight += parseFloat(this.value);
                }
            });


            $('#total-weight').html(FormatAmount((totalWeight/1000),2));
            $('#input_total_weight').val((totalWeight/1000).toFixed(2));

            $('#sub-total').html(FormatAmount(totalAmount,2));
            $('#input_sub_total').val(totalAmount.toFixed(2));

            total_weight();
        }

        function total_weight(){
            var weight = $('#input_total_weight').val();
            var country= $('#country').val();
            var city   = $('#city').val(); 

            $.ajax({
                type: "GET",
                url: "{{ route('ajax.get-city-rates') }}",
                data: {
                    'city' : city,
                    'country' : country,
                    'weight' : weight
                },
                success: function(response) {
                    $('#input_shippingfee').val(response.rate);
                    $('#span_shippingfee').html(FormatAmount(response.rate,2));

                    totalDue();
                }
            });

            
        }

        function totalDue(){
            var subtotal    = $('#input_sub_total').val();
            var shippingfee = $('#input_shippingfee').val();
            var servicefee  = $('#input_servicefee').val();
            var discount    = $('#input_loyalty_discount').val();


            var disc = (discount / 100).toFixed(2); //its convert 10 into 0.10
            var discounted_amount = subtotal * disc; // gives the value for subtract from main value

            var grandTotal = (parseFloat(subtotal)+parseFloat(shippingfee)+parseFloat(servicefee))-parseFloat(discounted_amount);

            $('#input_discount_amount').val(discounted_amount);
            $('#span_discount').html('₱ '+FormatAmount(discounted_amount,2));
            $('#input_total_due').val(grandTotal);
            $('#totalDue').html(FormatAmount(grandTotal,2));
        }


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

        $('#btnReviewOrder').click(function(){
            var option  = $("input:radio[name='shipOption']:checked").val();

            if(option == 1){ 
                $("#checkout-form").submit();
            } else {

            }
        });


        function FormatAmount(number, numberOfDigits) {

            var amount = parseFloat(number).toFixed(numberOfDigits);
            var num_parts = amount.toString().split(".");
            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            return num_parts.join(".");
        }

        function deleteProduct(cartid)
        {
            var totalproduct = $('#total_product').val();
            if(totalproduct == 1){
                swal({
                    title: '',
                    text: "You are not allowed to remove all the product(s).",         
                })
            } else {
                swal({
                    title: 'Are you sure?',
                    text: "This will remove the item from your cart.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove it!'            
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type: "GET",
                            url: "{{ route('checkout.remove-product') }}",
                            data: { 
                                    cartid : cartid,
                                },
                            success: function( response ) {
                                swal("Success!", "Product has been removed.", "success");
                                $('#cart_'+cartid).remove();
                                $('#total_product').val(parseFloat(totalproduct)-1);
                                subTotal();
                                
                            },
                            error: function( response ){
                                swal("Error!", "Failed to remove the product.", "danger"); 
                            }
                        });  
                    } 
                    else {                    
                        swal.close();                   
                    }
                });
            } 
        }
    </script>
@endsection