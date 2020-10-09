@extends('admin.layouts.app')

@section('pagecss')
    <style>
        .table tbody tr td {
            padding: 10px;
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
        <div class="col-sm-6 col-lg-8">
            <ul class="list-unstyled lh-7">
                <li>
                    <span>&nbsp;</span>
                    <span>&nbsp;</span>
                </li>
                <li>
                    <span>&nbsp;</span>
                    <span>&nbsp;</span>
                </li>               
                <li>
                    <span>{{ url('/') }}</span>
                    <span>&nbsp;</span>
                </li>
                <li>
                    <span>&nbsp;</span>
                    <span>&nbsp;</span>
                </li>  
                <li>
                    <span>Remarks:</span>
                    <span>{{ $sales->remarks }}</span>
                </li>  
            </ul>
        </div>
        <div class="col-sm-6 col-lg-4">
            <ul class="list-unstyled lh-7">
                <li class="d-flex justify-content-between">
                    <span>Order Number</span>
                    <span>{{$sales->order_number}}</span>
                </li> 
                <li class="d-flex justify-content-between">
                    <span>Order Date</span>
                    <span>{{ date('m/d/Y h:i:s A', strtotime($sales->created_at))}}</span>
                </li>              
                <li class="d-flex justify-content-between">
                    <span>Payment Method</span>
                    <span>{{ \App\EcommerceModel\SalesHeader::payment_type($sales->id) }}</span>
                </li>
                <li class="d-flex justify-content-between">
                    <span>Delivery Status</span>
                    <span>{{ $sales->delivery_status }}</span>
                </li>  
                <li class="d-flex justify-content-between">
                    <span>Payment Status</span>
                    <span>{{ $sales->payment_status }}</span>
                </li>  
            </ul>
        </div>

        <div class="table-responsive mg-t-20">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="wd-60">Billing Information</th>
                        <th class="wd-40p">{{ $sales->delivery_type }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            {{ $sales->customer_name }}<br>
                            {{ $sales->customer_delivery_adress }}<br>
                            {{ $sales->customer_contact_number }}
                        </td>
                        <td colspan="3">
                            @if($sales->branch != '')
                                Branch : {{ $sales->branch }}<br>
                            @endif

                            @if($sales->is_other == 1)
                                Other Address : {{ $sales->customer_delivery_adress }}<br>
                            @else
                                {{ $sales->customer_delivery_adress }}<br>
                            @endif

                            Instruction : {{ $sales->other_instruction }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-responsive mg-t-20">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="wd-30p">Items</th>
                        <th class="tx-center wd-15p">Weight (kg)</th>
                        <th class="tx-center wd-15p">Price (₱)</th>
                        <th class="tx-center wd-15p">Quantity</th>
                        <th class="tx-center wd-15p">Total Weight (kg)</th>
                        <th class="tx-right wd-10p">Total (₱)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $weight = 0; $total = 0; $subtotal = 0; $totalweight = 0; @endphp

                    @forelse($sales->items as $item)
                    @php
                        $weight = $item->product->weight*$item->qty;
                        $total = $item->price*$item->qty;
                        $subtotal += $item->price*$item->qty;
                        $totalweight += $weight;
                    @endphp
                    <tr>
                        <td>{{ $item->product_name}}</td>
                        <td class="tx-center">{{ ($item->product->weight/1000) }}</td>
                        <td class="text-center">{{ number_format($item->price,2) }}</td>
                        <td class="text-center">{{ number_format($item->qty,2) }}</td>
                        <td class="tx-center">{{ ($weight/1000) }}</td>
                        <td class="text-right">{{ number_format($total,2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td class="tx-center " colspan="4">No items found.</td>
                    </tr>
                    @endforelse
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Total Weight</strong></td>
                        <td colspan="2" class="text-right">{{ ($totalweight/1000) }} kg</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Subtotal</strong></td>
                        <td colspan="2" class="text-right">{{ number_format($subtotal,2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Shipping Fee</strong></td>
                        <td colspan="2" class="text-right">{{ number_format($sales->delivery_fee_amount,2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Service Fee</strong></td>
                        <td colspan="2" class="text-right">{{ number_format($sales->service_fee,2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Loyalty Discount</strong></td>
                        <td colspan="2" class="text-right">{{ number_format($sales->discount_amount,0) }}%</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Grand Total</strong></td>
                        <td colspan="2" class="text-right">{{ number_format($sales->net_amount,2) }}</td>
                    </tr>
                </tbody>
            </table>
            @if($sales->status != 'CANCELLED')
                @if($sales->delivery_status == 'Shipping Fee Validation')
                    <button type="button" class="btn btn-sm btn-primary float-right mg-l-5" onclick="add_shippingfee('{{$sales->id}}','{{$sales->order_number}}');">Add Shipping Fee</button>
                @else
                    @if($sales->is_approve == 0 && $sales->delivery_type == 'Cash on Delivery')
                        <button type="button" class="btn btn-sm btn-danger float-right mg-l-5" onclick="order_response('{{$sales->id}}','{{$sales->order_number}}','REJECT');">Reject</button>
                        <button type="button" class="btn btn-sm btn-primary float-right" onclick="order_response('{{$sales->id}}','{{$sales->order_number}}','APPROVE');">Approve</button>
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
        <form action="{{ route('cod-approve-order') }}" method="POST">
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
        <form action="{{ route('cod-approve-order') }}" method="POST">
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