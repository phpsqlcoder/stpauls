@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <style>
        .sub1 { display: none; }

        :checked ~ .sub1 {
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
                                <span class="title">Billing Information</span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab-2">
                                <span class="step">2</span>
                                <span class="title">Shipping Options</span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab-3">
                                <span class="step">3</span>
                                <span class="title">Review and Place Order</span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab-4">
                                <span class="step">4</span>
                                <span class="title">Payment Method</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Billing Info -->                    
                    <div id="tab-1">
                        <div class="checkout-content">
                            <div class="checkout-card">
                                <div class="form-group form-wrap">
                                    <p>Name *</p>
                                    <input type="text" class="form-control" name="customer" value="{{ $customer->fullname }}">
                                    @hasError(['inputName' => 'customer'])
                                    @endhasError
                                </div>

                                <div class="gap-10"></div>
                                <div class="form-group form-wrap">
                                    <p>Email *</p>
                                    <input type="email" class="form-control" name="email" value="{{ $customer->email }}">
                                    @hasError(['inputName' => 'email'])
                                    @endhasError
                                </div>

                                <div class="gap-10"></div>
                                <div class="form-group form-wrap">
                                    <p>Mobile Number *</p>
                                    <input type="text" class="form-control" name="mobile" id="input-mobile" value="{{ $customer->mobile }}">
                                    @hasError(['inputName' => 'mobile'])
                                    @endhasError
                                </div>

                                <div class="gap-10"></div>
                                <div class="form-group form-wrap">
                                    <p>Province *</p>
                                    <select name="province" id="province" class="form-control">
                                        <option value="" selected disabled>-- Select Province --</option>
                                        @foreach($provinces as $province)
                                        <option @if($customer->province == $province->province) selected @endif value="{{$province->province}}">{{ $province->province_detail->province }}</option>
                                        @endforeach
                                        <option value="0">Others</option>
                                    </select>
                                    @hasError(['inputName' => 'province'])
                                    @endhasError

                                    @if(\App\Deliverablecities::check_area($customer->city) <> 1)
                                    <small id="alert_province" class="form-text text-danger"><b>{{ $customer->provinces->province }}</b> is not serviceable. Please select another province.</small>
                                    @endif
                                </div>

                                <div class="gap-10"></div>
                                <div class="form-group form-wrap">
                                    <p>City/Municipality *</p>
                                    <select class="form-control" name="city" id="city">
                                        @if(\App\Deliverablecities::check_area($customer->city) <> 1)
                                            <option value="" selected disabled>-- Select City --</option>
                                        @else
                                            @foreach($cities as $city)
                                            <option @if($customer->city == $city->city) selected @endif value="{{$city->city}}|{{$city->rate}}">{{ $city->city_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @hasError(['inputName' => 'city'])
                                    @endhasError

                                    @if(\App\Deliverablecities::check_area($customer->city) <> 1)
                                    <small id="alert_city" class="form-text text-danger"><b>{{ $customer->cities->city }}</b> is not serviceable. Please select another city.</small>
                                    @endif
                                </div>

                                <div class="gap-10"></div>
                                <div class="form-group form-wrap">
                                    <p>Address Line 1 *</p>
                                    <input type="text" class="form-control" name="address" id="input-address" value="{{ $customer->address }}">
                                    @hasError(['inputName' => 'address'])
                                    @endhasError
                                </div>

                                <div class="gap-10"></div>
                                <div class="form-group form-wrap">
                                    <p>Address Line 2 *</p>
                                    <input type="text" class="form-control" name="barangay" id="input-barangay" value="{{ $customer->barangay }}">
                                    @hasError(['inputName' => 'barangay'])
                                    @endhasError
                                </div>

                                <div class="gap-10"></div>
                                <div class="form-group form-wrap" id="div_other" style="display: none;">
                                    <p>Other Address</p>
                                    <textarea class="form-control" cols="2" id="other_address"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="checkout-nav">
                            <span></span>
                            <a class="checkout-next-btn" href="" id="billingNxtBtn">Next <span class="lnr lnr-chevron-right"></span></a>
                        </div>
                    </div>

                    <!-- Shipping Options -->
                    <div id="tab-2">
                        <div class="checkout-content">
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
                                @if($cod->is_active == 1)
                                    <input type="radio" id="tab1" name="shipOption" onclick="shippingOption('cod');" value="1" class="tab">
                                    <label for="tab1">Cash On Delivery (COD)</label>
                                @endif

                                @if($cod->is_active == 1)
                                    <input type="radio" id="tab2" name="shipOption" onclick="shippingOption('stp');" value="2" class="tab">
                                    <label for="tab2">Store Pick-up</label>
                                @endif

                                @if($sdd->is_active == 1)
                                    <input type="radio" id="tab3" name="shipOption" onclick="shippingOption('sdd');" value="4" class="tab">
                                    <label for="tab3">Same Day Delivery</label>
                                @endif

                                <input type="radio" id="tab4" name="shipOption" onclick="shippingOption('dtd');" value="3" class="tab">
                                <label for="tab4">Door-to-door (D2D)</label>

                                @if($cod->is_active == 1)
                                <div class="tab__content">
                                    <h3>Cash on Delivery</h3>
                                    <div class="alert alert-info" role="alert">
                                        <h4 class="alert-heading">Reminder!</h4>
                                        <p>{{ $cod->reminder }}</p>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <label>Date *</label>
                                            <input type="date" name="pickup_date_1" onchange="pickupDate(1)" id="pickup_date1" class="form-control" min="{{date('Y-m-d',strtotime(today()))}}">
                                        </div>
                                        <div class="col">
                                            <label>Time *</label>
                                            <input type="time" name="pickup_time_1" onchange="pickupTime(1)" id="pickup_time1" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- store pick-up -->
                                @if($stp->is_active == 1)
                                <div class="tab__content">
                                    <h3>Store Pick-up</h3>
                                    <div class="alert alert-info" role="alert">
                                        <h4 class="alert-heading">Reminder!</h4>
                                        <p>{{ $stp->reminder }}</p>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <label>Select Branch*</label>
                                            <select class="form-control" name="branch">
                                                <option selected disabled value="">-- Select Branch --</option>
                                                @foreach($branches as $branch)
                                                <option value="{{$branch->id}}">{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="gap-10"></div>
                                    <div class="form-row">
                                        <div class="col">
                                            <label>Date *</label>
                                            <input type="date" name="pickup_date_2" onchange="pickupDate(2)" id="pickup_date2" class="form-control" min="{{date('Y-m-d',strtotime(today()))}}">
                                        </div>
                                        <div class="col">
                                            <label>Time *</label>
                                            <input type="time" name="pickup_time_2" onchange="pickupTime(2)" id="pickup_time2" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- same day delivery -->
                                @if($sdd->is_active == 1)
                                <div class="tab__content">
                                    <h3>Same Day Delivery</h3>
                                    <div class="alert alert-info" role="alert">
                                        <h4 class="alert-heading">Reminder!</h4>
                                        <p>{{ $cod->reminder }}</p>
                                    </div>
                                </div>
                                @endif

                                <!-- Door-to-door -->
                                @if($dtd->is_active == 1)
                                <div class="tab__content">
                                    <h3>Door-to-door</h3>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="checkout-nav">
                            <a class="checkout-back-btn" href=""><span class="lnr lnr-chevron-left"></span> Back</a>
                            <a class="checkout-next-btn" id="shipOptionNxtBtn" href="">Next <span class="lnr lnr-chevron-right"></span></a>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div id="tab-3">
                            <div class="checkout-content">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="subtitle">Billed To</label>
                                        <h3 class="customer-name">{{ $customer->fullname }}</h3>
                                        <p class="customer-address">Delivert Type: <span id="spanshipMethod"></span></p>
                                        <p class="customer-address"><span id="customer-address"></span></p>
                                        <p class="customer-phone" >Tel No: <span id="customer-phone"></span></p>
                                        <p class="customer-email" id="customer-email">Email: {{ $customer->email }}</p>
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

                                                $onsaleChecker = \App\EcommerceModel\Product::onsale_checker($product->product_id);
                                                
                                                $totalAmount = $onsaleChecker > 0 ?  $product->product->discountedprice*$product->qty : $product->product->price*$product->qty;
                                                $subTotal  += $totalAmount;
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
                                                <td class="text-right">₱ 
                                                    {{ $onsaleChecker > 0 ?  $product->product->discountedprice :  number_format($product->product->price,2) }}
                                                    <input type="hidden" id="product_price_{{$product->product_id}}" value="{{ $onsaleChecker > 0 ?  $product->product->discountedprice :  $product->product->price }}">
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
                                        <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Notes</label>
                                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                        </p>
                                    </div>
                                    <!-- col -->
                                    <div class="col-sm-12 col-lg-4 order-1 order-sm-0">
                                        <div class="gap-30"></div>
                                        <input type="hidden" id="is_serviceable" value="{{App\Deliverablecities::check_area($customer->city)}}">
                                        @if(\App\Deliverablecities::check_area($customer->city) == 1)
                                        <input type="hidden" id="city_rate" value="{{ $customer->delivery_rate->rate }}">
                                        @endif
                                        <input type="hidden" id="subTotal" value="{{ $subTotal }}">
                                        <input type="hidden" id="min_purchase" value="{{ $cod->minimum_purchase }}">
                                        <input type="hidden" id="delivery_rate" value="{{ $cod->delivery_rate }}">
                                        <input type="hidden" id="service_fee" value="{{ $sdd->service_fee }}">
                                        <input type="hidden" id="min_order_allowed" value="{{$settings->min_order_is_allowed}}">
                                        <input type="hidden" id="min_order" value="{{$settings->min_order}}">

                                        <input type="hidden" id="selected_servicefee_val">
                                        <input type="hidden" id="selected_deliveryfee_val">

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
                                                    <input type="hidden" id="input_servicefee" name="servicefee">
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

                    <!-- Payment Method -->
                    <div id="tab-4">
                        <div class="checkout-content">
                            <h3>Payment Method</h3>
                            @foreach($payment_method as $method)
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" name="payment_method" value="{{ $method->id }}" id="method{{ $method->id }}" name="groupOfDefaultRadios">
                                <label class="custom-control-label" for="method{{ $method->id }}">{{ $method->name }}</label>
                                <div class="sub1">
                                    @foreach($method->paymentList as $list)
                                    <div>
                                        <input type="radio" name="payment_option" value="{{$list->name}}" id="paylist{{$list->id}}"/>
                                        <label for="paylist{{$list->id}}">{{ $list->name }}</label>
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
    </script>
@endsection

@section('customjs')
    <script>
        
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

        
        $(document).ready(function() {
            $('select[name="province"]').on('change', function() {

                $('#alert_province').hide();
                $('#alert_city').hide();

                var provinceID = $(this).val();
                if(provinceID == 0){
                    $('#city').prop('disabled', true);
                    $('#div_other').show();
                } else {

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
                            $.each(data, function(key, value) {
                                $('select[name="city"]').append('<option value="'+value.id+'">'+value.city+'</option>');
                            });
                        }
                    });
                }
            });
        });

        // uncheck selected shipping option if selected city change
        $('select[name="city"]').on('change', function() {
            $("input:radio[name='shipOption']").each(function(i) {
                this.checked = false;
            });
        });

        $('#btnPlaceOrder').click(function(){
            $("#checkout-form").submit();
        });

        $('#btnReviewOrder').click(function(){
            var shipOption  = $("input:radio[name='shipOption']:checked").val();

            if(shipOption == 1){ 
                $("#checkout-form").submit();
            } else {

            }
        });

        $('#billingNxtBtn').click(function(){

            $('#customer-address').html($('#input-address').val()+' '+$('#input-barangay').val()+', '+$("#province option:selected" ).text()+' '+$("#city option:selected" ).text());

            $('#customer-phone').html($('#input-mobile').val());
    
        });

        function shippingOption(type){
            var servicefee = $('#service_fee').val();
            var isServiceable = $('#is_serviceable').val();

            var subTotal = $('#subTotal').val();
            var minPurchase = $('#min_purchase').val();
            var deliveryRate = $('#delivery_rate').val();

            if(type == 'stp' || type == 'dtd' || type == 'sdd'){
                $('#btnReviewOrder').addClass('checkout-next-btn');

            }

            if(type == 'sdd'){
                $('#selected_servicefee_val').val(servicefee);

            } else {
                $('#selected_servicefee_val').val(0);
            }

            if(type == 'cod'){
                $('#btnReviewOrder').removeClass('checkout-next-btn');

                var sel_address = $('#province').val();
                if(sel_address == 0){
                    $('#selected_deliveryfee_val').val(0);
                } else {
                    var city = $('#city').val();
                    var rate = city.split('|');

                    if(parseFloat(subTotal) >= parseFloat(minPurchase)){
                        $('#selected_deliveryfee_val').val(deliveryRate);
                    } else {
                        $('#selected_deliveryfee_val').val(rate[1]);
                    }
                }
            }

            if(type == 'dtd'){
                var sel_address = $('#province').val();
                if(sel_address == 0){
                    $('#selected_deliveryfee_val').val(0);
                } else {
                    var city = $('#city').val();
                    var rate = city.split('|');

                    if(parseFloat(subTotal) >= parseFloat(minPurchase)){
                        $('#selected_deliveryfee_val').val(deliveryRate);
                    } else {
                        $('#selected_deliveryfee_val').val(rate[1]);
                    }
                }
            }
        }

        $('#shipOptionNxtBtn').click(function(){
            var shipOption  = $("input:radio[name='shipOption']:checked").val();
            var deliveryfee = $('#selected_deliveryfee_val').val();
            var servicefee  = $('#selected_servicefee_val').val(); 

            if(shipOption == 1){
                $('#spanshipMethod').html('Cash on Delivery');
            }
            if(shipOption == 2){
                $('#spanshipMethod').html('Store Pickup');
            }
            if(shipOption == 3){
                $('#spanshipMethod').html('Door 2 Door Delivery');
                $('#span_deliveryfee').html(FormatAmount(deliveryfee,2));
                $('#input_servicefee').val(0);
                delivery_fee(shipOption);
            }
            if(shipOption == 4){
                $('#spanshipMethod').html('Same Day Delivery');
            }

            if(shipOption == 2){
                $('#input_servicefee').val(0);
                $('#span_servicefee').html('0.00');

                $('#input_deliveryfee').val(0);
                $('#span_deliveryfee').html('0.00');

                $('#spanReviewOrder').html('Next');


            } else {
                // cash on delivery
                if(shipOption == 1){
                    delivery_fee(shipOption);

                    $('#spanReviewOrder').html('Place Order');

                    $('#input_servicefee').val(0);
                    $('#span_servicefee').html('0.00');
                }

                // same day delivery
                if(shipOption == 4){
                    $('#input_servicefee').val(servicefee);
                    $('#span_servicefee').html(FormatAmount(servicefee,2));

                    $('#input_deliveryfee').val(0);
                    $('#span_deliveryfee').html('0.00');

                    $('#spanReviewOrder').html('Next');
                }
            }

            totalDue();
        });


        function updateAmount(id){
            var qty   = $('#product_qty_'+id).val();
            var price = $('#product_price_'+id).val();

            var totalAmount = parseFloat(price)*parseFloat(qty);

            $('#product_total_amount_'+id).html(FormatAmount(totalAmount,2));
            $('#input_product_total_amount_'+id).val(totalAmount.toFixed(2));

            subTotal();
        } 

        function subTotal(){
            var shipOption  = $("input:radio[name='shipOption']:checked").val();
            var totalAmount = 0;

            $(".input_product_total_amount").each(function() {
                if(!isNaN(this.value) && this.value.length!=0) {
                    totalAmount += parseFloat(this.value);
                }
            });
            
            $('#sub-total').html(FormatAmount(totalAmount,2));
            $('#input_sub_total').val(totalAmount.toFixed(2));

            delivery_fee(shipOption);

        }

        function delivery_fee(shipOption){
            var deliveryfee = $('#selected_deliveryfee_val').val();
            var subtotal    = $('#input_sub_total').val();
            var min_order_allowed = $('#min_order_allowed').val();
            var min_order = $('#min_order').val();

            if(shipOption == 1 || shipOption == 3){
                if(min_order_allowed == 1){
                    if(parseFloat(subtotal) >= parseFloat(min_order)){
                        $('#input_deliveryfee').val(0);
                        $('#span_deliveryfee').html('0.00');
                    } else {
                        $('#input_deliveryfee').val(deliveryfee);
                        $('#span_deliveryfee').html(FormatAmount(deliveryfee,2));
                    }
                } else {
                    $('#input_deliveryfee').val(deliveryfee);
                    $('#span_deliveryfee').html(FormatAmount(deliveryfee,2));
                }
            } else {
                $('#input_deliveryfee').val(0);
                $('#span_deliveryfee').html('0.00');
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


        function FormatAmount(number, numberOfDigits) {

            var amount = parseFloat(number).toFixed(numberOfDigits);
            var num_parts = amount.toString().split(".");
            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            return num_parts.join(".");
        }

        $(document).ready(function() {
            $('select[name="province"]').on('change', function() {
                var provinceID = $(this).val();
                if(provinceID) {

                    var url = "{{ route('ajax.get-deliverable-cities', ':provinceID') }}";
                    url = url.replace(':provinceID',provinceID);

                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                            $('select[name="city"]').empty();
                            $('select[name="city"]').append('<option value="" selected disabled>-- Select City --</option>');
                            $.each(data, function(key, value) {
                                $('select[name="city"]').append('<option value="'+value.city+'|'+value.rate+'">'+value.city_name+'</option>');
                            });
                        }
                    });
                } else {
                    $('select[name="city"]').empty();
                }
            });
        });

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