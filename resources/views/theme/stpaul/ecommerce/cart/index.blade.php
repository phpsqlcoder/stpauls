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
                            <strong>Success!</strong> {{ $message }}s
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
                            @php $grandtotal = 0; $totalproducts = 0; $available_stock = 0; @endphp

                            @forelse($cart as $key => $order)

                            @php 
                                $totalproducts += 1;
                                $grandtotal += $order->product->discountedprice*$order->qty;

                                if($order->product->inventory == 0){
                                    $available_stock++;
                                }
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
                                                    <input readonly type="number" name="qty[]" value="{{ $order->qty }}" min="1" step="1" data-inc="1" id="order{{$loop->iteration}}_qty">
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
                                                            <input type="hidden" class="input_product_total_price" id="input_order{{$loop->iteration}}_product_total_price" value="{{$order->product->discountedprice*$order->qty}}">
                                                            <span id="order{{$loop->iteration}}_total_price">
                                                                {{ number_format($order->product->discountedprice*$order->qty,2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
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
                            <h2>Summary</h2>
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

                if(qty > maxorder){
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

            grandTotal();
        }

        function grandTotal(){
            var totalAmount = 0;

            $(".input_product_total_price").each(function() {
                if(!isNaN(this.value) && this.value.length!=0) {
                    totalAmount += parseFloat(this.value);
                }
            });
            
            $('#subtotal').html(FormatAmount(totalAmount,2));
            $('#grandtotal').html(FormatAmount(totalAmount,2));

        }

        function remove_item(id){
            swal({
                title: 'Are you sure?',
                text: "This will remove the item from your cart",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'            
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