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
    <form method="post" action="{{ route('cart.temp_sales') }}" id="checkout-form">
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
                                    <span class="title">Billing Information and Shipping Options</span>
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
                                    <div class="col-lg-6 mb-sm-4">
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
                                                <input type="text" class="form-control form-input" name="mobile" id="input_mobile" value="{{ $customer->mobile }}" maxlength="13">
                                                <p id="p_mobile" class="text-danger" style="display: none;"><small>The mobile field is required.</small></p>
                                            </div>

                                            <div class="gap-10"></div>
                                            <div class="form-group form-wrap">
                                                <p>Province *</p>
                                                <select name="province" id="province" class="form-control form-input">
                                                    <option value="" selected disabled>-- Select Province --</option>
                                                    @foreach($provinces as $province)
                                                    <option @if($customer->province == $province->province) selected @endif value="{{$province->province}}">{{ $province->province_detail->province }}</option>
                                                    @endforeach
                                                    <option value="0">Others</option>
                                                </select>
                                                <p id="p_province" class="text-danger" style="display: none;"><small>The province field is required.</small></p>
                                                @if(\App\Deliverablecities::deliverable_province($customer->province) < 1)
                                                    <small id="alert_province" class="form-text text-danger"><b>{{ $customer->provinces->province }}</b> is not serviceable. Please select another province.</small>
                                                @endif
                                            </div>
                                            <div id="divaddress">
                                                <div class="gap-10"></div>
                                                <div class="form-group form-wrap">
                                                    <p>City/Municipality *</p>
                                                    <select required class="form-control form-input" name="city" id="city">
                                                        @if(\App\Deliverablecities::check_area($customer->city) <> 1)
                                                            <option value="0" selected>-- Select City --</option>
                                                        @else
                                                            @foreach($cities as $city)
                                                            <option @if($customer->city == $city->city) selected @endif value="{{$city->city}}|{{$city->rate}}">{{ $city->city_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <span></span>
                                                    <p id="p_city" class="text-danger" style="display: none;"><small>The city field is required.</small></p>
                                                    @if(\App\Deliverablecities::check_area($customer->city) <> 1)
                                                        <small id="alert_city" class="form-text text-danger"><b>{{ $customer->cities->city }}</b> is not serviceable. Please select another city.</small>
                                                    @endif
                                                </div>

                                                <div class="gap-10"></div>
                                                <div class="form-group form-wrap">
                                                    <p>Address Line 1 *</p>
                                                    <input required type="text" class="form-control form-input" name="address" id="input_address" value="{{ $customer->address }}">
                                                    <p id="p_address" class="text-danger" style="display: none;"><small>The address line 1 field is required.</small></p>
                                                </div>

                                                <div class="gap-10"></div>
                                                <div class="form-group form-wrap">
                                                    <p>Address Line 2 *</p>
                                                    <input required type="text" class="form-control form-input" name="barangay" id="input_barangay" value="{{ $customer->barangay }}">
                                                    <p id="p_barangay" class="text-danger" style="display: none;"><small>The address line 2 field is required.</small></p>
                                                </div>

                                                <div class="gap-10"></div>
                                                <div class="form-group form-wrap">
                                                    <p>Zip Code *</p>
                                                    <input required type="text" class="form-control form-input" name="zipcode" id="input_zipcode" value="{{ $customer->zipcode }}">
                                                    <p id="p_zipcode" class="text-danger" style="display: none;"><small>The zip code field is required.</small></p>
                                                </div>
                                            </div>

                                            <div class="gap-10"></div>
                                            <div class="form-group form-wrap" id="div_other" style="display: none;">
                                                <p>Other Address</p>
                                                <textarea name="other_address" class="form-control form-input" rows="3" id="other_address"></textarea>
                                                <p id="p_other" class="text-danger" style="display: none;"><small>The other address field is required.</small></p>
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
                                                $cod_allowed_days = "";

                                                $stp_days = explode('|',$stp->allowed_days);
                                                $cod_days = explode('|',$cod->allowed_days);

                                                foreach($stp_days as $day){
                                                    $stp_allowed_days .= date('N',strtotime($day)).',';
                                                }

                                                foreach($cod_days as $day){
                                                    $cod_allowed_days .= date('N',strtotime($day)).',';
                                                }
                                            @endphp
                                            <!-- Store Pick Up -->
                                            <input type="hidden" id="time_from2" value="{{ $stp->allowed_time_from }}">
                                            <input type="hidden" id="time_to2" value="{{ $stp->allowed_time_to }}">
                                            <input type="hidden" id="array_days2" value="{{ rtrim($stp_allowed_days,',') }}">

                                            <!-- Cash on Delivery -->
                                            <input type="hidden" id="time_from1" value="{{ $cod->allowed_time_from }}">
                                            <input type="hidden" id="time_to1" value="{{ $cod->allowed_time_to }}">
                                            <input type="hidden" id="array_days1" value="{{ rtrim($cod_allowed_days,',') }}">
                                            
                                            <div class="tab-wrap">
                                                <input type="radio" id="tab1" name="shipOption" value="1" class="tab">
                                                <label for="tab1">Cash On Delivery (COD) <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                <div class="tab__content">
                                                    <h3>Cash on Delivery</h3>
                                                    <div class="alert alert-info" role="alert">
                                                        <h4 class="alert-heading">Reminder!</h4>
                                                    </div>
                                                    <div class="form-row form-style fs-sm">
                                                        <div class="col-lg-6 mb-sm-2">
                                                            <label>Date *</label>
                                                            <input type="date" name="pickup_date_1" onchange="pickupDate(1)" id="pickup_date1" class="form-control form-input"
                                                                min="{{date('Y-m-d',strtotime(today()))}}">
                                                            <p id="cod_date" class="text-danger" style="display: none;"><small>The date field is required.</small></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label>Time *</label>
                                                            <input type="time" name="pickup_time_1" onchange="pickupTime(1)" id="pickup_time1" class="form-control form-input">
                                                            <p id="cod_time" class="text-danger" style="display: none;"><small>The time field is required.</small></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                                <input type="radio" id="tab2" name="shipOption" value="2" class="tab">
                                                <label for="tab2">Store Pick-up <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                <div class="tab__content">
                                                    <h3>Store Pick-up</h3>
                                                    <div class="alert alert-info" role="alert">
                                                        <h4 class="alert-heading">Reminder!</h4>
                                                    </div>
                                                    <div class="form-row form-style fs-sm">
                                                        <div class="col">
                                                            <label>Select Branch*</label>
                                                            <select class="form-control form-input" name="branch" id="selbranch">
                                                                <option selected value="0">-- Select Branch --</option>
                                                                @foreach($branches as $branch)
                                                                <option value="{{$branch->id}}">{{ $branch->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <p id="stp_branch" class="text-danger" style="display: none;"><small>The branch field is required.</small></p>
                                                        </div>
                                                    </div>
                                                    <div class="gap-10"></div>
                                                    <div class="form-row form-style fs-sm">
                                                        <div class="col-lg-6 mb-sm-2">
                                                            <label>Date *</label>
                                                            <input type="date" name="pickup_date_2" onchange="pickupDate(2)" id="pickup_date2" class="form-control form-input"
                                                                min="{{date('Y-m-d',strtotime(today()))}}">
                                                            <p id="stp_date" class="text-danger" style="display: none;"><small>The date field is required.</small></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label>Time *</label>
                                                            <input type="time" name="pickup_time_2" onchange="pickupTime(2)" id="pickup_time2" class="form-control form-input">
                                                            <p id="stp_time" class="text-danger" style="display: none;"><small>The date field is required.</small></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                                <input type="radio" id="tab3" name="shipOption" value="4" class="tab">
                                                <label for="tab3">Same Day Delivery <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                <div class="tab__content">
                                                    <div class="alert alert-info" role="alert">
                                                        <h4 class="alert-heading">Reminder!</h4>
                                                    </div>
                                                </div>
                                            
                                                <input type="radio" id="tab4" name="shipOption" value="3" class="tab">
                                                <label for="tab4">Door-to-door (D2D) <span class="fa fa-check-circle fa-icon ml-2"></span></label>
                                                <div class="tab__content">
                                                    <h3>Door-to-door</h3>
                                                </div>
                                            </div>

                                            <ul class="list-unstyled lh-7 pd-r-10" style="display: none;">
                                                <li class="d-flex justify-content-between">
                                                    <span>Is Serviceable</span>
                                                    <span>
                                                        <input type="text" id="is_serviceable" value="{{App\Deliverablecities::check_area($customer->city)}}">
                                                    </span>
                                                </li>
                                                @if(\App\Deliverablecities::check_area($customer->city) == 1)
                                                <li class="d-flex justify-content-between">
                                                    <span>City Rate</span>
                                                    <span>
                                                        <input type="text" id="city_rate" value="{{ $customer->delivery_rate->rate }}">
                                                    </span>
                                                </li>
                                                @endif

                                                <li class="d-flex justify-content-between">
                                                    <span>COD Min Order Allowed</span>
                                                    <span>
                                                        <input type="text" id="min_order_allowed" value="{{$settings->min_order_is_allowed}}">
                                                    </span>
                                                </li>
                                                <li class="d-flex justify-content-between">
                                                    <span>COD Minimum Purchase</span>
                                                    <span>
                                                        <input type="text" id="cod_min_purchase" value="{{ $cod->minimum_purchase }}">
                                                    </span>
                                                </li>
                                                <li class="d-flex justify-content-between">
                                                    <span>COD Delivery Rate</span>
                                                    <span>
                                                        <input type="text" id="delivery_rate" value="{{ $cod->delivery_rate }}">
                                                    </span>
                                                </li>
                                                <li class="d-flex justify-content-between">
                                                    <span>SDD Service Fee</span>
                                                    <span>
                                                        <input type="text" id="service_fee" value="{{ $sdd->service_fee }}">
                                                    </span>
                                                </li>
                                                <li class="d-flex justify-content-between">
                                                    <span>SDD Minimum Purchase</span>
                                                    <span>
                                                        <input type="text" id="sdd_min_purchase" value="{{ $sdd->minimum_purchase }}">
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
                                        <p class="customer-phone" >Tel No: <span id="customer-phone"></span></p>
                                        <p class="customer-email" id="customer-email">Email: <span id="customer-email"></span></p>
                                    </div>
                                </div>

                                <div class="gap-40"></div>

                                <div class="table-responsive mg-t-40">
                                    <table class="table table-invoice bd-b order-table">
                                        <thead>
                                            <tr>
                                                <th class="w-25">Type</th>
                                                <th class="w-30 d-none d-sm-table-cell">Description</th>
                                                <th class="w-15 text-center">Qty</th>
                                                <th class="w-15 text-right">Unit Price</th>
                                                <th class="w-15 text-right">Amount</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $totalAmount = 0; $subTotal = 0; @endphp
                                            @foreach($products as $product)
                                            @php
                                                $totalAmount = $product->price*$product->qty;
                                                $subTotal   += $totalAmount;
                                            @endphp
                                            <tr id="cart_{{$product->id}}">
                                                <td class="tx-nowrap text-danger">{{ $product->product->name }}</td>
                                                <td class="d-none d-sm-table-cell tx-color-03">{{ str_limit(strip_tags($product->product->description), 80, $end ='...') }}</td>
                                                <td class="text-center">
                                                    <div class="quantity">
                                                        <input type="hidden" name="productid[]" value="{{ $product->product_id }}">
                                                        <input type="number" name="qty[]" min="1" max="25" step="1" value="{{ $product->qty }}" data-inc="1" id="product_qty_{{$product->product_id}}" onchange="updateAmount('{{$product->product_id}}')">
                                                        <div class="quantity-nav">
                                                            <div class="quantity-button quantity-up">+</div>
                                                            <div class="quantity-button quantity-down">-</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">₱ {{ number_format($product->price,2) }}
                                                    <input type="hidden" name="product_price[]" id="product_price_{{$product->product_id}}" value="{{ number_format($product->price,2) }}">
                                                </td>
                                                <td class="text-right">
                                                    <input type="hidden" class="input_product_total_amount" id="input_product_total_amount_{{$product->product_id}}" value="{{$totalAmount}}">
                                                    ₱ <span id="product_total_amount_{{$product->product_id}}">{{ number_format($totalAmount,2) }}</span>
                                                </td>
                                                <td><a href="" onclick="deleteProduct('{{$product->id}}');">x</a></td>
                                            </tr>
                                            @endforeach
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
                                                <span>Sub-Total</span>
                                                <span>
                                                    <input type="hidden" id="input_sub_total" value="{{$subTotal}}" name="subtotal">
                                                    ₱ <span id="sub-total">{{ number_format($subTotal,2) }}</span>
                                                </span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span>Delivery Fee</span>
                                                <span>
                                                    <input type="hidden" id="input_deliveryfee" name="deliveryfee">
                                                    ₱ <span id="span_deliveryfee">0.00</span>
                                                </span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span>Service Fee</span>
                                                <span>
                                                    <input name="servicefee" type="hidden" id="input_servicefee" name="servicefee">
                                                    ₱ <span id="span_servicefee">0.00</span>
                                                </span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span>Loyalty Discount</span>
                                                <span>
                                                    <input type="hidden" id="input_loyalty_discount" name="loyaltydiscount" value="{{$loyalty_discount}}">
                                                    ₱ <span id="servicefee">{{ number_format($loyalty_discount,2) }}</span>
                                                </span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <strong>Total Due</strong>
                                                <strong>
                                                    <input type="hidden" name="totalDue" id="input_total_due" name="totalDue">
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
                                <div>
                                    <input type="radio" name="payment_method" value="{{ $method->id }}" id="method{{$method->id}}"/>
                                    <label for="method{{$method->id}}">{{ $method->name }}</label>
                                    <div class="sub1">
                                        @foreach($method->paymentList as $list)
                                        <div>
                                            <input type="radio" name="payment_option" value="{{$list->name}}" id="paylist{{$list->id}}"/>
                                            <label for="paylist{{$list->id}}">{{ $list->name }}</label>
                                            <div class="sub2">
                                                <div>
                                                    <label for="D10">Account # : {{ $list->account_no }}</label>
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
        $(document).ready(function(){
            //called when key is pressed in textbox
            $("#input_mobile").keypress(function (e) {
                //if the letter is not digit then display error and don't type anything
                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });

            /** Custom Input number increment js **/
            jQuery(".quantity").each(function() {
                var spinner = jQuery(this),
                    input = spinner.find('input[type="number"]'),
                    btnUp = spinner.find(".quantity-up"),
                    btnDown = spinner.find(".quantity-down"),
                    min = input.attr("min"),
                    max = input.attr("max"),
                    valOfAmout = input.val(),
                    newVal = 0;

                btnUp.on("click", function() {
                    var varholder = input.val();
                    var oldValue = parseFloat(input.val());

                    if (varholder === "") {
                        var newVal = 1;
                    } else {
                        if (oldValue >= max) {
                            var newVal = oldValue;
                        } else {
                            var newVal = oldValue + 1;
                        }
                    }
                    spinner.find("input").val(newVal);
                    spinner.find("input").trigger("change");
                });

                btnDown.on("click", function() {
                    var varholder = input.val();
                    var oldValue = parseFloat(input.val());

                    if (varholder === "") {
                        var newVal = 1;
                    } else {
                        if (oldValue <= min) {
                            var newVal = oldValue;
                        } else {
                            var newVal = oldValue - 1;
                        }
                    }
                    spinner.find("input").val(newVal);
                    spinner.find("input").trigger("change");
                });
            });
        });

        $(document).ready(function() {
            $('select[name="province"]').on('change', function() {

                $('#alert_province').hide();
                $('#alert_city').hide();

                var provinceID = $(this).val();

                if(provinceID == 0){
                    $('#divaddress').hide();

                    $('#p_city').hide();
                    $('select[name="city"]').empty();
                    $('select[name="city"]').append('<option value="0" selected>-- Select City --</option>');
                    $('#city').prop('disabled', true);
                    $('#div_other').show();
                } else {
                    $('#divaddress').show();

                    $('#serviceable').val(1);
                    $('#city').prop('disabled', false);
                    $('#div_other').hide();

                    var url = "{{ route('ajax.deliverable-cities', ':provinceID') }}";
                    url = url.replace(':provinceID',provinceID);

                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                            $('select[name="city"]').empty();
                            $('select[name="city"]').append('<option value="0" selected>-- Select City --</option>');
                            $.each(data, function(key, value) {
                                $('select[name="city"]').append("<option value='"+value.city+"|"+value.rate+"'>"+value.city_name+"</option>");
                            });
                        }
                    });
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

        function pickupDate(id){
            var allowed_days = [];

            var days = $('#array_days'+id).val();
            var inputdate = $('#pickup_date'+id).val();
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

            console.log(allowed_days);
            console.log(d);


            if(allowed_days.includes(""+d+"")){
  
            } else {
                $('#pickup_date'+id).val('');

                swal({
                    title: '',
                    text: "Sorry! We are not available on that date.",         
                })
            }
        }

        function pickupTime(id){
            var inputtime = $('#pickup_time'+id).val()+":00";
            var time_from = $('#time_from'+id).val()+":00";
            var time_to   = $('#time_to'+id).val()+":00";

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
                var name  = $('#input_fname').val()+' '+$('#input_lname').val(), 
                    fname = $('#input_fname').val(),
                    lname = $('#input_lname').val(),
                    email  = $('#input_email').val(), 
                    mobile = $('#input_mobile').val(), 
                    address = $('#input_address').val(), 
                    barangay = $('#input_barangay').val(),
                    city = $('#city').val(),
                    zipcode = $('#input_zipcode').val(),
                    others = $('#other_address').val();

                if($('#province').val() == 0){
                    if(others.length === 0 || fname.length === 0 || lname.length === 0 || email.length === 0 || IsEmail(email) == false || mobile.length === 0){ 
                        $(this).removeClass('checkout-next-btn');
                    } else { 
                        if (!$("input[name='shipOption']:checked").val()) {
                           alert('Please select a shipping method!');
                           $(this).removeClass('checkout-next-btn');
                        }
                        else {
                            $(this).addClass('checkout-next-btn');
                        }
                    }
                } else {
                    if(fname.length === 0 || lname.length === 0 || email.length === 0 || IsEmail(email) == false || mobile.length === 0 || address.length === 0 || barangay.length === 0 || city == 0 || zipcode.length === 0){
                        $(this).removeClass('checkout-next-btn');
                    } else {
                        if (!$("input[name='shipOption']:checked").val()) {
                           alert('Please select a shipping method!');
                           $(this).removeClass('checkout-next-btn');
                        }
                        else {
                            $(this).addClass('checkout-next-btn');
                        }
                    }
                }

                if(fname.length === 0){
                    $('#p_fname').show(); 
                } else { 
                    $('#p_fname').hide(); 
                }

                if(lname.length === 0){
                    $('#p_lname').show(); 
                } else { 
                    $('#p_lname').hide(); 
                }

                if(zipcode.length === 0){
                    $('#p_zipcode').show(); 
                } else { 
                    $('#p_zipcode').hide(); 
                }

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

                if(mobile.length === 0){ 
                    $('#p_mobile').show(); 
                } else { 
                    $('#p_mobile').hide(); 
                }

                if($('#province').val() == 0){
                    $('#p_other').show();
                    if(others.length === 0){
                        $('#p_other').show();
                    } else {
                        $('#p_other').hide();
                    }
                } else {
                    $('#p_other').hide();
                    if(barangay.length === 0){ 
                        $('#p_barangay').show(); 
                    } else { 
                        $('#p_barangay').hide(); 
                    }

                    if(address.length === 0){ 
                        $('#p_address').show(); 
                    } else { 
                        $('#p_address').hide(); 
                    }
                }
                
                // if(is_serviceable == 0){ 
                //     $('#alert_province').show(); 
                // }
                // if(is_serviceable == 1 && city == 0){ 
                //     $('#p_city').show(); 
                // } else { 
                //     $('#p_city').hide(); 
                // }
            /* END BILLING VALIDATION */

            /* BEGIN SHIPPING VALIDATION */
                var option = $('input[name="shipOption"]:checked').val();

                if(option == 1){
                    var cod_date = $('#pickup_date1').val(), cod_time = $('#pickup_time1').val();

                    if(cod_date.length === 0){ 
                        $('#cod_date').show(); 
                    } else { 
                        $('#cod_date').hide(); 
                    }

                    if(cod_time.length === 0){ 
                        $('#cod_time').show(); 
                    } else { 
                        $('#cod_time').hide(); 
                    }

                    if(cod_date.length === 0 || cod_time === 0){
                        $(this).removeClass('checkout-next-btn');
                    } else {
                        $(this).addClass('checkout-next-btn'); 
                    }

                    $('#btnReviewOrder').removeClass('checkout-next-btn');
                    $('#spanReviewOrder').html('Place Order');
                } else {
                    $('#btnReviewOrder').addClass('checkout-next-btn');
                    $('#spanReviewOrder').html('Next');
                }

                if(option == 2){
                    var stp_branch = $('#selbranch').val(), stp_date = $('#pickup_date2').val(), stp_time = $('#pickup_time2').val();

                    if($('#selbranch').val() == 0){ 
                        $('#stp_branch').show(); 
                    } else { 
                        $('#stp_branch').hide(); 
                    }

                    if($('#pickup_date2').val() == ''){ 
                        $('#stp_date').show(); 
                    } else { 
                        $('#stp_date').hide(); 
                    }

                    if($('#pickup_time2').val() == ''){ 
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
                if($('#province').val() == 0){
                    $('#customer-address').html($('#other_address').val());
                } else {
                    $('#customer-address').html($('#input_address').val()+' '+$('#input_barangay').val()+', '+$("#province option:selected" ).text()+' '+$("#city option:selected" ).text()+', '+$('#input_zipcode').val());
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

        function shippingFee(option){
            var isServiceable = $('#is_serviceable').val();

            var subTotal = $('#input_sub_total').val();
            var codMinPurchase = $('#cod_min_purchase').val();
            var deliveryRate = $('#delivery_rate').val();

            if(option == 1){
                $('#input_servicefee').val(0);
                $('#span_servicefee').html('0.00');

                if($('#province').val() == 0){
                    $('#input_deliveryfee').val(0);
                    $('#span_deliveryfee').html('0.00');
                } else {
                    var city = $('#city').val();
                    var rate = city.split('|');

                    if(parseFloat(subTotal) >= parseFloat(codMinPurchase)){
                        $('#input_deliveryfee').val(deliveryRate);
                        $('#span_deliveryfee').html(FormatAmount(deliveryRate,2));
                    } else {
                        $('#input_deliveryfee').val(rate[1]);
                        $('#span_deliveryfee').html(FormatAmount(rate[1],2));
                    }
                }
            }

            if(option == 2){
                $('#input_servicefee').val(0);
                $('#span_servicefee').html('0.00');

                $('#input_deliveryfee').val(0);
                $('#span_deliveryfee').html('0.00');
            }

            var sddMinPurchase = $('#sdd_min_purchase').val();
            var servicefee = $('#service_fee').val();

            if(option == 4){
                $('#input_deliveryfee').val(0);
                $('#span_deliveryfee').html('0.00');

                if(parseFloat(subTotal) >= parseFloat(sddMinPurchase)){
                    $('#input_servicefee').val(0);
                    $('#span_servicefee').html('0.00');
                } else {
                    $('#input_servicefee').val(servicefee);
                    $('#span_servicefee').html(FormatAmount(servicefee,2));
                }
            }

            if(option == 3){
                $('#input_servicefee').val(0);
                $('#span_servicefee').html('0.00');

                if($('#province').val() == 0){
                    $('#input_deliveryfee').val(0);
                     $('#span_deliveryfee').html('0.00');
                } else {
                    var city = $('#city').val();
                    var rate = city.split('|');

                    $('#input_deliveryfee').val(rate[1]);
                     $('#span_deliveryfee').html(FormatAmount(rate[1],2));
                }
            }
        }

        function updateAmount(id){
            var qty   = $('#product_qty_'+id).val();
            var price = $('#product_price_'+id).val();

            var totalAmount = parseFloat(price)*parseFloat(qty);

            $('#product_total_amount_'+id).html(FormatAmount(totalAmount,2));
            $('#input_product_total_amount_'+id).val(totalAmount.toFixed(2));

            subTotal();
        }

        function subTotal(){
            var totalAmount = 0;

            $(".input_product_total_amount").each(function() {
                if(!isNaN(this.value) && this.value.length!=0) {
                    totalAmount += parseFloat(this.value);
                }
            });
            
            $('#sub-total').html(FormatAmount(totalAmount,2));
            $('#input_sub_total').val(totalAmount.toFixed(2));

            update_fee(totalAmount);
        }

        function update_fee(subtotal){
            var option = $('input[name="shipOption"]:checked').val();

            if(option == 1){
                var codMinPurchase = $('#cod_min_purchase').val();
                var deliveryRate = $('#delivery_rate').val();

                $('#input_servicefee').val(0);
                $('#span_servicefee').html('0.00');

                var city = $('#city').val();
                var rate = city.split('|');

                if(parseFloat(subtotal) >= parseFloat(codMinPurchase)){
                    $('#input_deliveryfee').val(deliveryRate);
                    $('#span_deliveryfee').html(FormatAmount(deliveryRate,2));
                } else {
                    $('#input_deliveryfee').val(rate[1]);
                    $('#span_deliveryfee').html(FormatAmount(rate[1],2));
                }
            }

            if(option == 4){
                var sddMinPurchase = $('#sdd_min_purchase').val();
                var servicefee = $('#service_fee').val();

                $('#input_deliveryfee').val(0);
                $('#span_deliveryfee').html('0.00');

                if(parseFloat(subtotal) >= parseFloat(sddMinPurchase)){
                    $('#input_servicefee').val(0);
                    $('#span_servicefee').html('0.00');
                } else {
                    $('#input_servicefee').val(servicefee);
                    $('#span_servicefee').html(FormatAmount(servicefee,2));
                }
            }

            totalDue();
        }

        function totalDue(){
            var subtotal    = $('#input_sub_total').val();
            var deliveryfee = $('#input_deliveryfee').val();
            var servicefee  = $('#input_servicefee').val();
            var discount    = $('#input_loyalty_discount').val();

            var total = (parseFloat(subtotal)+parseFloat(deliveryfee)+parseFloat(servicefee)) - parseFloat(discount);

            $('#input_total_due').val(total);
            $('#totalDue').html(FormatAmount(total,2));
        }


        $('#btnPlaceOrder').click(function(){
            var option = $('input[name="payment_method"]:checked').val();

            if (!$("input[name='payment_method']:checked").val()) {
               alert('Please select a payment method!');
               $(this).removeClass('checkout-next-btn');
            }
            else {
                if(option == 1){
                    $("#checkout-form").submit();
                } else {
                    if (!$("input[name='payment_option']:checked").val()) {
                       alert('Please select a payment option!');
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
    </script>
@endsection