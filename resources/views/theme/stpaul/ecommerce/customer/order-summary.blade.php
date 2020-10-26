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
                <div class="col-lg-9" style="border-bottom: 10px solid #b81600;">
                    <div class="row row-sm">
                        <div class="col-sm-6 col-lg-6 mg-b-20">
                            <img src="{{  asset('storage/logos/'.Setting::getFaviconLogo()->company_logo) }}" alt="StPaul" width="240" />
                        </div>
                        <div class="col-sm-6 col-lg-6 mg-b-20">
                            <small class="d-flex justify-content-end">Order Number</small>
                            <h1 class="d-flex justify-content-end" style="margin-top:-2px;color:#b82e24;">{{ $sales->order_number }}</h1>
                        </div>
                        <div class="col-sm-6 col-lg-8" style="border-left: 6px solid #b82e24;margin-top: 30px;">
                            <label class="tx-sans tx-medium tx-spacing-1 tx-color-03">Delivery Details</label>
                            <h2 class="name" style="color:#555555;">{{ $sales->customer_name }}</h2>
                            <p class="mg-b-3">{{$sales->customer_address}}</p>
                            <p class="mg-b-3">{{$sales->customer_contact_number}}</p>
                            <p class="mg-b-3"><a style="color:blue;" href="mailto:{{ $sales->customer_main_details->email }}">{{ $sales->customer_main_details->email }}</a></p>
                            <p>&nbsp;</p>
                            <p>Remarks : {{ $sales->remarks }}</p>
                            @if($sales->sdd_booking_type == 1)
                            <p>Courier Name : {{$sales->courier_name }}</p>
                            <p>Rider Name : {{ $sales->rider_name }}</p>
                            <p>Contact # : {{ $sales->rider_contact_no }}</p>
                            <p>Plate # : {{ $sales->rider_plate_no }}</p>
                            <p>Rider Tracker Link : {{ $sales->rider_link_tracker }}</p>
                            @endif
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

                        <div class="table-responsive">
                            <table border="0" cellspacing="0" cellpadding="0" style="margin-top: 50px;">
                                <thead style="background:#b81600;">
                                    <tr style="color:white;">
                                        <th width="30%"  style="text-align: left;">Item(s)</th>
                                        <th width="5%" style="text-align: right;">Weight (kg)</th>
                                        <th width="10%" style="text-align: right;">Price (₱)</th>
                                        <th width="5%" style="text-align: right;">Quantity</th>
                                        <th width="30%" style="text-align: right;">Total Weight (kg)</th>
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
                                        <td>Sub-Total</td>
                                        <td class="text-right">{{ number_format($subtotal,2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-danger">LESS: Loyalty Discount({{$sales->discount_percentage}}%)</td>
                                        <td class="text-right text-danger">{{ number_format($sales->discount_amount,2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" rowspan="3">
                                            <div class="col-sm-12 col-lg-8 order-2 order-sm-0 mg-t-40 mg-sm-t-0">
                                                <div class="gap-30"></div>
                                                <label class="tx-sans tx-10 tx-medium tx-spacing-1 tx-color-03">Other Instructions</label>
                                                <p>{{ $sales->other_instruction ?? 'N/A' }}</p>
                                            </div>
                                        </td>
                                        <td>ADD: Shipping Fee</td>
                                        <td class="text-right">{{ number_format($sales->delivery_fee_amount,2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>ADD: Service Fee</td>
                                        <td class="text-right">{{ number_format($sales->service_fee,2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><h5 class="text-success"><b>TOTAL DUE</b></h5></td>
                                        <td class="text-right"><h5>{{ number_format($sales->net_amount,2)}}</h5></td>
                                    </tr>
                                </tfoot>
                            </table> 
                        </div>
                        
                        <div class="col-sm-6 col-lg-9 mg-b-20" style="padding-top: 50px;font-size:13px;">
                            <span class="tx-lowercase"><i class="fa fa-facebook-square"></i> &nbsp;www.stpauls.ph</span>&nbsp;&nbsp;
                            <span><i class="fa fa-facebook-square"></i> &nbsp;St Pauls Online</span>&nbsp;&nbsp;
                            <span class="tx-lowercase"><i class="fa fa-facebook-square"></i> &nbsp;@stpaulsph</span>
                        </div>
                        <div class="col-sm-6 col-lg-3 mg-b-20 d-flex justify-content-end">
                            <img src="{{ url('/') }}/theme/stpaul/images/others/Doing all for the gospel.png" width="200px">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('pagejs')
    <script src="{{ asset('theme/stpaul/plugins/datatables/datatables.min.js') }}"></script>
@endsection
