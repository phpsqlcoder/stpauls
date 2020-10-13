@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/datatables/datatables.min.css') }}" />
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

        table tbody tr:last-child td {
            border: none;
        }

        table tfoot tr td:second-child {
            border: none;
        }
    </style>
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
                    <div class="row row-sm">
                        <div class="col-sm-6 col-lg-6 mg-b-20">
                            <img src="{{  asset('storage/logos/'.Setting::getFaviconLogo()->company_logo) }}" alt="StPaul" width="240" />
                        </div>
                        <div class="col-sm-6 col-lg-6 mg-b-20">
                            <small class="d-flex justify-content-end">Order Number</small>
                            <h1 class="d-flex justify-content-end" style="margin-top:-2px;color:#b82e24;">{{ $sales->order_number }}</h1>
                        </div>
                        <div class="col-sm-6 col-lg-8" style="border-left: 6px solid #b82e24;margin-top: 30px;">
                            <label class="tx-sans tx-medium tx-spacing-1 tx-color-03">Billing Details</label>
                            <h2 class="name" style="color:#555555;">{{ $sales->customer_name }}</h2>
                            <p class="mg-b-3">{{$sales->customer_address}}</p>
                            <p class="mg-b-3">{{$sales->customer_contact_number}}</p>
                            <p class="mg-b-3"><a style="color:blue;" href="mailto:{{ $sales->customer_main_details->email }}">{{ $sales->customer_main_details->email }}</a></p>
                            <p>&nbsp;</p>
                            <p>Remarks : {{ $sales->remarks }}</p>
                        </div>
                        <!-- col -->

                        <div class="col-sm-6 col-lg-4" style="border-right: 6px solid #b82e24;margin-top: 30px;">
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
                                    <span class="text-success tx-uppercase"><b>{{ $sales->payment_status }}</b></span>
                                </li>
                                <hr>
                                <li class="d-flex justify-content-between">
                                    <span>Delivery Type</span>
                                    <span><b>{{ $sales->delivery_type }}</b></span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span>Branch</span>
                                    <span class="text-uppercase">{{ $sales->branch ?? 'N/A' }}</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span>Delivery Status</span>
                                    <span class="text-success tx-uppercase"><b>{{ $sales->delivery_status }}</b></span>
                                </li>
                            </ul>
                        </div>

                        <table border="0" cellspacing="0" cellpadding="0" style="margin-top: 50px;">
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
                                    <td colspan="4" rowspan="3"></td>
                                    <td>Total Weight</td>
                                    <td class="text-right">{{ ($totalweight/1000) }} kg</td>
                                </tr>
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="text-right">{{ number_format($subtotal,2) }}</td>
                                </tr>
                                <tr>
                                    <td>Shipping Fee</td>
                                    <td class="text-right">{{ number_format($sales->delivery_fee_amount,2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" rowspan="3">
                                        <div class="col-sm-12 col-lg-8 order-2 order-sm-0 mg-t-40 mg-sm-t-0">
                                            <div class="gap-30"></div>
                                            <label class="tx-sans tx-10 tx-medium tx-spacing-1 tx-color-03">Other Instructions</label>
                                            <p>{{ $sales->other_instruction ?? 'N/A' }}</p>
                                        </div>
                                    </td>
                                    <td>Service Fee</td>
                                    <td class="text-right">{{ number_format($sales->service_fee,2) }}</td>
                                </tr>
                                <tr>
                                    <td>Loyalty Discount</td>
                                    <td class="text-right">{{ number_format($sales->discount_amount,0) }}%</td>
                                </tr>
                                <tr>
                                    <td><h5 class="text-success"><b>Grand Total</b></h5></td>
                                    <td class="text-right"><h5>{{ number_format($sales->net_amount,2)}}</h5></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{--<div class="article-content">
                        <h3 class="subpage-heading">Order #{{$sales->order_number}}</h3>

                        <table width="100%" style="font-size:16px;">
                            <tr>
                                <td>
                                    <strong>BILLING INFORMATION</strong><br />
                                    Order Date: {{ date('Y-m-d h:i A',strtotime($sales->created_at))}}<br/>
                                    Customer Name: {{$sales->customer_name}}<br />
                                    Billing Address&nbsp;&nbsp;: {{$sales->customer_delivery_adress}}
                                </td>
                                <td>
                                    <strong>DELIVERY INFORMATION</strong><br />
                                    Shipping Type: {{$sales->delivery_type}}<br />
                                    Delivery Status: {{$sales->delivery_status}}<br/>
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>PAYMENT INFORMATION</strong><br />
                                    Payment Method: 
                                        @if($sales->payment_option == 0)
                                            Cash
                                        @elseif($sales->payment_option == 1)
                                            Card Payment
                                        @else
                                            $sales->payment_option
                                        @endif<br/>
                                    Payment Status: {{$sales->payment_status}}
                                </td>
                            </tr>
                        </table>

                        <br/><br/>
                        <table width="100%" class="table-striped">
                            <thead style="background:#b81600 ;">
                                <tr style="color:white;">
                                    <th style="padding:.5em;width: 30%;">Item(s)</th>
                                    <th style="padding:.5em; text-align:center;width: 15%;">Weight (kg)</th>
                                    <th style="padding:.5em; text-align:center;width: 15%;">Price (₱)</th>
                                    <th style="padding:.5em; text-align:center;width: 10%;">Quantity</th>
                                    <th style="padding:.5em; text-align:center;width: 20%;">Total Weight (kg)</th>
                                    <th style="padding:.5em; text-align:center;width: 10%;">Total (₱)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $weight = 0;
                                    $totalamount = 0;
                                    $subtotal = 0;
                                    $totalweight = 0;
                                @endphp

                                @foreach($sales->items as $item)
                                @php
                                    $weight = $item->product->weight*$item->qty;
                                    $totalamount = $item->price*$item->qty;
                                    $subtotal += $totalamount;
                                    $totalweight += $weight;
                                @endphp
                                <tr>
                                    <td style="padding:.5em;">
                                        <div style="width:60%;">
                                            {{ $item->product->name }}
                                        </div>
                                    </td>
                                    <td style="padding:.5em; text-align:center;">{{ ($item->product->weight/1000) }}</td>
                                    <td style="padding:.5em; text-align:center;">{{ number_format($item->price,2) }}</td>
                                    <td style="padding:.5em; text-align:center;">{{ $item->qty }}</td>
                                    <td style="padding:.5em; text-align:center;">{{ ($weight/1000) }}</td>
                                    <td style="padding:.5em; text-align:right;">{{ number_format($totalamount,2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="text-align:right;"><strong>Total Weight</strong></td>
                                    <td></td>
                                    <td style="text-align:right;">{{ ($totalweight/1000) }} kg</td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="text-align:right;"><strong>Subtotal</strong></td>
                                    <td></td>
                                    <td style="text-align:right;">₱ {{ number_format($subtotal,2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="text-align:right;"><strong>Loyalty Discount</strong></td>
                                    <td></td>
                                    <td style="text-align:right;">{{ number_format($sales->discount_amount,0) }}%</td>
                                </tr>

                                <tr>
                                    <td colspan="4" style="text-align:right;"><strong>Shipping Rate</strong></td>
                                    <td></td>
                                    <td style="text-align:right;">₱ {{ number_format($sales->delivery_fee_amount,2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="text-align:right;"><strong>Service Fee</strong></td>
                                    <td></td>
                                    <td style="text-align:right;">₱ {{ number_format($sales->service_fee,2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="text-align:right;"><strong>Grand Total</strong></td>
                                    <td></td>
                                    <td style="text-align:right;">₱ {{number_format($sales->net_amount,2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>--}}
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('pagejs')
    <script src="{{ asset('theme/stpaul/plugins/datatables/datatables.min.js') }}"></script>
@endsection
