@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('content')
<main>
    <section id="checkout-wrapper">
        <div class="container">
            <h2 class="checkout-title">Checkout</h2>
            <form method="post" action="{{ route('cart.temp_sales') }}" id="checkout-form">
                @csrf
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
                                <table class="customer-info">
                                    <tr>
                                        <td>Name</td>
                                        <td>{{ $customer->fullname }}</td>
                                    </tr>
                                    <tr>
                                        <td>E-mail Address</td>
                                        <td>{{ $customer->email }}</td>
                                    </tr>
                                    <tr>
                                        <td>Contact Number</td>
                                        <td>{{ $customer->mobile }}</td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td>
                                            {{ $customer->address1 }}<br>{{ $customer->address2 }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Zip Code</td>
                                        <td>{{ $customer->zipcode }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="checkout-nav">
                                <span></span>
                                <a class="checkout-next-btn" href="">Next <span class="lnr lnr-chevron-right"></span></a>
                            </div>
                        </div>

                        <!-- Shipping Options -->
                        <div id="tab-2">
                            <div class="checkout-content">
                                <p class="mb-3">Select a shipping method:</p>

                                <div class="tab-wrap">
                                    @if($cod->is_active == 1)
                                        @if(\App\EcommerceModel\CheckoutOption::check_availability(1) == 1)
                                            <input type="radio" id="tab1" name="shipOption" onclick="shippingOption('cod');" value="1" class="tab">
                                            <label for="tab1">Cash On Delivery (COD)</label>
                                        @endif
                                    @endif

                                    @if($cod->is_active == 1)
                                        @if(\App\EcommerceModel\CheckoutOption::check_availability(2) == 1)
                                            <input type="radio" id="tab2" name="shipOption" onclick="shippingOption('stp');" value="2" class="tab">
                                            <label for="tab2">Store Pick-up</label>
                                        @endif
                                    @endif

                                    @if($sdd->is_active == 1)
                                        @if(\App\EcommerceModel\CheckoutOption::check_availability(4) == 1)
                                        <input type="radio" id="tab3" name="shipOption" onclick="shippingOption('sdd');" value="4" class="tab">
                                        <label for="tab3">Same Day Delivery</label>
                                        @endif
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
                                        <div class="checkout-card">
                                            <div class="unit flex-row unit-spacing-s">
                                                <div class="unit__left">
                                                    <span class="fa fa-check-circle fa-icon"></span>
                                                </div>
                                                <div class="unit__body">
                                                    <h3 class="customer-name">{{ $customer->fullname }}</h3>
                                                    <p class="customer-address">{{ $customer->address1 }}<br> {{ $customer->address2withzip }}</p>
                                                    @if(\App\Deliverablecities::check_area($customer->city) <> 1)
                                                    <small class="form-text text-danger">This area is not serviceable. Please enter new address.</small>

                                                    <div class="gap-20"></div>
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Address 1*</label>
                                                            <textarea required name="add1" class="form-control" rows="2"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputPassword1">Address 2*</label>
                                                            <textarea required name="add2" class="form-control" rows="2"></textarea>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="col">
                                                                <label for="exampleInputEmail1">Province*</label>
                                                                <select class="form-control" name="province" id="province">
                                                                    <option selected disabled value="">-- Select Province --</option>
                                                                    @foreach($provinces as $province)
                                                                    <option value="{{$province->province}}">{{ $province->province_detail->province }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col">
                                                                <label for="exampleInputEmail1">City *</label>
                                                                <select name="city" id="city" class="form-control">
                                                                </select>  
                                                                <small class="form-text text-muted">Serviceable cities.</small>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
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
                                        <div class="checkout-card">
                                            <div class="unit flex-row unit-spacing-s">
                                                <div class="unit__left">
                                                    <span class="fa fa-check-circle fa-icon"></span>
                                                </div>
                                                <div class="unit__body">
                                                    <h3 class="customer-name">{{ $customer->fullname }}</h3>
                                                    <p class="customer-address">{{ $customer->address1 }}<br> {{ $customer->address2withzip }}</p>
                                                    @if(\App\Deliverablecities::check_area($customer->city) <> 1)
                                                    <small class="form-text text-danger">This area is not serviceable. Please enter new address.</small>
                                                    <textarea class="form-control" cols="4" name="d2d-new-address" id="dtd-new-address"></textarea>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
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
                                        <p class="customer-address">{{ $customer->address1 }}, {{ $customer->address2 }} {{ $customer->zipcode }}</p>
                                        <p class="customer-phone">Tel No: {{ $customer->mobile }}</p>
                                        <p class="customer-email">Email: {{ $customer->email }}</p>
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
                                            <tr>
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
                                                    ₱ <span id="product_total_amount_{{$product->product_id}}">{{ number_format($totalAmount,2) }}</span></td>
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
                                <a class="checkout-next-btn" href="">Next <span class="lnr lnr-chevron-right"></span></a>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div id="tab-4">
                            <div class="checkout-content">
                                <h3>Payment Method</h3>
                                <!-- Group of default radios - option 1 -->
                                <div class="custom-control custom-radio">
                                  <input type="radio" class="custom-control-input" id="defaultGroupExample1" name="groupOfDefaultRadios">
                                  <label class="custom-control-label" for="defaultGroupExample1">Credit Card</label>
                                </div>

                                <!-- Group of default radios - option 2 -->
                                <div class="custom-control custom-radio">
                                  <input type="radio" class="custom-control-input" id="defaultGroupExample2" name="groupOfDefaultRadios">
                                  <label class="custom-control-label" for="defaultGroupExample2">Online Fund</label>
                                </div>

                                <!-- Group of default radios - option 3 -->
                                <div class="custom-control custom-radio">
                                  <input type="radio" class="custom-control-input" id="defaultGroupExample3" name="groupOfDefaultRadios">
                                  <label class="custom-control-label" for="defaultGroupExample3">Money Transfer</label>
                                </div>
                                <div class="gap-20"></div>
                            </div>
                            <div class="checkout-nav">
                                <a class="checkout-back-btn" href=""><span class="lnr lnr-chevron-left"></span> Back</a>
                                <a class="checkout-finish-btn" href="javascript:;" id="btnPlaceOrder">Place Order <span class="lnr lnr-chevron-right"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</main>
@endsection

@section('pagejs')
    <script src="{{ asset('theme/stpaul/plugins/responsive-tabs/js/jquery.responsiveTabs.js') }}"></script>
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
        $('#btnPlaceOrder').click(function(){
            $("#checkout-form").submit();
        });

        function shippingOption(type){
            var servicefee = $('#service_fee').val();
            var isServiceable = $('#is_serviceable').val();

            var subTotal = $('#subTotal').val();
            var minPurchase = $('#min_purchase').val();
            var deliveryRate = $('#delivery_rate').val();

            $('#shipOptionNxtBtn').addClass("checkout-next-btn");

            if(type == 'sdd'){
                $('#selected_servicefee_val').val(servicefee);
            } else {
                $('#selected_servicefee_val').val(0);
            }

            if(type == 'cod'){
                if(isServiceable == 1){
                    var cityRate = $('#city_rate').val();
                    if(parseFloat(subTotal) >= parseFloat(minPurchase)){
                        $('#selected_deliveryfee_val').val(deliveryRate);
                    } else {
                        $('#selected_deliveryfee_val').val(cityRate);
                    }
                }
            }
        }

        $('#shipOptionNxtBtn').click(function(){
            if ($('input[name=shipOption]:checked').length > 0) {
                var shipOption  = $("input:radio[name='shipOption']:checked").val();
                var deliveryfee = $('#selected_deliveryfee_val').val();
                var servicefee  = $('#selected_servicefee_val').val(); 

                if(shipOption == 2 || shipOption == 3){
                    $('#input_servicefee').val(0);
                    $('#span_servicefee').html('0.00');

                    $('#input_deliveryfee').val(0);
                    $('#span_deliveryfee').html('0.00');
                } else {
                    // cash on delivery
                    if(shipOption == 1){
                        delivery_fee(shipOption);

                        $('#input_servicefee').val(0);
                        $('#span_servicefee').html('0.00');
                    }

                    // same day delivery
                    if(shipOption == 4){
                        $('#input_servicefee').val(servicefee);
                        $('#span_servicefee').html(FormatAmount(servicefee,2));

                        $('#input_deliveryfee').val(0);
                        $('#span_deliveryfee').html('0.00');
                    }
                }

                totalDue();
                
            } else {
                $(this).removeClass("checkout-next-btn");
                alert('Please select shipping option.');
            }
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

            if(shipOption == 1){
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
    </script>
@endsection