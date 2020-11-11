@extends('admin.layouts.app')

@section('pagecss')
    <style>
        h2.name {
            font-size: 1.4em;
            font-weight: normal;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table th {
            padding: 20px;
            background: #b81600;
            border-bottom: 1px solid #FFFFFF;
        }

        table td {
            padding: 15px;
            background: #EEEEEE;
            border-bottom: 1px solid #FFFFFF;
        }

        table tfoot td {
            padding: 10px;
            background: #EEEEEE;
            border-bottom: 1px solid #FFFFFF;
        }

        table th {
            white-space: nowrap;        
            font-weight: normal;
        }

        table tfoot tr:first-child td {
            border-top: none; 
        }

        table tfoot tr:last-child td {
            color: #57B223;
            font-size: 1.4em;
            border-top: 1px solid #57B223; 

        }

        table tfoot tr td:first-child {
            border: none;
        }
    </style>
@endsection

@section('content')
<!-- container start-->
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('sales-transaction.index')}}">Sales Transaction</a></li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1"> Sales Transaction Summary</h4>
        </div>
        <div>
            @if($sales->payment_status == 'PAID')
            <a href="{{route('sales-transaction.invoice',$sales->id) }}" target="_blank" class="btn btn-outline-primary btn-sm tx-semibold"><i data-feather="printer"></i> Print Invoice</a>
            @endif
        </div>
    </div>

    <div class="row row-sm">
        <div class="col-sm-6 col-lg-6 mg-b-20">
            <img src="{{  asset('storage/logos/'.Setting::getFaviconLogo()->company_logo) }}" alt="StPaul" width="240" />
        </div>
        <div class="col-sm-6 col-lg-6 mg-b-20">
            <small class="d-flex justify-content-end">Order Number</small>
            <h1 class="d-flex justify-content-end" style="margin-top:-2px;color:#b82e24;">{{ $sales->order_number }}</h1>
        </div>
        <div class="col-sm-6 col-lg-8" style="border-left: 6px solid #b82e24;">
            <label class="tx-sans tx-medium tx-spacing-1 tx-color-03">Delivery Details</label>
            <h2 class="name">{{ $sales->customer_name }}</h2>
            <p class="mg-b-3">{{$sales->customer_address}}</p>
            <p class="mg-b-3">{{$sales->customer_contact_number}}</p>
            <p class="mg-b-3"><a href="mailto:{{ $sales->customer_main_details->email }}">{{ $sales->customer_main_details->email }}</a></p>
            <p>&nbsp;</p>
            <p class="mg-b-10">Remarks :</p>
            <ul class="list-unstyled">
                @if($sales->remarks != '')
                    <li>* {{ $sales->remarks }}</li>
                @endif
                
                @php
                    $paymentQry = \App\EcommerceModel\SalesPayment::where('sales_header_id',$sales->id);
                    $paymentCount = $paymentQry->count();
                @endphp

                @if($paymentQry->count() > 0)
                    @php
                        $paymentRemarks = $paymentQry->first();
                    @endphp

                    @if($paymentRemarks->remarks != '')
                        <li>* {{ $paymentRemarks->remarks }}</li>
                    @endif
                @endif
            </ul>
            <p>Other Instructions : {{ $sales->other_instruction ?? 'N/A' }}</p>
            @if($sales->sdd_booking_type == 1)
            <p class="mg-b-3">Courier Name : {{ $sales->courier_name }}</p>
            <p class="mg-b-3">Rider Name : {{ $sales->rider_name }}</p>
            <p class="mg-b-3">Contact # : {{ $sales->rider_contact_no }}</p>
            <p class="mg-b-3">Plate # : {{ $sales->rider_plate_no }}</p>
            <p class="mg-b-3">Rider Tracker Link : {{ $sales->rider_link_tracker }}</p>
            @endif
        </div>
        <!-- col -->

        <div class="col-sm-6 col-lg-4" style="border-right: 6px solid #b82e24;">
            <label class="tx-sans tx-medium tx-spacing-1 tx-color-03">Order Details</label>
            <ul class="list-unstyled lh-7">
                <li class="d-flex justify-content-between">
                    <span>Order Date</span>
                    <span>{{ date('m/d/Y h:i A',strtotime($sales->created_at)) }}</span>
                </li>
                <li class="d-flex justify-content-between">
                    <span>Payment Method</span>
                    <span>{{ \App\EcommerceModel\SalesHeader::payment_type($sales->id) }}</span>
                </li>                            
                <li class="d-flex justify-content-between">
                    <span>Payment Status</span>
                    <span class="tx-success tx-semibold tx-uppercase">{{ $sales->payment_status }}</span>
                </li>
                <hr>
                <li class="d-flex justify-content-between">
                    <span>Delivery Type</span>
                    <span class="tx-semibold tx-uppercase">{{ $sales->delivery_type }}</span>
                </li>
                <li class="d-flex justify-content-between">
                    <span>Branch</span>
                    <span class="tx-semibold tx-uppercase">{{ $sales->branch ?? 'N/A' }}</span>
                </li>
                <li class="d-flex justify-content-between">
                    <span>Delivery Status</span>
                    <span class="tx-success tx-semibold tx-uppercase">{{ $sales->delivery_status }}</span>
                </li>
            </ul>
        </div>

        <table border="0" cellspacing="0" cellpadding="0" class="mg-t-40">
            <thead style="background:#b81600;">
                <tr style="color:white;">
                    <th width="30%"  style="text-align: left;">Item(s)</th>
                    <th width="10%" style="text-align: right;">Weight (kg)</th>
                    <th width="10%" style="text-align: right;">Price (₱)</th>
                    <th width="10%" style="text-align: right;">Quantity</th>
                    <th width="20%" style="text-align: right;">Total Weight (kg)</th>
                    <th width="20%" style="text-align: right;">Total (₱)</th>
                </tr>
            </thead>
            <tbody>
                @php $subtotal = 0; $weight = 0; $total = 0; $totalweight = 0; @endphp
                @foreach($sales->items as $item)
                @php
                    $weight   = $item->product->weight*$item->qty;
                    $total    = $item->price*$item->qty;
                    $subtotal += $total;
                    $totalweight += $weight;
                @endphp
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td style="text-align: right;">{{ ($item->product->weight/1000) }}</td>
                    <td style="text-align: right;">{{ number_format($item->price,2) }}</td>
                    <td style="text-align: right;">{{ $item->qty }}</td>
                    <td style="text-align: right;">{{ ($weight/1000) }}</td>
                    <td style="text-align: right;">{{ number_format($total,2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4"></td>
                    <td>Total Weight</td>
                    <td class="text-right">{{ ($totalweight/1000) }} kg</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td>Sub-Total</td>
                    <td class="text-right">{{ number_format($subtotal,2) }}</td>
                </tr>
                @if($sales->discount_percentage > 0)
                <tr>
                    <td colspan="4"></td>
                    <td class="text-danger">LESS: Loyalty Discount({{$sales->discount_percentage}}%)</td>
                    <td class="text-right text-danger">{{ number_format($sales->discount_amount,2) }}</td>
                </tr>
                @endif
                <tr>
                    <td colspan="4"></td>
                    <td>ADD: Shipping Fee</td>
                    <td class="text-right">{{ number_format($sales->delivery_fee_amount,2) }}</td>
                </tr>
                @if($sales->service_fee > 0)
                <tr>
                    <td colspan="4"></td>
                    <td>ADD: Service Fee</td>
                    <td class="text-right">{{ number_format($sales->service_fee,2) }}</td>
                </tr>
                @endif
                <tr>
                    <td colspan="4"></td>
                    <td><h5 class="text-success"><b>TOTAL DUE</b></h5></td>
                    <td class="text-right"><h5>{{ number_format($sales->net_amount,2)}}</h5></td>
                </tr>
            </tfoot>
        </table>

        <div class="table-responsive mg-t-20">
            @if($sales->status != 'CANCELLED')
                @if($sales->delivery_status == 'Shipping Fee Validation')
                    @if (auth()->user()->has_access_to_route('add-shipping-fee'))
                        <button type="button" class="btn btn-sm btn-primary float-right mg-l-5" onclick="add_shippingfee('{{$sales->id}}','{{$sales->order_number}}');">Add Shipping Fee</button>
                    @endif
                @else
                    @if($sales->is_approve == '' && $sales->delivery_type == 'Cash on Delivery')
                        @if (auth()->user()->has_access_to_route('cod-order-response'))
                            <button type="button" class="btn btn-sm btn-danger float-right mg-l-5" onclick="order_response('{{$sales->id}}','{{$sales->order_number}}','REJECT');">Reject</button>
                            <button type="button" class="btn btn-sm btn-primary float-right" onclick="order_response('{{$sales->id}}','{{$sales->order_number}}','APPROVE');">Approve</button>
                        @endif
                    @endif
                @endif
            @endif
        </div>
    </div>
</div>

<div class="modal effect-scale" id="prompt-add-shippingfee" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('add-shipping-fee') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add Shipping Fee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">  
                    <input type="hidden" name="orderid" id="shippingfee_orderid">
                    <p>Enter Shipping fee for order #: <strong><span id="addshippingfee_order"></span></strong></p>
                    <input type="number" name="shippingfee" class="form-control" min="1">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary" id="btnDelete">Submit Shipping Fee</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal effect-scale" id="prompt-approve-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('cod-order-response') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Approve Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">  
                    <input type="hidden" name="orderid" id="id_approve">
                    <input type="hidden" name="status" value="APPROVE">
                    <p>Are you sure you want to approve this order #: <strong><span id="span_approve_order"></span></strong>?</p>

                    @if($sales->is_other == 1)
                    <label>Shipping Fee*</label>
                    <input type="number" name="shippingfee" class="form-control" min="1">
                    <br>
                    @endif

                    <label>Remarks*</label>
                    <textarea name="remarks" requried class="form-control" rows="5" placeholder="Please enter a remarks"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary" id="btnDelete">Yes, Approve</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal effect-scale" id="prompt-reject-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('cod-order-response') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Reject Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">  
                    <input type="hidden" name="orderid" id="id_reject">
                    <input type="hidden" name="status" value="REJECT">
                    <p>Are you sure you want to reject this order #: <strong><span id="span_reject_order"></span></strong>?</p>
                    <textarea name="remarks" requried class="form-control" rows="5" placeholder="Please enter a remarks"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-danger" id="btnDelete">Yes, Reject</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('pagejs')
    <script>
        function add_shippingfee(id,orderno){
            $('#shippingfee_orderid').val(id);
            $('#addshippingfee_order').html(orderno);
            $('#prompt-add-shippingfee').modal('show');
        }

        function order_response(id,order,status){
            if(status == 'APPROVE'){
                $('#id_approve').val(id);
                $('#span_approve_order').html(order);
                $('#prompt-approve-order').modal('show');
            } else {
                $('#id_reject').val(id);
                $('#span_reject_order').html(order);
                $('#prompt-reject-order').modal('show');
            }
        }
    </script>
@endsection