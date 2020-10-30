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
                                <h2 class="lgin-title">Create your ST PAULS Account</h2>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <p class="text-lg-right text-md-left text-sm-left" id="log-mem">Already member? <span class="text-nowrap"><a href="{{ route('customer-front.login') }}">Login</a> here.</span></p>
                            </div>
                        </div>
                        
                        @if($message = Session::get('error'))
                            <div class="alert alert-danger" role="alert">
                                <span class="fa fa-exclamation"></span> {{ $message }}
                            </div>
                        @endif

                        <div class="gap-10"></div>
                        <div id="signup-form">
                            <form id="signUpForm" autocomplete="off" method="post" action="{{ route('customer-front.customer-sign-up') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="gap-10"></div>
                                        <p class="text-center "><strong>Sign up with your social media account</strong></p>
                                        <div class="gap-20"></div>
                                    </div>
                                    <div class="col-md-2 col-sm-12"></div>
                                    <div class="col-md-4 col-sm-12">
                                        <a href="{{ route('social.oauth', 'facebook') }}" class="btn btn-primary btn-block fb"><i class="fab fa-facebook-f pr-3"></i>Facebook</a>
                                        <div class="gap-20"></div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <a href="{{ route('social.oauth', 'google') }}" class="btn btn-primary btn-block gl"><i class="fab fa-google-plus-g pr-3"></i>Google</a>
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
                                                <input type="hidden" name="provider" value="{{ $socialData->provider }}">
                                                <input type="hidden" name="provider_id" value="{{ $socialData->provider_id }}">
                                                <p>First Name *</p>
                                                <input required type="text" name="firstname" class="form-control form-input @error('firstname') is-invalid @enderror" value="{{ old('firstname',$socialData->firstname) }}">
                                                @hasError(['inputName' => 'firstname'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-6">
                                                <p>Last Name *</p>
                                                <input required type="text" name="lastname" class="form-control form-input @error('lastname') is-invalid @enderror" value="{{ old('lastname',$socialData->lastname) }}">
                                                @hasError(['inputName' => 'lastname'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <p>Email Address *</p>
                                                <input required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" type="email" name="email"  class="form-control form-input  @error('email') is-invalid @enderror" value="{{ old('email',$socialData->email) }}">
                                                @hasError(['inputName' => 'email'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <p>Password *</p>
                                                <input required type="password" name="password" class="form-control form-input @error('password') is-invalid @enderror">
                                                <small class=><b>Minimum of eight (8) alphanumeric characters (combination of letters and numbers) with at least one (1) upper case and one (1) special character.</b></small>
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
                                                <p>Country</p>
                                                <select name="country" id="country" class="form-control form-input">
                                                    <option value="">-- Select Country --</option>
                                                    @foreach(Setting::countries() as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="gap-10"></div>    
                                            </div>

                                            <div class="col-md-12" id="addressdiv" style="display: none;">
                                                <p>Address 1</p>
                                                <input type="text" name="address" id="address" class="form-control form-input" placeholder="Unit No./Building/House No./Street" value="{{ old('address') }}">
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12" id="brgydiv" style="display: none;">
                                                <p>Address 2</p>
                                                <input type="text" name="brgy" id="brgy" class="form-control form-input" placeholder="Name of Subdivision/Barangay" value="{{ old('brgy') }}">
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12" id="provincediv" style="display: none;">
                                                <p>Province</p>
                                                <select name="province" id="province" class="form-control form-input">
                                                    <option value="">-- Select Province --</option>
                                                    @foreach(Setting::provinces() as $province)
                                                    <option value="{{ $province->id }}">{{ $province->province }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-7" id="citydiv" style="display: none;">
                                                <p>City</p>
                                                <select name="city" id="city" class="form-control form-input">
                                                    <option selected disabled value="">-- Select City --</option>
                                                </select>
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-5" id="zipcodediv" style="display: none;">
                                                <p>Zip Code</p>
                                                <input type="text" name="zipcode" class="form-control form-input" value="{{ old('zipcode') }}">
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <p>Telephone Number</p>
                                                <input type="text" name="telno" id="telno" class="form-control form-input" value="{{ old('telno') }}" min="1">
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <p>Mobile Number</p>
                                                <input type="text" name="mobileno" id="mobileno" class="form-control form-input" value="{{ old('mobileno') }}" min="1" maxlength="13">
                                                <div class="gap-10"></div>    
                                            </div>

                                            <div class="col-md-12">
                                                <script src="https://www.google.com/recaptcha/api.js?hl=en" async="" defer="" ></script>
                                                <div class="g-recaptcha" data-sitekey="{{ \Setting::info()->google_recaptcha_sitekey }}"></div>
                                                <label class="control-label text-danger" for="g-recaptcha-response" id="catpchaError" style="display:none;font-size: 14px;"><i class="fa fa-times-circle-o"></i>The Captcha field is required.</label></br>
                                                @if($errors->has('g-recaptcha-response'))
                                                    @foreach($errors->get('g-recaptcha-response') as $message)
                                                        <label class="control-label text-danger" for="g-recaptcha-response"><i class="fa fa-times-circle-o"></i>{{ $message }}</label></br>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="col-lg-6 col-md-7">
                                                <div class="gap-10"></div>
                                                <button type="submit" class="btn btn-md primary-btn btn-block">Sign Up</button>
                                                <div class="gap-10"></div>
                                                {{--<p class="sign-notice">By clicking "SIGN UP", I agree to St. Pauls Privacy Policy</p>--}}
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
    <script>
        /** form validations **/
        $(document).ready(function () {
            //called when key is pressed in textbox
            $("#telno,#mobileno").keypress(function (e) {
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
        $('#signUpForm').submit(function (evt) {
            let recaptcha = $("#g-recaptcha-response").val();
            if (recaptcha === "") {
                evt.preventDefault();
                $('#catpchaError').show();
                return false;
            }
        });

        $(document).ready(function() {
            $('select[name="country"]').on('change', function() {
                var country = $(this).val();

                if(country == 259){
                    $('#addressdiv,#brgydiv,#provincediv,#citydiv,#zipcodediv').css('display','block');
                } else {
                    $('#addressdiv,#brgydiv,#provincediv,#citydiv,#zipcodediv').css('display','none');
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
                            $('select[name="city"]').append('<option selected disabled value="">-- Select City --</option>');
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
