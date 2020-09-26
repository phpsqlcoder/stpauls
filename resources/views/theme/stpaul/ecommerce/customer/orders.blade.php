@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
@endsection

@section('content')
<main>
    <section id="default-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.account-page-options')
                </div>
                <div class="col-lg-9">
                    <div class="article-content">
                        <h3 class="subpage-heading">My Orders</h3>
                        @if(session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        <table id="salesTransaction" class="table table-md table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col">Order #</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Order Status</th>
                                    <th scope="col">Delivery Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $sale)
                                <tr>
                                    <td>{{ $sale->order_number }}</td>
                                    <td>{{ date('Y-m-d h:i A',strtotime($sale->created_at)) }}</td>
                                    <td>{{ number_format($sale->gross_amount,2) }}</td>
                                    <td class="text-uppercase">
                                        @if($sale->status == 'CANCELLED')
                                            CANCELLED
                                        @else
                                            @if($sale->status == 'COMPLETED')
                                                COMPLETED
                                            @else
                                                @if($sale->payment_status == 'UNPAID')
                                                    {{ $sale->status }}
                                                @else
                                                    {{ $sale->payment_status }}
                                                @endif
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-uppercase">{{ $sale->delivery_status }}</td>
                                    <td align="right">
                                        @if($sale->status != 'CANCELLED')
                                            @if($sale->payment_method > 1)
                                                @if($sale->payment_status == 'UNPAID')
                                                <a href="" title="Pay now" onclick="pay('{{$sale->id}}','{{$sale->net_amount}}','{{$sale->payment_option}}')" id="paybtn{{$sale->id}}">
                                                    <span class="lnr lnr-inbox mr-2"></span>
                                                </a>
                                                @endif
                                            @endif
                                                @if($sale->payment_status == 'UNPAID')
                                                <a href="#" title="Cancel Order" id="cancelbtn{{$sale->id}}" onclick="cancelOrder('{{$sale->id}}')">
                                                    <span class="lnr lnr-cross mr-2"></span>
                                                </a> 
                                                @endif
                                        @endif

                                        <a href="#" title="Track your order" onclick="view_delivery_details('{{$sale->id}}','{{$sale->order_number}}')"><span class="lnr lnr-car mr-2"></span></a>
                                        <a href="#" title="View items" onclick="view_items('{{$sale->id}}','{{$sale->order_number}}','{{date('Y-m-d',strtotime($sale->created_at))}}','{{$sale->payment_status}}','{{$sale->delivery_type}}')">
                                            <span class="lnr lnr-eye"></span>
                                        </a>
                                    </td>
                                </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $sales->appends($_POST)->links() }}
                </div>
            </div>
        </div>
    </section>
</main>

<div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="payment_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Payment Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form autocomplete="off" action="{{ route('pay-order') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="header_id" id="header_id">
                        <label for="Amount" class="col-form-label">Payment Type *</label>
                        <input readonly type="text" name="payment_type" id="payment_type" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Amount" class="col-form-label">Payment Date *</label>
                        <input required type="date" name="payment_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Amount" class="col-form-label">Reference # *</label>
                        <input required type="text" name="refno" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Amount" class="col-form-label">Amount *</label>
                        <input required type="number" step="0.1" name="amount" id="balance" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Amount" class="col-form-label">Attachment *</label>
                        <input required type="file" name="attachment" class="form-control">
                    </div>                              
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="delivery_modal" tabindex="-1" role="dialog" aria-labelledby="trackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trackModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="transaction-status">
                </div>
                <div class="gap-20"></div>
                <div class="table-modal-wrap">
                    <table class="table table-md table-modal">
                        <thead>
                            <tr>
                                <th>Date and Time</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="tr_deliveries">
                            
                        </tbody>
                    </table>
                </div>
                <div class="gap-20"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="items_modal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="transaction-status">
                    <p>Date: <span id="order_date"></span></p>
                    <p>Payment Status: <span id="payment_status"></span></p>
                    <p>Delivery Type: <span id="delivery_type"></span></p>
                </div>
                <div class="gap-20"></div>
                <div class="table-modal-wrap">
                    <table class="table table-md table-modal" style="font-size:12px !important;">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="tr_items">
                            
                        </tbody>
                    </table>
                </div>
                <div class="gap-20"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('pagejs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
@endsection

@section('customjs')
    <script>
        function view_delivery_details(orderid,orderNo){
            $.ajax({
                type: "GET",
                url: "{{ route('display-delivery-history') }}",
                data: { orderid : orderid },
                success: function( response ) {
                    $('#tr_deliveries').html(response);
                    $('#trackModalLabel').html(orderNo);
                    $('#delivery_modal').modal('show');
                }
            });
        }

        function view_items(orderid,orderNo,date,paymentStatus,deliveryType){
            $.ajax({
                type: "GET",
                url: "{{ route('display-items') }}",
                data: { orderid : orderid },
                success: function( response ) {
                    $('#tr_items').html(response);
                    $('#viewModalLabel').html(orderNo);
                    $('#order_date').html(date);
                    $('#payment_status').html(paymentStatus);
                    $('#delivery_type').html(deliveryType);
                    $('#items_modal').modal('show');
                }
            });
        }

        function pay(order_id,balance,paymentType){
            var bal = parseFloat(balance);

            $('#payment_type').val(paymentType);
            $('#payment_modal').modal('show');
            $('#header_id').val(order_id);
            $('#balance').val(bal.toFixed(2));

            $('#balance').prop('max',bal);
        }

        function cancelOrder(orderid){
            swal({
                title: 'Are you sure?',
                text: "Are you sure you want to cancel this order?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, cancel it!'            
            },
            function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('transaction.cancel-order') }}",
                        data: { 
                                orderid : orderid,
                            },
                        success: function( response ) {
                            swal("Success!", "Order has been cancelled.", "success");
                            $('#paybtn'+orderid).hide();
                            $('#cancelbtn'+orderid).hide();
                            $('#order'+orderid+'_status').html('CANCELLED');
                            
                        },
                        error: function( response ){
                            swal("Error!", "Failed to cancel the order.", "danger"); 
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