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
            <form method="post" action="{{route('cart.front.batch_update')}}">
                @csrf
                <div class="row">
                    <div class="col-lg-9">
                        <div class="cart-title">
                            <h2>My Cart</h2>
                        </div>
                        <ul class="cart-wrap">
                            @php $grandtotal = 0; @endphp
                            @forelse($cart as $order)
                                @php $grandtotal += $order->ItemTotalPrice; @endphp
                            <li class="item">
                                <div class="remove-item">
                                    <a href="#" onclick="remove_item('{{$order->id}}')" style="font-size: .7em;" class="text-uppercase txt-10">Remove <span class="lnr lnr-cross"></span></a>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-4">
                                        <div class="img-wrap">
                                            <a href="#"><img src="{{ asset('storage/products/'.$order->product->photoPrimary) }}" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <div class="info-wrap">
                                            <div class="cart-description">
                                                <input type="hidden" name="cart_id[]" value="{{$order->id}}">
                                                <h3 class="cart-product-title"><a href="product-profile.htm">{{ $order->product->name }}</a></h3>
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
                                                    <input type="number" name="qty[]" value="{{ $order->qty }}" min="1" max="{{ $order->product->inventory }}" step="1" data-inc="1">
                                                    <div class="quantity-nav">
                                                        <div class="quantity-button quantity-up">+</div>
                                                        <div class="quantity-button quantity-down">-</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="cart-info">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <table>
                                                            <tr>
                                                                <td>
                                                                    <p>Weight (g)</p>
                                                                </td>
                                                                <td>
                                                                    <p>{{ $order->product->weight }}</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <p>Total Weight (g)</p>
                                                                </td>
                                                                <td>
                                                                    <p>{{ $order->TotalWeight }}</p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="cart-product-price">₱ {{ $order->ItemTotalPrice }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @empty

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
                                            ₱ {{ number_format($grandtotal,2) }}
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
                                            ₱ {{ number_format($grandtotal,2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="shipping-message">Shipping fees will apply <span class="white-spc">upon checkout</span></div>
                            <div class="cart-btn">
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" name="btnUpdateCart" value="1" class="btn btn-lg secondary-btn">Update My Cart</button>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" name="btnCheckout" value="1" class="btn btn-lg tertiary-btn">Proceed to Checkout</button>
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