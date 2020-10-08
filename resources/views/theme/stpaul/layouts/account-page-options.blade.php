<nav class="rd-navbar rd-navbar-listing">
    <div class="listing-filter-wrap">
        <div class="rd-navbar-listing-close-toggle rd-navbar-static--hidden toggle-original"><span class="lnr lnr-cross"></span> Close</div>
        <h3 class="subpage-heading">Options</h3>
        <div class="side-menu">
            <ul>
                <li @if(Route::current()->getName() == 'my-account.manage-account') class="active" @endif ><a href="{{ route('my-account.manage-account') }}">Account Information</a></li>
                <li @if(Route::current()->getName() == 'my-account.change-password') class="active" @endif><a href="{{ route('my-account.change-password') }}">Change Password</a></li>
                <li @if(Route::current()->getName() == 'account-my-orders') class="active" @endif><a href="{{ route('account-my-orders') }}">My Orders</a></li>
                <li><a href="{{ route('customer.logout') }}">Log Out</a></li>
            </ul>
        </div>
    </div>
</nav>