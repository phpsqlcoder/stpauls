@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/responsive-tabs/css/responsive-tabs.css') }}" />
@endsection

@section('content')
<main>
    <section id="default-wrapper">
        <div class="container">
            <div class="row">
                <div id="col1" class="col-lg-3">
                    @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.account-page-options')
                </div>
                <div id="col2" class="col-lg-9">
                    <nav class="rd-navbar">
                        <div class="rd-navbar-listing-toggle rd-navbar-static--hidden toggle-original" data-rd-navbar-toggle=".listing-filter-wrap"><span class="lnr lnr-list"></span> Options</div>
                    </nav>
                    <div class="article-content">
                        <h3 class="subpage-heading">{{ $page->name }}</h3>
                        <div>
                    <nav>
                        <div class="nav nav-tabs account-tabs" id="nav-tab" role="tablist">
                            <a href="#tab-1" class="nav-item nav-link @if(session()->has('tabname')) @else active @endif" id="nav-home-tab" data-toggle="tab" role="tab" aria-controls="nav-home" aria-selected="true">Personal Info</a>
                            <a href="#tab-2" class="nav-item nav-link @if(session()->has('tabname') && session('tabname') == 'contact-information') active @endif" id="nav-profile-tab" data-toggle="tab" role="tab" aria-controls="nav-profile" aria-selected="false">Contact Info</a>
                            <a href="#tab-3" class="nav-item nav-link @if(session()->has('tabname') && session('tabname') == 'my-address') show active @endif" id="nav-contact-tab" data-toggle="tab" role="tab" aria-controls="nav-contact" aria-selected="false">Address</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        {{-- Pesonal Info --}}
                        <div class="tab-pane fade show login-forms @if(session()->has('tabname')) @else active @endif" id="tab-1" role="tabpanel" aria-labelledby="nav-home-tab">
                            <br>
                            <h4>Personal Information</h4>
                            <hr>
                            <div class="gap-10"></div>
                            <div class="form-style-alt">
                                @if (Session::has('success-personal'))
                                    <div class="alert alert-success" role="alert"><span class="fa fa-info-circle"></span>{{ Session::get('success-personal') }}</div>
                                @endif
                                <form autocomplete="off" method="post" class="row" action="{{ route('my-account.update-personal-info') }}">
                                    @csrf                                          
                                        <div class="col-lg-6">
                                            <label>First Name *</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname" value="{{ old('firstname', $customer->firstname) }}">
                                                @hasError(['inputName' => 'firstname'])
                                                @endhasError
                                            </div>
                                       
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Last Name *</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ old('lastname', $customer->lastname) }}">
                                                @hasError(['inputName' => 'lastname'])
                                                @endhasError
                                            </div>
                                      
                                        </div>                                           
                                    <div class="col-lg-6">
                                        <button type="submit" class="btn btn-md btn-success">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Contact Info --}}
                        <div class="tab-pane fade login-forms @if(session()->has('tabname') && session('tabname') == 'contact-information') show active @endif" id="tab-2" role="tabpanel" aria-labelledby="nav-profile-tab"><br>
                            <h4>Contact Information</h4>
                            <hr>
                            <div class="gap-10"></div>
                            <div class="form-style-alt">
                                @if (Session::has('success-contact'))
                                    <div class="alert alert-success" role="alert"><span class="fa fa-info-circle"></span>{{ Session::get('success-contact') }}</div>
                                @endif
                                <form method="post" class="row" action="{{ route('my-account.update-contact-info') }}">
                                    @csrf
                                    <div class="col-lg-6">
                                        <label>Mobile Number *</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ old('mobile', $customer->details->mobile) }}" maxlength="13">
                                            @hasError(['inputName' => 'mobile'])
                                            @endhasError
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label>Telephone Number </label>
                                        <div class="form-group">
                                            <input type="text" class="form-control @error('telno') is-invalid @enderror" id="telno" name="telno" value="{{ old('telno', $customer->details->telno) }}">
                                            @hasError(['inputName' => 'telno'])
                                            @endhasError
                                        </div>
                                    </div>  
                                    
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-md btn-success">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane fade login-forms @if(session()->has('tabname') && session('tabname') == 'my-address') show active @endif" id="tab-3" role="tabpanel" aria-labelledby="nav-contact-tab"><br>
                            <form autocomplete="off" method="post" action="{{ route('my-account.update-address-info') }}">
                                @csrf
                                <h4>Delivery Address</h4>
                                <hr>
                                <div class="gap-10"></div>
                                <div class="address-card">
                                    <div class="form-style-alt"> 
                                        @if (Session::has('success-address'))
                                            <div class="alert alert-success" role="alert"><span class="fa fa-info-circle"></span>{{ Session::get('success-address') }}</div>
                                        @endif                                              
                                        
                                        <div class="gap-10"></div>

                                        <div class="form-group form-wrap">
                                            <label>Country *</label>
                                            <select name="country" id="country" class="form-control @error('country') is-invalid @enderror">
                                                @foreach(Setting::countries() as $country)
                                                <option @if($customer->details->country == $country->id) selected @endif value="{{$country->id}}">{{$country->name}}</option>
                                                @endforeach
                                            </select>
                                            @hasError(['inputName' => 'country'])
                                            @endhasError
                                        </div> 

                                        <div id="intl_address" style="display: @if($customer->details->country <> 259 && $customer->details->country != '') block @else none @endif">
                                           <div class="form-group form-wrap">
                                                <label>Billing Address *</label>
                                                <textarea @if($customer->details->country <> 259) required @endif name="intl_address" class="form-control" rows="3" id="intlAddress">{{ old('intl_address', $customer->details->intl_address) }}</textarea>
                                            </div> 
                                        </div>

                                        <div id="local_address" style="display: @if($customer->details->country == 259) block @else none @endif">
                                            <div class="form-group form-wrap">
                                                <label>Main Address *</label>
                                                <input maxlength="40" @if($customer->details->country == 259) required @endif type="text" class="form-control" id="address" name="address" placeholder="Unit No./Building/House No./Street" value="{{ old('address', $customer->details->address) }}"/>
                                            </div>
                                       
                                            <div class="form-group form-wrap">
                                                <label>Alternative Address</label>
                                                <input @if($customer->details->country == 259) required @endif type="text" class="form-control" id="barangay" name="barangay" placeholder="Subd/Brgy" value="{{ old('barangay', $customer->details->barangay) }}"/>      
                                                @hasError(['inputName' => 'barangay'])
                                                @endhasError
                                            </div>    
                                            
                                            <div class="form-group form-wrap">
                                                <label>Province *</label>
                                                <select @if($customer->details->country ==259) required @endif name="province" id="province" class="form-control">
                                                    <option value="">-- Select Province --</option>
                                                    @foreach($provinces as $province)
                                                    <option @if($customer->details->province == $province->id) selected @endif value="{{$province->id}}">{{$province->province}}</option>
                                                    @endforeach
                                                </select>
                                                @hasError(['inputName' => 'province'])
                                                @endhasError
                                            </div> 
                                            @php
                                                $cities = \App\Cities::where('province',$customer->details->province)->orderBy('city','asc')->get();
                                            @endphp
                                            <div class="form-group form-wrap">
                                                <label>City *</label>
                                                <select @if($customer->details->country == 259) required @endif name="city" id="city" class="form-control @error('city') is-invalid @enderror">
                                                    @foreach($cities as $city)
                                                    <option @if($customer->details->city == $city->id) selected @endif value="{{$city->id}}">{{$city->city}}</option>
                                                    @endforeach
                                                </select>    
                                                @hasError(['inputName' => 'city'])
                                                @endhasError
                                            </div>    
                                        </div>
                                        <div class="form-group form-wrap">
                                            <label>Zip Code</label>
                                            <input type="text" class="form-control @error('zipcode') is-invalid @enderror" id="zipcode" name="zipcode" value="{{ old('zipcode', $customer->details->zipcode) }}"/>
                                            @hasError(['inputName' => 'zipcode'])
                                            @endhasError
                                        </div>
                                    </div>
                                </div>
                                <div class="gap-10"></div>
                                <button type="submit" class="btn btn-md btn-success">Save</button>
                            </form>
                        </div>
                    </div>
                    <div class="gap-20"></div>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('pagejs')
    <script>
        /** form validations **/
        $(document).ready(function () {
            //called when key is pressed in textbox
            $("#telno,#mobile").keypress(function (e) {
                //if the letter is not digit then display error and don't type anything
                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });
        });  
    </script>
@endsection

@section('customjs')
    <script>
        $(document).ready(function() {
            $('select[name="country"]').on('change', function() {
                var country = $(this).val();
                if(country == 259){
                    $('#local_address').css('display','block');
                    $('#intl_address').css('display','none');

                    $('#address').attr('required',true);
                    $('#barangay').attr('required',true);
                    $('#province').attr('required',true);
                    $('#city').attr('required',true);
                    $('#intlAddress').attr('required',false);
                } else {
                    $('#intlAddress').attr('required',true);
                    $('#address').attr('required',false);
                    $('#barangay').attr('required',false);
                    $('#province').attr('required',false);
                    $('#city').attr('required',false);

                    $('#intl_address').css('display','block');
                    $('#local_address').css('display','none');
                }
            });

            $('select[name="province"]').on('change', function() {
                var provinceID = $(this).val();
                if(provinceID) {

                    var url = "{{ route('ajax.get-cities', ':provinceID') }}";
                    url = url.replace(':provinceID',provinceID);

                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                            $('select[name="city"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="city"]').append('<option value="'+value.id+'">'+value.city+'</option>');
                            });
                        }
                    });
                } else {
                    $('select[name="city"]').empty();
                }
            });
        });
    </script>
@endsection
