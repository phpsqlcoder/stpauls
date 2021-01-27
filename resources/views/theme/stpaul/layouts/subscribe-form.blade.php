<section id="home-quote">
    <div class="container">
        <div class="row align-center">
            <div class="col-lg-4 col-md-12 col-sm-12">
                <ul class="list-inline-sm">
                    <li><img src="{{ asset('theme/stpaul/images/misc/email.png') }}" alt=""></li>
                    <li>
                        <h2 class="quote-title">Receive our news by email!</h2>
                    </li>
                </ul>
            </div>
            <div class="col-lg-8 col-md-12 col-sm-12">
                <form id="subscribeForm">
                    <div class="form-row form-style align-center">
                        <div class="col-lg-3">
                            <div class="gap-10"></div>
                            <div class="form-wrap">
                                <label class="form-label" for="name">First Name</label>
                                <input id="firs_name" type="text" class="form-control form-input" id="first_name" name="first_name" autocomplete="off" />
                            </div>
                            <div class="gap-10"></div>
                        </div>
                        <div class="col-lg-3">
                            <div class="gap-10"></div>
                            <div class="form-wrap">
                                <label class="form-label" for="name">Last Name</label>
                                <input id="last_name" type="text" class="form-control form-input" id="last_name" name="last_name" autocomplete="off" />
                            </div>
                            <div class="gap-10"></div>
                        </div>
                        <div class="col-lg-4">
                            <div class="gap-10"></div>
                            <div class="form-wrap">
                                <input id="email" type="email" class="form-control form-input" name="email" autocomplete="off" />
                                <label class="form-label" for="email">Email Address</label>
                            </div>
                            <div class="gap-10"></div>
                        </div>
                        <div class="col-lg-2">
                            <div class="gap-10"></div>
                            <button type="submit" class="btn btn-md primary-btn" id="subscribeBtn"><span id="spanSubscribe">Subscribe</span></button>
                            <div class="gap-10"></div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</section>