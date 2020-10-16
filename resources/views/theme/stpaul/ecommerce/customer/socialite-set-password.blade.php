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
                                <h2 class="lgin-title">Set Password</h2>
                            </div>
                        </div>
                        
                        <div class="gap-10"></div>
                        <div id="signup-form">
                            <form autocomplete="off" method="post" action="{{ route('customer.social_save_password') }}">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <input type="text" name="providerid" value="{{ $socialData->provider_id }}">
                                                <input type="text" name="provider" value="{{ $socialData->provider }}">
                                                <p>First Name *</p>
                                                <input readonly type="text" name="firstname" class="form-control form-input" value="{{ old('firstname',$socialData->firstname) }}">
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-6">
                                                <p>Last Name *</p>
                                                <input readonly type="text" name="lastname" class="form-control form-input" value="{{ old('lastname',$socialData->lastname) }}">
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <p>Email Address *</p>
                                                <input readonly type="email" name="email"  class="form-control form-input" value="{{ old('email',$socialData->email) }}">
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <p>Password *</p>
                                                <input required type="password" name="password" class="form-control form-input @error('password') is-invalid @enderror">
                                                <small>Minimum of eight (8) alphanumeric characters (combination of letters and numbers) with at least one (1) upper case and one (1) special character.</small>
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
                                            <div class="col-lg-6 col-md-7">
                                                <div class="gap-10"></div>
                                                <button type="submit" class="btn btn-md primary-btn btn-block">Submit</button>
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