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
        <!-- <div>
            <button class="btn btn-outline-primary btn-sm tx-semibold" onclick="window.print();"><i data-feather="printer"></i> Print</button>
        </div> -->
    </div>

    <div class="row row-sm">
        <div class="col-sm-6 col-lg-8">
            <p class="mg-b-3">&nbsp;</p>
            <p class="mg-b-3">http:://stpauls.ph/</p>
            <p class="mg-b-3">&nbsp;</p>
        </div>
        <div class="col-sm-6 col-lg-4">
            <ul class="list-unstyled lh-7">
                <li class="d-flex justify-content-between">
                    <span>Order Date</span>
                    <span>{{ date('m/d/Y h:i:s A', strtotime($sales->created_at))}}</span>
                </li>
                <li class="d-flex justify-content-between">
                    <span>Transaction Number</span>
                    <span>{{$sales->order_number}}</span>
                </li>               
                <li class="d-flex justify-content-between">
                    <span>Payment Method</span>
                    <span>@if($sales->payment_method == 0) Cash @else {{$sales->payment->payment_type }} @endif</span>
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
        <div class="col-lg-12">
            Remarks : {{ $sales->remarks }}
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
                        <th class="wd-10p">Product Code</th>
                        <th class="tx-center">Price (₱)</th>
                        <th class="tx-right">Quantity</th>
                        <th class="tx-right">Total (₱)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $subtotal = 0; @endphp

                    @forelse($sales->items as $item)
                    @php
                        $subtotal += $item->price*$item->qty;
                    @endphp
                    <tr>
                        <td>{{ $item->product_name}}</td>
                        <td class="text-center">{{ number_format($item->price,2) }}</td>
                        <td class="text-center">{{ number_format($item->qty,2) }}</td>
                        <td class="text-right">{{ number_format($item->price*$item->qty,2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td class="tx-center " colspan="4">No items found.</td>
                    </tr>
                    @endforelse
                    <tr>
                        <td colspan="3" class="text-right"><strong>Subtotal</strong></td>
                        <td class="text-right">{{ number_format($subtotal,2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Shipping Rate</strong></td>
                        <td class="text-right">{{ number_format($sales->delivery_fee_amount,2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Service Fee</strong></td>
                        <td class="text-right">{{ number_format($sales->service_fee,2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Loyalty Discount</strong></td>
                        <td class="text-right">{{ number_format($sales->discount_amount,0) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Grand Total</strong></td>
                        <td class="text-right">{{ number_format($sales->net_amount,2) }}</td>
                    </tr>
                </tbody>
            </table>
            @if($sales->status != 'CANCELLED')
                @if($sales->is_approve == 0 && $sales->delivery_type == 'Cash on Delivery')
                    <button type="button" class="btn btn-sm btn-danger float-right mg-l-5" onclick="order_response('{{$sales->id}}','{{$sales->order_number}}','REJECT');">Reject</button>
                    <button type="button" class="btn btn-sm btn-primary float-right" onclick="order_response('{{$sales->id}}','{{$sales->order_number}}','APPROVE');">Approve</button>
                @endif
            @endif
        </div>
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