@if(Route::current()->getName() == 'customer-front.login'
 || Route::current()->getName() == 'customer-front.sign-up'
 || Route::current()->getName() == 'ecommerce.forgot_password'
 || Route::current()->getName() == 'ecommerce.reactivate-account'
 || Route::current()->getName() == 'cart.front.show'
 || Route::current()->getName() == 'product.front.list'
 || Route::current()->getName() == 'product.front.show'
 || Route::current()->getName() == 'cart.front.checkout'
 || Route::current()->getName() == 'order.payment-received'
 || Route::current()->getName() == 'payment.failed'
 || Route::current()->getName() == 'order.received'
 || Route::current()->getName() == 'my-account.manage-account'
 || Route::current()->getName() == 'my-account.change-password'
 || Route::current()->getName() == 'account-my-orders'
 || Route::current()->getName() == 'account.manage-wishlist'
 || Route::current()->getName() == 'front.request-a-title'
 || Route::current()->getName() == 'front.branches'
 || Route::current()->getName() == 'account-order-info')

@else

	@if(isset($page) && $page->album && count($page->album->banners) > 1 && $page->album->is_main_banner())
	    @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.home-slider')
	@elseif(isset($page) && $page->album && count($page->album->banners) > 1 && !$page->album->is_main_banner())
	    @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.page-slider')
	@elseif(isset($page) && (isset($page->album->banners) && (count($page->album->banners) == 1 && !$page->album->is_main_banner()) || !empty($page->image_url)))
	    @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.page-banner')
	@else
	    @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.no-banner')
	@endif

@endif