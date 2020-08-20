@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')


@section('content')
<main>
    <section id="default-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-12">
                    <div id="signup-wrapper">
                        <div class="row align-items-center">
                            <div class="col-lg-8 col-md-12">
                                <h2 class="lgin-title">Create your St Pauls Account</h2>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <p class="text-lg-right text-md-left text-sm-left" id="log-mem">Already member? <span class="text-nowrap"><a href="{{ route('customer-front.login') }}">Login</a> here.</span></p>
                            </div>
                        </div>
                        <div class="gap-10"></div>
                        <div id="signup-form">
                            <form method="post" action="{{ route('customer-front.customer-sign-up') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="gap-10"></div>
                                        <p class="text-center "><strong>Sign up with your social media account</strong></p>
                                        <div class="gap-20"></div>
                                    </div>
                                    <div class="col-md-2 col-sm-12"></div>
                                    <div class="col-md-4 col-sm-12">
                                        <a href="{{ url('auth/facebook') }}" class="btn btn-primary btn-block fb"><i class="fa fa-facebook pr-3"></i>Facebook</a>
                                        <div class="gap-20"></div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <button type="" class="btn btn-primary btn-block gl"><i class="fa fa-google-plus pr-3"></i>Google</button>
                                        <div class="gap-20"></div>
                                    </div>
                                    <div class="col-md-2 col-sm-12"></div>
                                    <div class="col-md-12">
                                        <div class="gap-30"></div>
                                        <h6 class="text-dark text-center">OR</h6>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <input type="hidden" name="fbId" value="{{ $fbdata->fb_id }}">
                                                <p>First Name *</p>
                                                <input required type="text" name="fname" class="form-control form-input @error('fname') is-invalid @enderror" value="{{ old('fname',$fbdata->fname) }}">
                                                @hasError(['inputName' => 'fname'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-6">
                                                <p>Last Name *</p>
                                                <input required type="text" name="lname" class="form-control form-input @error('lname') is-invalid @enderror" value="{{ old('lname',$fbdata->lname) }}">
                                                @hasError(['inputName' => 'lname'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <p>Email Address *</p>
                                                <input required type="email" name="email" class="form-control form-input @error('email') is-invalid @enderror" value="{{ old('email',$fbdata->email) }}">
                                                @hasError(['inputName' => 'email'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <p>Password *</p>
                                                <input required type="password" name="password" class="form-control form-input @error('password') is-invalid @enderror">
                                                @hasError(['inputName' => 'password'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <p>Confirm Password *</p>
                                                <input required type="password" name="password_confirmation" class="form-control form-input @error('password_confirmation') is-invalid @enderror">
                                                @hasError(['inputName' => 'password_confirmation'])
                                                @endhasError
                                                <div class="gap-30"></div>    
                                            </div>
                                            <div class="col-md-12"><hr><div class="gap-30"></div></div>
                                            <div class="col-md-12">
                                                <p>Address 1 *</p>
                                                <input required type="text" name="address" class="form-control form-input @error('address') is-invalid @enderror" placeholder="Unit No./Building/House No./Street" value="{{ old('address') }}">
                                                @hasError(['inputName' => 'address'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <p>Subdivision/Barangay *</p>
                                                <input required type="text" name="brgy" class="form-control form-input @error('brgy') is-invalid @enderror" placeholder="Name of Subdivision/Barangay" value="{{ old('brgy') }}">
                                                @hasError(['inputName' => 'brgy'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <p>Province *</p>
                                                <select name="province" id="province" class="form-control form-input  @error('province') is-invalid @enderror">
                                                    <option value="">-- Select --</option>
                                                    @foreach($provinces as $province)
                                                    <option value="{{ $province->id }}">{{ $province->province }}</option>
                                                    @endforeach
                                                </select>
                                                @hasError(['inputName' => 'province'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-7">
                                                <p>City *</p>
                                                <select name="city" id="city" class="form-control form-input  @error('city') is-invalid @enderror">
                                                </select>
                                                @hasError(['inputName' => 'city'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-5">
                                                <p>Zip Code *</p>
                                                <input required type="number" name="zipcode" class="form-control form-input @error('zipcode') is-invalid @enderror" value="{{ old('zipcode') }}">
                                                @hasError(['inputName' => 'zipcode'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <p>Telephone Number</p>
                                                <input type="number" name="telno" class="form-control form-input" value="{{ old('telno') }}">
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <p>Mobile Number *</p>
                                                <input required type="number" name="mobileno" class="form-control form-input @error('mobileno') is-invalid @enderror" value="{{ old('mobileno') }}">
                                                @hasError(['inputName' => 'mobileno'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <div class="gap-20"></div>
                                                <div id="chxbx">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="customCheck" name="subscriber">
                                                        <label class="custom-control-label" for="customCheck">I want to receive exclusive offers and promotions from St Pauls.</label>
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="col-lg-6 col-md-7">
                                                <div class="gap-10"></div>
                                                <button type="submit" class="btn btn-md primary-btn btn-block">Sign Up</button>
                                                <div class="gap-10"></div>
                                                <p class="sign-notice">By clicking "SIGN UP", I agree to St. Pauls Privacy Policy</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('pagejs')
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
