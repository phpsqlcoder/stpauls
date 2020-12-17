@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/legande/plugins/responsive-tabs/css/responsive-tabs.css') }}" />
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
                        @if(Session::has('success-change-password'))
                            <div class="alert alert-success" role="alert"><span class="fa fa-info-circle"></span>{{ Session::get('success-change-password') }}</div>
                        @endif
                        <div class="form-style fs-sm">
                            <form class="form message-form" role="form" autocomplete="off" action="{{ route('my-account.update-password') }}" method="post">
                                @csrf
                                @if (Session::has('success'))
                                    <div class="alert alert-success" role="alert"><span class="fa fa-info-circle"></span>{{ Session::get('success') }}</div>
                                @endif
                                <div class="form-group">
                                    <label for="inputPasswordOld">Current Password</label>
                                    <div class="form-group position-relative w-auto col-md-6 p-0">
                                        <input type="password" class="form-control form-input password-field" name="current_password" required id="inputPasswordOld" >
                                    </div>
                                    @if(Session::has('error-change-password'))
                                    <p class="text-danger"><small><strong>The password is incorrect.</strong></small></p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="inputPasswordNew">New Password</label>
                                    <div class="form-group position-relative w-auto col-md-6 p-0">
                                        <input type="password" class="form-control form-input password-field @error('password') is-invalid @enderror" name="password" required id="inputPasswordNew" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Password should have a minimum of 8 alphanumeric characters and has at least 1 upper case and 1 special character.">
                                    </div>
                                    @hasError(['inputName' => 'password'])
                                    @endhasError

                                </div>
                                <div class="form-group">
                                    <label for="inputPasswordNewVerify">Verify Password</label>
                                    <div class="form-group position-relative w-auto col-md-6 p-0">
                                        <input type="password" class="form-control form-input password-field @error('confirm_password') is-invalid @enderror" name="confirm_password" required id="inputPasswordNewVerify">
                                    </div>
                                    @hasError(['inputName' => 'confirm_password'])
                                    @endhasError
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn primary-btn more2">Save</button>
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

