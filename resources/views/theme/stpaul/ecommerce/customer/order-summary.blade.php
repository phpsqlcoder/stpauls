@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/datatables/datatables.min.css') }}" />

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
                                    $weight += $item->product->weight*$item->qty;
                                    $totalamount += $item->price*$item->qty;
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
