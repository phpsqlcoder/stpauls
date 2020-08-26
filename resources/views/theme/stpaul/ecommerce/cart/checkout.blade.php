@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('content')
<main>
    <section id="checkout-wrapper">
        <div class="container">
            <h2 class="checkout-title">Checkout</h2>

            <div class="checkout-info">
                <div id="responsiveTabs2">
                    <ul>
                        <li>
                            <a href="#tab-1">
                                <span class="step">1</span>
                                <span class="title">Billing Information</span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab-2">
                                <span class="step">2</span>
                                <span class="title">Shipping Options</span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab-3">
                                <span class="step">3</span>
                                <span class="title">Payment Method</span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab-4">
                                <span class="step">4</span>
                                <span class="title">Review and Place Order</span>
                            </a>
                        </li>
                    </ul>

                    <div id="tab-1">
                        <div class="checkout-content">
                            <table class="customer-info">
                                <tr>
                                    <td>Name</td>
                                    <td>{{ $customer->fullname }}</td>
                                </tr>
                                <tr>
                                    <td>E-mail Address</td>
                                    <td>{{ $customer->email }}</td>
                                </tr>
                                <tr>
                                    <td>Contact Number</td>
                                    <td>{{ $customer->mobile }}</td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>
                                        {{ $customer->address1 }}<br>{{ $customer->address2 }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Zip Code</td>
                                    <td>{{ $customer->zipcode }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="checkout-nav">
                            <span></span>
                            <a class="checkout-next-btn" href="">Next <span class="lnr lnr-chevron-right"></span></a>
                        </div>
                    </div>
                    <div id="tab-2">
                        <div class="checkout-content">
                            <p class="mb-3">Select a shipping method:</p>
                            <div id="responsiveTabs">
                                <ul>
                                    <li><a href="#nav-1">Cash On Delivery (COD)</a></li>
                                    <li><a href="#nav-2">Store Pick-up</a></li>
                                    <li><a href="#nav-3">Same Day Delivery</a></li>
                                    <li><a href="#nav-4">Door-to-door (D2D)</a></li>
                                </ul>

                                <div id="nav-1">
                                    <div class="checkout-card">
                                        <div class="edit-item">
                                            <a href="#" class="edit">Edit</a>
                                        </div>
                                        <div class="unit flex-row unit-spacing-s">
                                            <div class="unit__left">
                                                <span class="fa fa-check-circle fa-icon"></span>
                                            </div>
                                            <div class="unit__body">
                                                <h3 class="customer-name">{{ $customer->fullname }}</h3>
                                                <p class="customer-address">{{ $customer->address1 }}
                                                    <br> {{ $customer->address2withzip }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="gap-20"></div>
                                    <button type="button" class="btn btn-md btn-txt-3 add-cart-alt2-btn addToCartButton" data-loading-text="processing...">
					                  	<span class="fa fa-plus mr-2"></span> Add New Address
					                </button>
                                </div>
                                <div id="nav-2"> ...2.... </div>
                                <div id="nav-3"> ...3.... </div>
                                <div id="nav-4"> ...4.... </div>
                            </div>
                        </div>
                        <div class="checkout-nav">
                            <a class="checkout-back-btn" href=""><span class="lnr lnr-chevron-left"></span> Back</a>
                            <a class="checkout-next-btn" href="">Next <span class="lnr lnr-chevron-right"></span></a>
                        </div>
                    </div>
                    <div id="tab-3">
                        <div class="checkout-content">
                            <h3>Payment Method</h3>
                            <p>[For integration]</p>
                            <div class="gap-20"></div>
                        </div>
                        <div class="checkout-nav">
                            <a class="checkout-back-btn" href=""><span class="lnr lnr-chevron-left"></span> Back</a>
                            <a class="checkout-next-btn" href="">Next <span class="lnr lnr-chevron-right"></span></a>
                        </div>
                    </div>
                    <div id="tab-4">
                        <div class="checkout-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="subtitle">Billed To</label>
                                    <h3 class="customer-name">Stephen Curry</h3>
                                    <p class="customer-address">Unit 908 9th Floor, Antel Global Corporate Center, Julia Vargas Ave., Ortigas Center, Metro Manila, Philippines 1600</p>
                                    <p class="customer-phone">Tel No: 324 445-4544</p>
                                    <p class="customer-email">Email: youremail@companyname.com</p>
                                </div>
                            </div>

                            <div class="gap-40"></div>

                            <div class="table-responsive mg-t-40">
                                <table class="table table-invoice bd-b order-table">
                                    <thead>
                                        <tr>
                                            <th class="w-25">Type</th>
                                            <th class="w-30 d-none d-sm-table-cell">Description</th>
                                            <th class="w-15 text-center">QNTY</th>
                                            <th class="w-15 text-right">Unit Price</th>
                                            <th class="w-15 text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="tx-nowrap"><a href="">The Friend of the Bridegroom</a></td>
                                            <td class="d-none d-sm-table-cell tx-color-03">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam...</td>
                                            <td class="text-center">
                                                <div class="checkout-quantity">
                                                    <select name="quantity" id="quantity">
							                          	<option value="1">1</option>
							                          	<option value="2" selected>2</option>
							                          	<option value="3">3</option>
							                          	<option value="4">4</option>
							                          	<option value="5">5</option>
							                          	<option value="6">6</option>
							                          	<option value="7">7</option>
							                          	<option value="8">8</option>
							                          	<option value="9">9</option>
							                          	<option value="10">10</option>
							                          	<option value="11">11</option>
							                          	<option value="12">12</option>
							                          	<option value="13">13</option>
							                          	<option value="14">14</option>
							                          	<option value="15">15</option>
							                          	<option value="16">16</option>
							                          	<option value="17">17</option>
							                          	<option value="18">18</option>
							                          	<option value="19">19</option>
							                          	<option value="20">20</option>
							                          	<option value="21">21</option>
							                          	<option value="22">22</option>
							                          	<option value="23">23</option>
							                          	<option value="24">24</option>
							                          	<option value="25">25</option>
							                        </select>
                                                </div>
                                            </td>
                                            <td class="text-right">₱ 150.00</td>
                                            <td class="text-right">₱ 300.00</td>
                                        </tr>
                                        <tr>
                                            <td class="tx-nowrap"><a href="">PESAH</a></td>
                                            <td class="d-none d-sm-table-cell tx-color-03">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque...</td>
                                            <td class="text-center">
                                                <div class="checkout-quantity">
                                                    <select name="quantity" id="quantity">
							                          	<option value="1" selected>1</option>
							                          	<option value="2">2</option>
							                          	<option value="3">3</option>
							                          	<option value="4">4</option>
							                          	<option value="5">5</option>
							                          	<option value="6">6</option>
							                          	<option value="7">7</option>
							                          	<option value="8">8</option>
							                          	<option value="9">9</option>
							                          	<option value="10">10</option>
							                          	<option value="11">11</option>
							                          	<option value="12">12</option>
							                          	<option value="13">13</option>
							                          	<option value="14">14</option>
							                          	<option value="15">15</option>
							                          	<option value="16">16</option>
							                          	<option value="17">17</option>
							                          	<option value="18">18</option>
							                          	<option value="19">19</option>
							                          	<option value="20">20</option>
							                          	<option value="21">21</option>
							                          	<option value="22">22</option>
							                          	<option value="23">23</option>
							                          	<option value="24">24</option>
							                          	<option value="25">25</option>
							                        </select>
                                                </div>
                                            </td>
                                            <td class="text-right">₱ 1,200.00</td>
                                            <td class="text-right">₱ 1,200.00</td>
                                        </tr>
                                        <tr>
                                            <td class="tx-nowrap"><a href="">HeartLines</a></td>
                                            <td class="d-none d-sm-table-cell tx-color-03">Et harum quidem rerum facilis est et expedita distinctio</td>
                                            <td class="text-center">
                                                <div class="checkout-quantity">
                                                    <select name="quantity" id="quantity">
							                          <option value="1">1</option>
							                          <option value="2" selected>2</option>
							                          <option value="3">3</option>
							                          <option value="4">4</option>
							                          <option value="5">5</option>
							                          <option value="6">6</option>
							                          <option value="7">7</option>
							                          <option value="8">8</option>
							                          <option value="9">9</option>
							                          <option value="10">10</option>
							                          <option value="11">11</option>
							                          <option value="12">12</option>
							                          <option value="13">13</option>
							                          <option value="14">14</option>
							                          <option value="15">15</option>
							                          <option value="16">16</option>
							                          <option value="17">17</option>
							                          <option value="18">18</option>
							                          <option value="19">19</option>
							                          <option value="20">20</option>
							                          <option value="21">21</option>
							                          <option value="22">22</option>
							                          <option value="23">23</option>
							                          <option value="24">24</option>
							                          <option value="25">25</option>
							                        </select>
                                                </div>
                                            </td>
                                            <td class="text-right">₱ 850.00</td>
                                            <td class="text-right">₱ 1,700.00</td>
                                        </tr>
                                        <tr>
                                            <td class="tx-nowrap"><a href="">Pandasal Set 2020</a></td>
                                            <td class="d-none d-sm-table-cell tx-color-03">Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut</td>
                                            <td class="text-center">
                                                <div class="checkout-quantity">
                                                    <select name="quantity" id="quantity">
							                          <option value="1">1</option>
							                          <option value="2">2</option>
							                          <option value="3" selected>3</option>
							                          <option value="4">4</option>
							                          <option value="5">5</option>
							                          <option value="6">6</option>
							                          <option value="7">7</option>
							                          <option value="8">8</option>
							                          <option value="9">9</option>
							                          <option value="10">10</option>
							                          <option value="11">11</option>
							                          <option value="12">12</option>
							                          <option value="13">13</option>
							                          <option value="14">14</option>
							                          <option value="15">15</option>
							                          <option value="16">16</option>
							                          <option value="17">17</option>
							                          <option value="18">18</option>
							                          <option value="19">19</option>
							                          <option value="20">20</option>
							                          <option value="21">21</option>
							                          <option value="22">22</option>
							                          <option value="23">23</option>
							                          <option value="24">24</option>
							                          <option value="25">25</option>
							                        </select>
                                                </div>
                                            </td>
                                            <td class="text-right">₱ 850.00</td>
                                            <td class="text-right">₱ 2,550.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="checkout-bt row justify-content-between">
                                <div class="col-sm-12 col-lg-6 order-2 order-sm-0 mg-t-40 mg-sm-t-0">
                                    <div class="gap-30"></div>
                                    <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Notes</label>
                                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                    </p>
                                </div>
                                <!-- col -->
                                <div class="col-sm-12 col-lg-4 order-1 order-sm-0">
                                    <div class="gap-30"></div>
                                    <ul class="list-unstyled lh-7 pd-r-10">
                                        <li class="d-flex justify-content-between">
                                            <span>Sub-Total</span>
                                            <span>$5,750.00</span>
                                        </li>
                                        <li class="d-flex justify-content-between">
                                            <span>Tax (5%)</span>
                                            <span>$287.50</span>
                                        </li>
                                        <li class="d-flex justify-content-between">
                                            <span>Discount</span>
                                            <span>-$50.00</span>
                                        </li>
                                        <li class="d-flex justify-content-between">
                                            <strong>Total Due</strong>
                                            <strong>$5,987.50</strong>
                                        </li>
                                    </ul>

                                    <!-- <button class="btn btn-block primary-btn mt-2"><span class="fa fa-check-circle fa-icon mr-2"></span> Place Order</button> -->
                                </div>
                                <!-- col -->
                            </div>
                        </div>
                        <div class="checkout-nav">
                            <a class="checkout-back-btn" href=""><span class="lnr lnr-chevron-left"></span> Back</a>
                            <a class="checkout-finish-btn" href="">Place Order <span class="lnr lnr-chevron-right"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('pagejs')
	<script src="{{ asset('theme/stpaul/plugins/responsive-tabs/js/jquery.responsiveTabs.js') }}"></script>
@endsection