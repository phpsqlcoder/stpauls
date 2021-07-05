@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
@endsection

@section('content')

<main>
    <section id="cart-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <strong>Success!</strong> {{ $message }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif

                    @if($message = Session::get('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong>Error!</strong> {{ $message }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif
                </div>
            </div>
            <form id="checkoutForm" method="post" action="{{route('cart.front.proceed_checkout')}}">
                @csrf
                <div class="row">
                    <div class="col-lg-9">
                        <div class="cart-title">
                            <h2>My Cart</h2>
                        </div>
                        <ul class="cart-wrap">
                            @php 
                                $grandtotal = 0; $totalproducts = 0; $available_stock = 0; 

                                $cproducts  = '';
                                $totalCartProducts = 0;

                            @endphp

                            @forelse($cart as $key => $order)

                            @php 
                                $totalproducts += 1;
                                $grandtotal += $order->product->discountedprice*$order->qty;

                                if($order->product->inventory == 0){
                                    $available_stock++;
                                }


                                $cproducts .= $order->product_id.'|';
                                $totalCartProducts++;
                            @endphp

                            <li class="item">
                                <div class="remove-item">
                                    <a href="#" onclick="remove_item('@if(Auth::check()) {{$order->id}} @else {{$key}} @endif')" style="font-size: .7em;" class="text-uppercase txt-10">Remove <span class="lnr lnr-cross"></span></a>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-4">
                                        <div class="img-wrap">
                                            <a href="{{ route('product.front.show',$order->product->slug)}}"><img src="{{ asset('storage/products/'.$order->product->photoPrimary) }}" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <div class="info-wrap">
                                            <div class="cart-description">
                                                @if(Auth::check())
                                                    <input type="hidden" name="cart_id[]" value="{{ $order->id }}">
                                                @else
                                                    <input type="hidden" name="cart_id[]" value="{{$order->product_id}}">
                                                @endif
                                                
                                                <h3 class="cart-product-title"><a href="{{ route('product.front.show',$order->product->slug)}}">{{ $order->product->name }}</a></h3>
                                                <ol class="breadcrumb">
                                                    @php 
                                                        $arr = \App\EcommerceModel\ProductCategory::product_category($order->product->category_id);
                                                    @endphp

                                                    @foreach($arr as $key => $a)
                                                        <li class="breadcrumb-item active" aria-current="page">{{ $a->name }}</li>
                                                    @endforeach
                                                </ol>
                                            </div>
                                            <div class="cart-quantity">
                                                <label for="quantity">Quantity</label>
                                                <div class="quantity">
                                                    @if($order->qty > $order->product->inventory)
                                                    <input readonly type="number" name="qty[]" value="{{ $order->product->inventory }}" min="1" step="1" data-inc="1" id="order{{$loop->iteration}}_qty">
                                                    @else
                                                    <input readonly type="number" name="qty[]" value="{{ $order->qty }}" min="1" step="1" data-inc="1" id="order{{$loop->iteration}}_qty">
                                                    @endif

                                                    <div class="quantity-nav">
                                                        <div class="quantity-button quantity-up" id="{{$loop->iteration}}">+</div>
                                                        <div class="quantity-button quantity-down" id="{{$loop->iteration}}">-</div>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="orderID_{{$loop->iteration}}" value="{{$order->product_id}}">
                                                <input type="hidden" id="prevqty{{$loop->iteration}}" value="{{ $order->qty }}">
                                                <input type="hidden" id="maxorder{{$loop->iteration}}" value="{{ $order->product->inventory }}">
                                            </div>
                                            <div class="cart-info">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <table>
                                                            <tr>
                                                                <td><p @if($order->product->inventory == 0) class="text-danger" @endif><b>Available Stock</b></p></td>
                                                                <td><p @if($order->product->inventory == 0) class="text-danger" @endif><b>{{ $order->product->inventory }}</b></p></td>
                                                            </tr>
                                                            <tr>
                                                                <td><p><b>Quantity Added on Cart</b></p></td>
                                                                <td><p><b>{{ $order->qty }}</b></p></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <p>Weight (g)</p>
                                                                </td>
                                                                <td>
                                                                    <p>{{ number_format($order->product->weight,2) }}</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <p>Total Weight (g)</p>
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" id="input_order{{$loop->iteration}}_product_weight" value="{{$order->product->weight}}">
                                                                    <p id="order{{$loop->iteration}}_total_weight">{{ number_format($order->TotalWeight,2) }}</p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="cart-product-price">₱ 
                                                            <input type="hidden" name="product_price[]" id="input_order{{$loop->iteration}}_product_price" value="{{$order->product->discountedprice}}">
                                                            <input type="hidden" class="input_product_total_price" data-id="{{$loop->iteration}}" data-productid="{{$order->product_id}}" id="input_order{{$loop->iteration}}_product_total_price" value="{{$order->product->discountedprice*$order->qty}}">
                                                            <span id="order{{$loop->iteration}}_total_price">
                                                                {{ number_format($order->product->discountedprice*$order->qty,2) }}
                                                            </span>
                                                        </div>

                                                        <div class="cart-product-price couponDiscountSpan{{$loop->iteration}}" style="display: none;">
                                                            Coupon Discount : <span class="text-danger" id="product_coupon_discount{{$loop->iteration}}"></span>&nbsp;OFF
                                                        </div>
                                                
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <!-- coupon product parameters -->
                            <input type="hidden" id="iteration{{$order->product_id}}" value="{{$loop->iteration}}">
                            <input type="hidden" id="product_name_{{$loop->iteration}}" value="{{$order->product->name}}">

                            <input type="hidden" name="productid[]" id="pp{{$loop->iteration}}" value="{{$order->product_id}}">
                            <input type="hidden" name="productcatid[]" data-productid="{{$order->product_id}}" id="pp{{$loop->iteration}}" value="{{$order->product->category_id}}">
                            <input type="hidden" name="productbrand[]" data-productid="{{$order->product_id}}" id="pp{{$loop->iteration}}" value="{{$order->product->brand}}">

                            <input type="hidden" class="cart_product_reward" id="cart_product_reward{{$loop->iteration}}" value="0">
                            <input type="hidden" name="price{{$loop->iteration}}" id="price{{$loop->iteration}}" value="{{number_format($order->product->discountedprice,2,'.','')}}">

                            <input type="hidden" class="cart_product_discount" id="cart_product_discount{{$loop->iteration}}" value="0">

                            @empty
                                @php $totalproducts = 0; @endphp
                                <div class="gap-30"></div>
                                <div class="alert alert-primary" role="alert">
                                    Your shopping cart is <strong>empty</strong>.
                                </div>
                            @endforelse
                        </ul>
                    </div>

                    <div class="col-lg-3">
                        <div class="cart-title">
                            <h2>Coupon</h2>
                        </div>
                        <div class="summary-wrap">
                            <div class="promo-code">
                                <label for="promo">Enter promo code or <span class="white-spc">gift card</span></label>
                                <div class="input-group">
                                    <input type="text" class="promo-input form-control" name="promo" id="promo">
                                </div>
                                <div class="field_wrapper"></div>
                                
                                @if(Auth::check())
                                    <a class="mt-2 mb-2 text-primary" href="#" style="font-size:12px;" onclick="myCoupons()"> or click here to  Select from My Coupons</a>
                                @else
                                    <a class="mt-2 mb-2 text-primary" href="#" style="font-size:12px;" onclick="login_modal()"> or click here to  Select from My Coupons</a>
                                @endif
                                
                                <div class="mt-2">
                                    <button class="btn promo-btn" type="button">Apply</button>
                                </div>

                                <!-- coupon parameters/validators -->
                                <input type="hidden" id="cproducts" value="{{$cproducts}}">

                                <input type="hidden" id="total_amount_discount_counter" value="0">
                                <input type="hidden" id="coupon_limit" value="{{ Setting::info()->coupon_limit }}">
                                <input type="hidden" id="coupon_counter" name="coupon_counter" value="0">
                                <input type="hidden" id="solo_coupon_counter" value="0">
                                <!-- <input type="hidden" id="total_amount_discount_counter" value="0"> -->

                            </div>
                            
                            <div id="appliedCouponList"></div>
                        </div>



                        <div class="cart-title">
                            <h2>Summary</h2>
                            <!-- coupon discounts -->
                            <input type="hidden" id="coupon_total_discount" name="coupon_total_discount" value="0">
                            <input type="hidden" id="total_amount_discount" value="0">

                            <input type="hidden" name="grantotal" id="npt_grandTotal" value="{{ $grandtotal }}">
                            
                        </div>
                        <div class="summary-wrap">
                            <div class="subtotal">
                                <div class="table">
                                    <div class="table-row">
                                        <div class="table-cell">
                                            Subtotal
                                        </div>
                                        <div class="table-cell">
                                            ₱ <span id="subtotal">{{ number_format($grandtotal,2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="subtotal couponDiscountSummary" style="display:none;">
                                <div class="table">
                                    <div class="table-row">
                                        <div class="table-cell">
                                            Coupon Discount
                                        </div>
                                        <div class="table-cell">
                                            ₱ <span id="total_coupon_deduction"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="grand-total">
                                <div class="table">
                                    <div class="table-row">
                                        <div class="table-cell">
                                            Grand Total
                                        </div>
                                        <div class="table-cell">
                                            ₱ <span id="grandtotal">{{ number_format($grandtotal,2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(Auth::check())
                            <div class="shipping-message">
                                {{ \App\StPaulModel\LoyalCustomer::loyal_customer(auth()->user()->id) }}<br><br>
                                Shipping fees will apply upon checkout.
                            </div>
                            @endif
                            <div class="cart-btn">
                                <div class="row">
                                    <div class="col-12">
                                        @if(Auth::check())
                                        <input type="hidden" id="roleid" value="{{ auth()->user()->role_id }}">
                                        <input type="hidden" id="auth" value="1">
                                        @else
                                        <input type="hidden" id="roleid" value="3">
                                        <input type="hidden" id="auth" value="0">
                                        @endif
                                        <button @if($totalproducts > 0 && $available_stock == 0) @else disabled @endif type="button" id="btnCheckout" class="btn btn-lg tertiary-btn">Proceed to Checkout</button>

                                        @if($available_stock > 0)
                                        <p class="text-danger"><small>Kindly remove products with 0 available stock.</small></p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </form>
        </div>
    </section>
</main>

@include('theme.stpaul.ecommerce.cart.modals')

<div style="display: none;">
    <form id="remove_order_form" method="post" action="{{route('cart.remove_product')}}">
        @csrf
        <input type="text" name="order_id" id="order_id" value="">
    </form>
</div>
@endsection

@section('pagejs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
@endsection

@section('customjs')
    <script>
    // coupon scripts
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

        function login_modal(){
            $('#modalLoginLink').modal('show');
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


        function coupon_counter(cid){
            var limit           = $('#coupon_limit').val();
            var totalUsedCoupon = $('#coupon_counter').val();
            var solo_coupon_counter = $('#solo_coupon_counter').val();

            var combination = $('#couponcombination'+cid).val();

            var max_amount_coupon_discount = parseFloat(500);
            var couponTotalDiscount = parseFloat($('#coupon_total_discount').val());

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


    // use coupon on total amount   
        function use_coupon_total_amount(cid){

            var totalAmountDiscountCounter = $('#total_amount_discount_counter').val();
            var combination = $('#couponcombination'+cid).val();
            var name        = $('#couponname'+cid).val();
            var desc        = $('#coupondesc'+cid).val();
            var terms       = $('#couponterms'+cid).val();
            var validity    = $('#couponvalidity'+cid).val();
            

            if(coupon_counter(cid)){
                if(parseInt(totalAmountDiscountCounter) == 1){
                    swal({
                        title: '',
                        text: "Only one (1) coupon with discount amount/percentage per transaction.",         
                    });

                    var counter = $('#coupon_counter').val();
                    $('#coupon_counter').val(parseInt(counter)-1);

                    return false;
                }

                $('#total_amount_discount_counter').val(1);
                $('#couponBtn'+cid).prop('disabled',true);
                $('#btnCpnTxt'+cid).html('Applied');

                $('#appliedCouponList').append(
                    '<div class="subtotal" id="appliedCouponDiv'+cid+'">'+
                        '<div class="coupon-item">'+
                            // coupon inputs
                                '<input type="hidden" name="couponUsage[]" value="0">'+
                                '<input type="hidden" id="coupon_combination'+cid+'" value="'+combination+'">'+
                                '<input type="hidden" name="couponid[]" value="'+cid+'">'+
                                '<input type="hidden" name="coupon_productid[]" value="0">'+
                                '<input type="hidden" name="coupon_productdiscount[]" value="0">'+
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

                $('[data-toggle="popover"]').popover();

                var grandtotal = $('#npt_grandTotal').val();
                var amount= $('#discountamount'+cid).val();
                var percnt= $('#discountpercentage'+cid).val();

                if(amount > 0){ 
                    var amountdiscount = parseFloat(amount);
                }

                if(percnt > 0){
                    var percent  = parseFloat(percnt)/100;
                    var discount = parseFloat(grandtotal)*percent;

                    var amountdiscount = parseFloat(discount);
                }

                var coupon_discount = parseFloat($('#coupon_total_discount').val());

                var total_coupon_deduction = coupon_discount+amountdiscount;
                $('#coupon_total_discount').val(total_coupon_deduction);
                $('#total_coupon_deduction').html(addCommas(total_coupon_deduction.toFixed(2))); 
                $('.couponDiscountSummary').css('display','block');

                $('#total_amount_discount').val(amountdiscount);

                grandTotal();
            }
        }

        $(document).on('click', '.couponRemove', function(){  
            var id = $(this).attr("id"); 

            var coupon_total_discount = parseFloat($('#coupon_total_discount').val());
            var total_amount_discount = $('#total_amount_discount').val();
            
            var updated_coupon_discount = coupon_total_discount-total_amount_discount;
            $('#coupon_total_discount').val(updated_coupon_discount);
            $('#total_coupon_deduction').html('₱ '+ addCommas(updated_coupon_discount.toFixed(2))); 
            
            var counter = $('#coupon_counter').val();
            $('#coupon_counter').val(parseInt(counter)-1);

            var total_amount_counter = $('#total_amount_discount_counter').val();
            $('#total_amount_discount_counter').val(parseInt(total_amount_counter)-1);
            $('#total_amount_discount').val(0);

            var combination = $('#coupon_combination'+id).val();
            if(combination == 0){
                $('#solo_coupon_counter').val(0);
            }

            $('#appliedCouponDiv'+id).remove(); 
            grandTotal();
        });
    //

    // use coupon on product
        var arr_coupon_product = [];
        function use_coupon_on_product(cid){
            var amount = $('#discountamount'+cid).val();
            var percnt = $('#discountpercentage'+cid).val();

            var name  = $('#couponname'+cid).val();
            var desc  = $('#coupondesc'+cid).val();
            var terms = $('#couponterms'+cid).val();
            var pdiscount = $('#productdiscount'+cid).val();
            var discountproductid = parseFloat($('#discountproductid'+cid).val());
            var remaining = parseFloat($('#remainingusage'+cid).val());
            var purchaseqty = parseFloat($('#purchaseqty'+cid).val());

            var discount = 0;

            if(coupon_counter(cid)){
                if(pdiscount == 'specific'){
                    var iteration = $('#iteration'+discountproductid).val();
                    //var total_cart_reward = parseFloat($('#cart_product_reward'+iteration).val())

                    var pname       = $('#product_name_'+iteration).val();
                    var productid   = $('#pp'+iteration).val();
                    var combination = $('#couponcombination'+cid).val();
                    var validity    = $('#couponvalidity'+cid).val();

                    var sub_price   = $('#input_product_total_price'+iteration).val();

                    if(amount > 0){
                        var productSubTotalDiscount = parseFloat(sub_price)-parseFloat(amount);
                        var discount = parseFloat(amount);
                    }

                    if(percnt > 0){
                        var percent = parseFloat(percnt)/100;
                        var discount =  parseFloat(sub_price)*parseFloat(percent);

                        // var productSubTotalDiscount = parseFloat(sub_price)-parseFloat(discount);
                        var productSubTotalDiscount = parseFloat(discount);
                    }

                    arr_coupon_product.push(iteration);

                    $('#cart_product_reward'+iteration).val(1);
                }

                if(pdiscount == 'current'){

                    var categories = $('#couponcategories'+cid).val();
                    var products   = $('#couponproducts'+cid).val();
                    var brands     = $('#couponbrands'+cid).val();

                    if(products != ''){
                        var product_split = products.split('|');

                        var arr_purchase_products = [];
                        $.each(product_split, function(key, productID) {
                            if(productID != ''){
                                arr_purchase_products.push(parseInt(productID));    
                            }
                        });

                        var exist_counter = 0;
                        var arr_exist_product = [];
                        $("input[name='productid[]']").each(function() {
                            if(jQuery.inArray(parseInt($(this).val()), arr_purchase_products) !== -1){
                                arr_exist_product.push(parseInt($(this).val()));
                                exist_counter++
                            }
                        });

                        if(exist_counter > 0){
                            // get highest product purchase
                            var highest = -Infinity; 
                            var iteration;

                            $(".input_product_total_price").each(function() {
                                var id = $(this).data('id');
                                var productid = $(this).data('productid');

                                var x = $('#cart_product_reward'+id).val();
                                if(x == 0){
                                    if(jQuery.inArray(parseInt(productid), arr_exist_product) !== -1){
                                        highest = Math.max(highest, parseFloat(this.value));
                                    }
                                }
                            });
                                
                            $(".input_product_total_price").each(function() {
                                if(parseFloat(this.value) == parseFloat(highest.toFixed(2))){
                                    iteration = $(this).data('id');
                                }
                            });
                        }
                    }

                    if(categories != ''){
                        var category_split = categories.split('|');

                        var arr_purchase_categories = [];
                        $.each(category_split, function(key, brand) {
                            if(brand != ''){
                                arr_purchase_categories.push(brand);    
                            }
                        });

                        var exist_counter = 0;
                        var arr_exist_product = [];
                        $("input[name='productcatid[]']").each(function() {
                            if(jQuery.inArray($(this).val(), arr_purchase_categories) !== -1){
                                arr_exist_product.push(parseInt($(this).data('productid')));
                                exist_counter++
                            }
                        });

                        if(exist_counter > 0){
                            // get highest product purchase
                            var highest = -Infinity; 
                            var iteration;

                            $(".input_product_total_price").each(function() {
                                var id = $(this).data('id');
                                var productid = $(this).data('productid');

                                var x = $('#cart_product_reward'+id).val();
                                if(x == 0){
                                    if(jQuery.inArray(parseInt(productid), arr_exist_product) !== -1){
                                        highest = Math.max(highest, parseFloat(this.value));
                                    }
                                }
                            });
                                
                            $(".input_product_total_price").each(function() {
                                if(parseFloat(this.value) == parseFloat(highest.toFixed(2))){
                                    iteration = $(this).data('id');
                                }
                            });
                        }
                    }

                    if(brands != ''){
                        var brand_split = brands.split('|');

                        var arr_purchase_brands = [];
                        $.each(brand_split, function(key, brand) {
                            if(brand != ''){
                                arr_purchase_brands.push(brand);    
                            }
                        });

                        var exist_counter = 0;
                        var arr_exist_product = [];
                        $("input[name='productbrand[]']").each(function() {
                            if(jQuery.inArray($(this).val(), arr_purchase_brands) !== -1){
                                arr_exist_product.push(parseInt($(this).data('productid')));
                                exist_counter++
                            }
                        });

                        if(exist_counter > 0){
                            // get highest product purchase
                            var highest = -Infinity; 
                            var iteration;

                            $(".input_product_total_price").each(function() {
                                var id = $(this).data('id');
                                var productid = $(this).data('productid');

                                var x = $('#cart_product_reward'+id).val();
                                if(x == 0){
                                    if(jQuery.inArray(parseInt(productid), arr_exist_product) !== -1){
                                        highest = Math.max(highest, parseFloat(this.value));
                                    }
                                }
                            });
                                
                            $(".input_product_total_price").each(function() {
                                if(parseFloat(this.value) == parseFloat(highest.toFixed(2))){
                                    iteration = $(this).data('id');
                                }
                            });
                        }
                    }
                    
                    var price = parseFloat($('#price'+iteration).val());
                
                    var totalpurchaseqty = parseFloat($('#purchaseqty'+cid).val())+1;
                    var purchaseqty = parseFloat($('#purchaseqty'+cid).val());
                    var cartQty = parseFloat($('#order'+iteration+'_qty').val());

                    if(cartQty % 2 == 0){
                        var totalProductCartQty = cartQty;
                    } else {
                        var totalProductCartQty = cartQty-1;
                    }

                    var totalDiscountedProduct = 0;
                    for (i = 1; i <= totalProductCartQty; i++) {
                        if(i == purchaseqty){
                            totalDiscountedProduct++;
                            var purchaseqty = parseInt(purchaseqty)+totalpurchaseqty;
                        }                                 
                    }


                    var i;
                    var totaldiscount = 0;

                    var pname       = $('#product_name_'+iteration).val();
                    var productid   = $('#pp'+iteration).val();
                    var combination = $('#couponcombination'+cid).val();
                    var validity    = $('#couponvalidity'+cid).val();

                    var counter = 0;
                    for (i = 1; i <= totalDiscountedProduct; i++) {
                        if(amount > 0){
                            var tdiscount = price-parseFloat(amount);
                        }

                        if(percnt > 0){
                            var percent = parseFloat(percnt)/100;
                            var discount =  price*parseFloat(percent);

                            //var tdiscount = price-parseFloat(discount);
                        } 

                        totaldiscount += discount;
                        discount = totaldiscount;
                        counter++;
                    }

                    arr_coupon_product.push(iteration);

                    var sub_price = $('#input_order'+iteration+'_product_total_price').val();
                    var productSubTotalDiscount = parseFloat(sub_price)-parseFloat(totaldiscount);
                }

                $('#appliedCouponList').append(
                    '<div class="subtotal" id="appliedCouponDiv'+cid+'">'+
                        '<div class="coupon-item">'+
                            // coupon inputs
                                '<input type="hidden" name="couponUsage[]" value="'+counter+'">'+
                                '<input type="hidden" id="coupon_discount'+cid+'" value="'+totaldiscount+'">'+
                                '<input type="hidden" id="coupon_combination'+cid+'" value="'+combination+'">'+
                                '<input type="hidden" id="productid'+cid+'" value="'+iteration+'">'+
                                '<input type="hidden" name="couponid[]" value="'+cid+'">'+
                                '<input type="hidden" name="coupon_productid[]" value="'+productid+'">'+
                                '<input type="hidden" name="coupon_productdiscount[]" value="'+discount+'">'+
                            //
                            '<div class="coupon-item-name text-white" style="background:#b82e24;">'+
                                '<h6 class="p-1 mb-1">'+name+'</h6>'+
                            '</div>'+
                            '<div class="coupon-item-desc mb-1">'+
                                '<small><strong>'+validity+'</strong></small>'+
                                '<p class="m-0">'+desc+'</p>'+
                                '<p class="text-success">Applied On : '+pname+'</p>'+ 
                            '</div>'+
                            '<div class="coupon-item-btns">'+
                                '<button class="btn btn-danger btn-sm productCouponRemove" id="'+cid+'"><i class="fa fa-times"></i></button>&nbsp;'+
                                '<button type="button" class="btn btn-info btn-sm" data-toggle="popover" title="Terms & Condition" data-content="'+terms+'">Terms & Conditions</button>'+
                            '</div>'+
                        '</div>'+
                    '</div>'
                );

                $('[data-toggle="popover"]').popover();

                // Total Amount Coupon Discount 
                    var coupon_discount = parseFloat($('#coupon_total_discount').val());

                    var total_coupon_deduction = coupon_discount+discount;
                    $('#coupon_total_discount').val(total_coupon_deduction.toFixed(2));
                    $('#total_coupon_deduction').html(addCommas(total_coupon_deduction.toFixed(2))); 
                    $('.couponDiscountSummary').css('display','block');
                //


                // Total Summary Computation
                    $('#cart_product_discount'+iteration).val(discount.toFixed(2));
                    $('#product_coupon_discount'+iteration).html('₱ '+addCommas(discount.toFixed(2)));
                    $('.couponDiscountSpan'+iteration).css('display','block');

                    $('#input_order'+iteration+'_product_total_price').val(productSubTotalDiscount.toFixed(2));
                    $('#order'+iteration+'_total_price').html(addCommas(productSubTotalDiscount.toFixed(2)))
                    
                    grandTotal();
                //

                $('#cart_product_reward'+iteration).val(1);
                $('#couponBtn'+cid).prop('disabled',true);
                $('#btnCpnTxt'+cid).html('Applied');
            }
        }

        $(document).on('click','.productCouponRemove', function(){
            var id = $(this).attr('id'); // coupon id
            var pid = $('#productid'+id).val(); // product iteration id

            var product_subtotal = parseFloat($('#input_order'+pid+'_product_total_price').val());
            var total_reward_on_product = $('#cart_product_reward'+pid).val();
            var discount = $('#coupon_discount'+id).val();

            var coupon_total_discount = parseFloat($('#coupon_total_discount').val());
            var coupon_product_discount = parseFloat($('#cart_product_discount'+pid).val());
            
            var updated_coupon_discount = coupon_total_discount-coupon_product_discount;
            $('#coupon_total_discount').val(updated_coupon_discount.toFixed(2));
            $('#total_coupon_deduction').html(addCommas(updated_coupon_discount.toFixed(2))); 

            $('#cart_product_reward'+pid).val(0);
            $('#cart_product_discount'+pid).val(0);

            var orig_product_price = product_subtotal+coupon_product_discount;

            $('#order'+pid+'_total_price').html(addCommas(orig_product_price.toFixed(2)));
            $('.couponDiscountSpan'+pid).css('display','none');

            var counter = $('#coupon_counter').val();
            $('#coupon_counter').val(parseInt(counter)-1);

            var combination = $('#coupon_combination'+id).val();
            if(combination == 0){
                $('#solo_coupon_counter').val(0);
            }

            $('#appliedCouponDiv'+id+'').remove(); 

            grandTotal();
        });
    // end choose product


    // coupon free products
        function free_product_coupon(cid){
            if(coupon_counter(cid)){
                var name  = $('#couponname'+cid).val();
                var terms = $('#couponterms'+cid).val();
                var desc = $('#coupondesc'+cid).val();
                var freeproductid = $('#couponfreeproductid'+cid).val();
                var combination = $('#couponcombination'+cid).val();
                var validity    = $('#couponvalidity'+cid).val();

                $('#appliedCouponList').append(
                    '<div class="subtotal" id="appliedCouponDiv'+cid+'">'+
                        '<div class="coupon-item">'+
                            // coupon inputs
                                '<input type="hidden" name="couponUsage[]" value="0">'+
                                '<input type="hidden" id="coupon_combination'+cid+'" value="'+combination+'">'+
                                '<input type="hidden" name="couponid[]" value="'+cid+'">'+
                                '<input type="hidden" name="coupon_productid[]" value="0">'+
                            //
                            '<div class="coupon-item-name text-white" style="background:#b82e24;">'+
                                '<h6 class="p-1 mb-1">'+name+'</h6>'+
                            '</div>'+
                            '<div class="coupon-item-desc mb-1">'+
                                '<small><strong>'+validity+'</strong></small>'+
                                '<p class="m-0">'+desc+'</p>'+
                            '</div>'+
                            '<div class="coupon-item-btns">'+
                                '<button class="btn btn-danger btn-sm productCouponRemove" id="'+cid+'"><i class="fa fa-times"></i></button>&nbsp;'+
                                '<button type="button" class="btn btn-info btn-sm" data-toggle="popover" title="Terms & Condition" data-content="'+terms+'">Terms & Conditions</button>'+
                            '</div>'+
                        '</div>'+
                    '</div>'
                );

                $('[data-toggle="popover"]').popover();


                $('#couponBtn'+cid).prop('disabled',true);
                $('#btnCpnTxt'+cid).html('Applied');
            }
        }

        $(document).on('click', '.cRmvFreeProduct', function(){  
            var id = $(this).attr("id");  

            var counter = $('#coupon_counter').val();
            $('#coupon_counter').val(parseInt(counter)-1);

            var combination = $('#coupon_combination'+id).val();
            if(combination == 0){
                $('#solo_coupon_counter').val(0);
            }

            $('#appliedCouponDiv'+id+'').remove();   
        });
    //

















    $('#btnCheckout').click(function(){
        var roleid = $('#roleid').val();

        if(roleid != 3){
            swal({
                title: '',
                text: "You are logged in as CMS user. Please use a customer account to proceed this transaction.",         
            });
        } else {
            $('#checkoutForm').submit();
        }
    });

    function FormatAmount(number, numberOfDigits) {

        var amount = parseFloat(number).toFixed(numberOfDigits);
        var num_parts = amount.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        return num_parts.join(".");
    }

    $('.quantity-up').click(function(){
        var id = $(this).attr("id");
        var orderid = $('#orderID_'+id).val();

        if(id){
            var qty = $('#order'+id+'_qty').val();
            var maxorder = $('#maxorder'+id).val();

            if(parseInt(qty) > parseInt(maxorder)){
                swal({
                    title: '',
                    text: "Sorry. Currently, there is no sufficient stocks for the item you wish to order.",         
                });

                $('#order'+id+'_qty').val(qty-1);
            } else {
                $('#prevqty'+id).val(qty);

                addQty(id,orderid);
            }  
        }
    });

    $('.quantity-down').click(function(){
        var id = $(this).attr("id");
        var productid = $('#orderID_'+id).val();

        if(id){
            var prevqty = $('#prevqty'+id).val();

            if(prevqty == 1){

            } else {
                $('#prevqty'+id).val(prevqty-1);

                var auth = $('#auth').val();
                if(auth == 0){
                    addQty(id,productid);  
                } else {
                    deductQty(id,productid);
                }
            } 
        }
    });

    function deductQty(id,productid){
        var qty = $('#order'+id+'_qty').val();
        var weight = $('#input_order'+id+'_product_weight').val();
        var price = $('#input_order'+id+'_product_price').val();

        total_weight = parseFloat(weight)*parseFloat(qty);
        total_price  = parseFloat(price)*parseFloat(qty);

        $('#order'+id+'_total_weight').html(FormatAmount(total_weight,2));
        $('#order'+id+'_total_price').html(FormatAmount(total_price,2));
        $('#input_order'+id+'_product_total_price').val(total_price);

        $.ajax({
            data: {
                "product_id": productid,
                "_token": "{{ csrf_token() }}",
            },
            type: "post",
            url: "{{route('cart.deduct')}}",
            success: function(returnData) {
                if (returnData['success']) {
                    $('.cart-counter').html(returnData['totalItems']);
                    $('.counter').html(returnData['totalItems']);
                } 
            }
        });

        resetCoupons();
        grandTotal();
    }

    function addQty(id,productid){

        var qty = $('#order'+id+'_qty').val();
        var weight = $('#input_order'+id+'_product_weight').val();
        var price = $('#input_order'+id+'_product_price').val();

        total_weight = parseFloat(weight)*parseFloat(qty);
        total_price  = parseFloat(price)*parseFloat(qty);

        $('#order'+id+'_total_weight').html(FormatAmount(total_weight,2));
        $('#order'+id+'_total_price').html(FormatAmount(total_price,2));
        $('#input_order'+id+'_product_total_price').val(total_price);

        var auth = $('#auth').val();
        if(auth == 1){
            var orderqty = 1;
        } else {
            var orderqty = qty;
        }

        $.ajax({
            data: {
                "product_id": productid,
                "qty": orderqty,
                "_token": "{{ csrf_token() }}",
            },
            type: "post",
            url: "{{route('cart.add')}}",
            success: function(returnData) {
                if (returnData['success']) {
                    $('.cart-counter').html(returnData['totalItems']);
                    $('.counter').html(returnData['totalItems']);
                }
            }
        });

        resetCoupons();
        grandTotal();
    }

    function resetCoupons(){
        $('#appliedCouponList').empty();
        $('#coupon_counter').val(0);
        $('#solo_coupon_counter').val(0);
        $('#total_amount_discount_counter').val(0);
        $('#coupon_total_discount').val(0);

        $('#total_amount_discount').val(0);
        $('.couponDiscountSummary').hide();



        $(".cart_product_reward").each(function() {
            $(this).val(0);
        });

        $(".cart_product_discount").each(function() {
            $(this).val(0);
        });

        $.each(arr_coupon_product, function(key, iteration) {
            var product_price = parseFloat($('#input_order'+iteration+'_product_price').val());
            var qty = parseFloat($('#order'+iteration+'_qty').val());
            var pprice = product_price*qty;

            $('#order'+iteration+'_total_price').html(addCommas(pprice.toFixed(2)));
            $('.couponDiscountSpan'+iteration).css('display','none');
        });
    }

    function grandTotal(){
        var couponTotalDiscount = parseFloat($('#coupon_total_discount').val());
        var totalAmount = 0;

        $(".input_product_total_price").each(function() {
            if(!isNaN(this.value) && this.value.length!=0) {
                totalAmount += parseFloat(this.value);
            }
        });
        
        if(couponTotalDiscount == 0){
            $('.couponDiscountSummary').css('display','none');
        }

        var grandtotal = totalAmount-couponTotalDiscount;
        
        $('#subtotal').html(FormatAmount(totalAmount,2));
        $('#grandtotal').html(FormatAmount(grandtotal,2));
        $('#npt_grandTotal').val(grandtotal);


        
        // $('#total_sub').html('₱ '+addCommas(subtotal.toFixed(2)));
        // $('#grandTotal').val(grandTotal.toFixed(2));
        // $('#total_grand').html('₱ '+addCommas(grandTotal.toFixed(2))); 
    }

    function remove_item(id){
        swal({
            title: 'Are you sure?',
            text: "This will remove the item from your cart",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it!'            
        },
        function(isConfirm) {
            if (isConfirm) {
                $('#order_id').val(id);
                $('#remove_order_form').submit();
            } 
            else {                    
                swal.close();                   
            }
        });
    }
    </script>
@endsection