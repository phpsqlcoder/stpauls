@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/responsive-tabs/css/responsive-tabs.css') }}" />
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">                          
                <div class="desk-cat d-none d-lg-block">
                    <div class="quick-nav">
                        <h3 class="catalog-title">My Account</h3>
                        <ul>
                            <li class="active"><a href="{{ route('my-account.manage-account')}}">Manage Account</a></li>
                            <li><a href="{{ route('my-account.update-password') }}">Change Password</a></li>
                            <li><a href="{{ route('account-transactions') }}">Manage Orders</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div>
                    <nav>
                        <div class="nav nav-tabs account-tabs" id="nav-tab" role="tablist">
                            <a href="#tab-1" class="nav-item nav-link @if(session()->has('tabname')) @else active @endif" id="nav-home-tab" data-toggle="tab" role="tab" aria-controls="nav-home" aria-selected="true">Personal Info</a>
                            <a href="#tab-2" class="nav-item nav-link @if(session()->has('tabname') && session('tabname') == 'contact-information') active @endif" id="nav-profile-tab" data-toggle="tab" role="tab" aria-controls="nav-profile" aria-selected="false">Contact Info</a>
                            <a href="#tab-3" class="nav-item nav-link @if(session()->has('tabname') && session('tabname') == 'my-address') show active @endif" id="nav-contact-tab" data-toggle="tab" role="tab" aria-controls="nav-contact" aria-selected="false">Address</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
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
                                            <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ old('mobile', $customer->mobile) }}">
                                            @hasError(['inputName' => 'mobile'])
                                            @endhasError
                                        </div>
                                      
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Telephone Number </label>
                                        <div class="form-group">
                                            <input type="text" class="form-control @error('telno') is-invalid @enderror" id="telno" name="telno" value="{{ old('telno', $customer->telno) }}">
                                            @hasError(['inputName' => 'telno'])
                                            @endhasError
                                        </div>
                                        <div class="gap-20"></div>
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
                                            <label>Address Line 1 *</label>
                                            <input required type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Unit No./Building/House No./Street" value="{{ old('address', $customer->address) }}"/>
                                            @hasError(['inputName' => 'address'])
                                            @endhasError
                                        </div>
                                       
                                   
                                        <div class="form-group form-wrap">
                                            <label>Address Line 2 *</label>
                                            <input required type="text" class="form-control @error('barangay') is-invalid @enderror" id="barangay" name="barangay" placeholder="Subd/Brgy" value="{{ old('barangay', $customer->barangay) }}"/>      
                                            @hasError(['inputName' => 'barangay'])
                                            @endhasError
                                        </div>    
                                        
                                        <div class="form-group form-wrap">
                                            <label>Province *</label>
                                            <select required name="province" id="province" class="form-control @error('province') is-invalid @enderror">
                                                @foreach($provinces as $province)
                                                <option @if($customer->province == $province->id) selected @endif value="{{$province->id}}">{{$province->province}}</option>
                                                @endforeach
                                            </select>
                                            @hasError(['inputName' => 'province'])
                                            @endhasError
                                        </div> 
                                        @php
                                            $cities = \App\Cities::where('province',$customer->province)->orderBy('city','asc')->get();
                                        @endphp
                                        <div class="form-group form-wrap">
                                            <label>City *</label>
                                            <select required name="city" id="city" class="form-control @error('city') is-invalid @enderror">
                                                @foreach($cities as $city)
                                                <option @if($customer->city == $city->id) selected @endif value="{{$city->id}}">{{$city->city}}</option>
                                                @endforeach
                                            </select>    
                                            @hasError(['inputName' => 'city'])
                                            @endhasError
                                        </div>    
                                                                     
                                         <div class="form-group form-wrap">
                                            <label>Zip </label>
                                            <input type="text" class="form-control @error('zipcode') is-invalid @enderror" id="zipcode" name="zipcode" value="{{ old('zipcode', $customer->zipcode) }}"/>                                                  
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
</section>
@endsection

@section('customjs')
    <script>
        $(document).ready(function() {
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
