<?php

    ########## ECOMMERCE ROUTES #############
    // Home
        Route::get('/', 'FrontController@home')->name('home');

    // Customer Login & Sign Up

        Route::get('/customer-sign-up', 'EcommerceControllers\CustomerFrontController@sign_up')->name('customer-front.sign-up');
        Route::post('/customer-sign-up', 'EcommerceControllers\CustomerFrontController@customer_sign_up')->name('customer-front.customer-sign-up');
        Route::get('auth/social', 'Auth\SocialiteController@show')->name('social.login');
        Route::get('oauth/{driver}', 'Auth\SocialiteController@redirectToProvider')->name('social.oauth');
        Route::get('oauth/{driver}/callback', 'Auth\SocialiteController@handleProviderCallback')->name('social.callback');

        Route::get('/ajax/deliverable-cities/{id}','EcommerceControllers\CustomerFrontController@ajax_deliverable_cities')->name('ajax.deliverable-cities');

        Route::get('/login', 'EcommerceControllers\CustomerFrontController@login')->name('customer-front.login');
        Route::get('oauth/login/{driver}', 'Auth\SocialiteController@loginRedirectToProvider')->name('social.oauth-login');
        Route::get('oauth/login/{driver}/callback', 'Auth\SocialiteController@loginHandleProviderCallback')->name('social.callback-login');

        Route::post('/login', 'EcommerceControllers\CustomerFrontController@customer_login')->name('customer-front.customer_login');
        Route::get('/customer-logout', 'EcommerceControllers\CustomerFrontController@logout')->name('customer.logout');

        Route::get('/forgot-password', 'EcommerceControllers\EcommerceFrontController@forgot_password')->name('ecommerce.forgot_password');
        Route::post('/forgot-password', 'EcommerceControllers\EcommerceFrontController@sendResetLinkEmail')->name('ecommerce.send_reset_link_email');
        Route::get('/reset-password/{token}', 'EcommerceControllers\EcommerceFrontController@showResetForm')->name('ecommerce.reset_password');
        Route::post('/reset-password', 'EcommerceControllers\EcommerceFrontController@reset')->name('ecommerce.reset_password_post');
        Route::get('/reactive-account', 'EcommerceControllers\EcommerceFrontController@showReactivateForm')->name('ecommerce.reactivate-account');
        Route::post('/reactivate-account', 'EcommerceControllers\EcommerceFrontController@sendReactivateRequest')->name('ecommerce.send_reactivate_request');
    //

    // Cart
        Route::post('cart/add-product','EcommerceControllers\CartController@store')->name('cart.add');
        Route::get('/cart/view', 'EcommerceControllers\CartController@view')->name('cart.front.show');

        Route::post('cart/proceed-checkout','EcommerceControllers\CartController@proceed_checkout')->name('cart.front.proceed_checkout');
        Route::post('cart/remove-product','EcommerceControllers\CartController@remove_product')->name('cart.remove_product');
    //

    // Products
        Route::get('/product-info/{slug}', 'Product\Front\ProductFrontController@show')->name('product.front.show');
        Route::post('/product-buy-now','EcommerceControllers\CartController@buynow')->name('product-buy-now');
        Route::any('/products/{category}','Product\Front\ProductFrontController@product_list')->name('product.front.list');
        Route::any('/search-product', 'Product\Front\ProductFrontController@search_product')->name('product.front.search');
    //

    //
        
        Route::get('/privacy-policy/', 'FrontController@privacy_policy')->name('privacy-policy');
        //Route::post('/payment-notification', 'EcommerceControllers\CartController@receive_data_from_payment_gateway')->name('cart.payment-notification');
    ########## ECOMMERCE ROUTES #############



    Route::group(['middleware' => ['authenticated']], function () {

        // Checkout
            Route::get('/checkout', 'EcommerceControllers\CheckoutController@checkout')->name('cart.front.checkout');

            Route::get('/ajax-city-rates','EcommerceControllers\CheckoutController@ajax_city_rates')->name('ajax.get-city-rates');


            Route::get('/checkout/remove-product','EcommerceControllers\CheckoutController@remove_product')->name('checkout.remove-product');
            Route::post('/temp_save','EcommerceControllers\CartController@save_sales')->name('cart.temp_sales');
        //
        
        // Account Transactions
            Route::get('/account/my-orders', 'EcommerceControllers\SalesFrontController@orders')->name('account-my-orders');
            Route::get('/account/order-info/{id}', 'EcommerceControllers\SalesFrontController@order_info')->name('account-order-info');

            Route::post('/pay-order-globalpay','EcommerceControllers\CartController@globalpay')->name('globalpay-paynow');
            Route::get('/transaction/cancel-order','EcommerceControllers\SalesFrontController@cancel_order')->name('transaction.cancel-order');
            Route::get('/transaction-deliveries','EcommerceControllers\SalesFrontController@display_delivery_history')->name('display-delivery-history');
            Route::any('/transaction-items','EcommerceControllers\SalesFrontController@display_items')->name('display-items');

            Route::get('/order-received/{orderno}', 'EcommerceControllers\SalesFrontController@globalpay_success')->name('globalpay-success');
        //

        // Account Management
            Route::get('/account/manage', 'EcommerceControllers\MyAccountController@manage_account')->name('my-account.manage-account');
            Route::get('/account/change-password', 'EcommerceControllers\MyAccountController@change_password')->name('my-account.change-password');
            Route::post('/update-password', 'EcommerceControllers\MyAccountController@update_password')->name('my-account.update-password');
            Route::post('/account/manage', 'EcommerceControllers\MyAccountController@update_personal_info')->name('my-account.update-personal-info');
            Route::post('/account/manage/update-contact', 'EcommerceControllers\MyAccountController@update_contact_info')->name('my-account.update-contact-info');
            Route::post('/account/manage/update-address', 'EcommerceControllers\MyAccountController@update_address_info')->name('my-account.update-address-info');
        //

            Route::get('/ajax-province-cities/{id}','EcommerceControllers\CustomerFrontController@ajax_cities')->name('ajax.get-cities');

            Route::post('/transactions/pay-order','EcommerceControllers\SalesFrontController@pay_order')->name('pay-order');

            // Route::post('/account/cancel/order', 'EcommerceControllers\SalesFrontController@cancel_order')->name('my-account.cancel-order');
            // Route::get('/account/pay/{id}', 'EcommerceControllers\CartController@pay_again')->name('my-account.pay-again');

            Route::post('product/review/store', 'EcommerceControllers\ProductReviewController@store')->name('product.review.store');

    });



    ########## GLOBAL ROUTE ##########
        Route::get('/ajax-get-cities/{id}','EcommerceControllers\CustomerFrontController@ajax_cities')->name('ajax.get-cities');
        Route::get('myform/ajax/{id}','EcommerceControllers\CheckoutController@ajax_deliverable_cities')->name('ajax.get-deliverable-cities');
    ########## GLOBAL ROUTE ##########
 

        




##############################################################
Route::group(['prefix' => env('APP_PANEL', 'stpaul')], function () {

    Route::get('/', 'Auth\LoginController@showLoginForm')->name('panel.login');
    Auth::routes(['verify' => true]);

    //Route::group(['middleware' => 'admin'], function () {
    Route::group(['middleware' => ['admin','authenticated']], function () {
    


        // Customers
            Route::resource('/admin/customers', 'Settings\CustomerController');
            Route::get('/admin/customer/reactivate-request','Settings\CustomerController@reactivate_request')->name('customers.reactivate-request');
            Route::post('/customer/deactivate', 'Settings\CustomerController@deactivate')->name('customer.deactivate');
            Route::post('/customer/reactivate', 'Settings\CustomerController@reactivate')->name('customer.reactivate');
            Route::post('/customer/activate', 'Settings\CustomerController@activate')->name('customer.activate');
            // Route::get('/admin/customer-search/', 'Settings\CustomerController@search')->name(
            //     'customer.search');
            //Route::get('/admin/customer-profile-log-search/', 'Settings\CustomerController@filter')->name('customer.activity.search');
        //

        // Promos
            Route::resource('/admin/promos', 'Promo\PromoController');
            Route::get('/admin/promo/{id}/{status}', 'Promo\PromoController@update_status')->name('promo.change-status');
            Route::post('/admin/promo-single-delete', 'Promo\PromoController@single_delete')->name('promo.single.delete');
            Route::post('/admin/promo-multiple-change-status','Promo\PromoController@multiple_change_status')->name('promo.multiple.change.status');
            Route::post('/admin/promo-multiple-delete','Promo\PromoController@multiple_delete')->name('promo.multiple.delete');
            Route::get('/admin/promo-restore/{id}', 'Promo\PromoController@restore')->name('promo.restore');
        //

        // Product review
            Route::get('/product-review/', 'EcommerceControllers\ProductReviewController@index')->name('product-review.list');
            Route::post('/product-review/single-approve', 'EcommerceControllers\ProductReviewController@single_approve')->name('product-review.single-approve');
            Route::post('/product-review/single-delete', 'EcommerceControllers\ProductReviewController@single_delete')->name('product-review.single-delete');
            Route::get('/product-review/restore/{id}', 'EcommerceControllers\ProductReviewController@restore')->name('product-review.restore');
            Route::post('/product-review-multiple-delete','EcommerceControllers\ProductReviewController@multiple_delete')->name('product-review.multiple.delete');
            Route::post('/product-review-multiple-approve','EcommerceControllers\ProductReviewController@multiple_approve')->name('product-review.multiple-approve');      
        //

        //Branches
            Route::resource('/admin/branch', 'EcommerceControllers\BranchController');
            Route::post('/admin/branch/single-delete', 'EcommerceControllers\BranchController@single_delete')->name('branch.single.delete');
            Route::get('/admin/branch/restore/{id}', 'EcommerceControllers\BranchController@restore')->name('branch.restore');
            Route::post('/admin/branch/multiple-delete','EcommerceControllers\BranchController@multiple_delete')->name('branch.multiple.delete');
        //

        // Website
            // Route::post('/website-settings/update-ecommerce', 'Settings\WebController@update_ecommerce')->name('website-settings.update-ecommerce');
            Route::post('/website-settings/ecommerce-payment-add-bank','Settings\WebController@add_bank')->name('ecommerce-setting.add-bank');
            Route::post('/website-settings/ecommerce-payment-update-bank','Settings\WebController@update_bank')->name('ecommerce-setting.update-bank');
            Route::post('/website-settings/ecommerce-payment-delete-bank','Settings\WebController@delete_bank')->name('ecommerce-setting.delete-bank');

            Route::post('/website-settings/ecommerce-payment-add-remittance','Settings\WebController@add_remittance')->name('ecommerce-setting.add-remittance');
            Route::post('/website-settings/ecommerce-payment-edit-remittance','Settings\WebController@update_remittance')->name('ecommerce-setting.edit-remittance');
            Route::post('/website-settings/ecommerce-payment-delete-remittance','Settings\WebController@delete_remittance')->name('ecommerce-setting.delete-remittance');

            Route::post('/ecommerce-setting-cash-on-delivery-update','Settings\WebController@cod_update')->name('ecom-setting-cash-on-delivery-update');
            Route::post('/ecommerce-setting-store-pickup-update','Settings\WebController@stp_update')->name('ecom-setting-store-pickup-update');
            Route::post('/ecommerce-setting-same-day-delivery-update','Settings\WebController@sdd_update')->name('ecom-setting-same-day-delivery-update');
            Route::post('/ecommerce-setting-bank-update','Settings\WebController@bank_update')->name('ecom-setting-bank-update');
            Route::post('/ecommerce-setting-remittance-update','Settings\WebController@remittance_update')->name('ecom-setting-remittance-update');

            Route::post('/ecommerce-setting-deactivate-payment-option','Settings\WebController@deactivate_payment_opt')->name('ecommerce-setting.deactivate-payment-opt');
            Route::post('/ecommerce-setting-activate-payment-option','Settings\WebController@activate_payment_opt')->name('ecommerce-setting.activate-payment-opt');

            Route::get('/website-settings/edit', 'Settings\WebController@edit')->name('website-settings.edit');
            Route::put('/website-settings/update', 'Settings\WebController@update')->name('website-settings.update');
            Route::post('/website-settings/update_contacts', 'Settings\WebController@update_contacts')->name('website-settings.update-contacts');
            Route::post('/website-settings/update_media_accounts', 'Settings\WebController@update_media_accounts')->name('website-settings.update-media-accounts');
            Route::post('/website-settings/update_data_privacy', 'Settings\WebController@update_data_privacy')->name('website-settings.update-data-privacy');
            Route::post('/website-settings/remove_logo', 'Settings\WebController@remove_logo')->name('website-settings.remove-logo');
            Route::post('/website-settings/remove_icon', 'Settings\WebController@remove_icon')->name('website-settings.remove-icon');
            Route::post('/website-settings/remove_media', 'Settings\WebController@remove_media')->name('website-settings.remove-media');
        //

        // Delivery Flat Rate
            // Route::resource('/locations', 'DeliverablecitiesController');
            
            // Route::get('/location-rate/{id}/{status}', 'DeliverablecitiesController@update_status')->name('location-rate.change-status');
            // Route::post('/location-multiple-change-status','DeliverablecitiesController@multiple_change_status')->name('location-rate.multiple.change.status');
            // Route::post('/location-rate-single-delete', 'DeliverablecitiesController@single_delete')->name('location.single.delete');
            // Route::get('/location-rate-restore/{id}', 'DeliverablecitiesController@restore')->name('location-rate.restore');
            // Route::post('/location-rate-multiple-delete','DeliverablecitiesController@multiple_delete')->name('location-rate.multiple.delete');

            // Route::post('/locations-enable', 'DeliverablecitiesController@enable')->name('locations.enable');
            // Route::post('/locations-disable', 'DeliverablecitiesController@disable')->name('locations.disable');
            // Route::post('/locations-delete', 'DeliverablecitiesController@delete')->name('locations.delete');
        //

        // Manage Sales Transactions
            Route::resource('/admin/sales-transaction', 'EcommerceControllers\SalesController');

            // Money Transfer
            Route::get('/admin/sales/money-transfer','EcommerceControllers\SalesController@sales_money_transfer')->name('sales-transaction.money-transfer');
            Route::get('/display-payment-details/{id}', 'EcommerceControllers\SalesController@display_payment_details')->name('display.payment-details');
            Route::post('/sales-validate-payment','EcommerceControllers\SalesController@validate_payment')->name('sales.validate-payment');
            // Cash on Delivery
            Route::get('/admin/sales/cash-on-delivery','EcommerceControllers\SalesController@sales_cash_on_delivery')->name('sales-transaction.cash-on-delivery');
            Route::post('/cod-order-response','EcommerceControllers\SalesController@order_response')->name('cod-order-response');
            Route::post('/payment-add-store','EcommerceControllers\SalesController@payment_add_store')->name('payment.add.store');

            // Card Payment
            Route::get('/admin/sales/card-payment','EcommerceControllers\SalesController@sales_card_payment')->name('sales-transaction.card-payment');
            Route::post('/admin/add-shippingfee','EcommerceControllers\SalesController@add_shippingfee')->name('add-shipping-fee');





            // to remove
            // Report
            Route::get('/admin/report/delivery_report/{id}', 'EcommerceControllers\ReportsController@delivery_report')->name('admin.report.delivery_report');

            


            


            Route::post('/admin/sales-transaction/change-status', 'EcommerceControllers\SalesController@change_status')->name('sales-transaction.change.status');
            Route::post('/admin/sales-transaction/{sales}', 'EcommerceControllers\SalesController@quick_update')->name('sales-transaction.quick_update'); // for verification

            Route::get('/admin/sales-transaction/view/{sales}', 'EcommerceControllers\SalesController@show')->name('sales-transaction.view');
            Route::get('/admin/sales-invoice/{sales}', 'EcommerceControllers\SalesController@invoice')->name('sales-transaction.invoice');
            Route::post('/admin/change-delivery-status', 'EcommerceControllers\SalesController@delivery_status')->name('sales-transaction.delivery_status');
            Route::post('/admin/update-delivery-fee', 'EcommerceControllers\SalesController@update_delivery_fee')->name('sales-transaction.update_delivery_fee');
        //

        // Loyalty
            Route::resource('/loyalty', 'Loyalty\LoyaltyController');
            Route::post('/loyalty-approved','Loyalty\LoyaltyController@approved')->name('loyalty.approved');
            Route::post('/loyalty-disapproved','Loyalty\LoyaltyController@disapproved')->name('loyalty.disapproved');
            Route::post('/loyalty-update-discount','Loyalty\LoyaltyController@update_discount')->name('loyalty.update-discount');
        //

        // Discount
            Route::resource('/discounts', 'Loyalty\DiscountController');

            Route::post('/admin/discount-single-delete', 'Loyalty\DiscountController@single_delete')->name('discount.single.delete');
            Route::get('/admin/discount-restore/{id}', 'Loyalty\DiscountController@restore')->name('discount.restore');
            Route::get('/admin/discount/{id}/{status}', 'Loyalty\DiscountController@update_status')->name('discount.change-status');
            Route::post('/admin/discount-multiple-change-status','Loyalty\DiscountController@multiple_change_status')->name('discount.multiple.change.status');
            Route::post('/admin/discount-multiple-delete','Loyalty\DiscountController@multiple_delete')->name('discount.multiple.delete');
        //

        //Reports
            Route::get('/report/sales_list', 'EcommerceControllers\ReportsController@sales_list')->name('report.sales.list');
            Route::get('/report/unpaid_list', 'EcommerceControllers\ReportsController@unpaid_list')->name('report.sales.unpaid');
            Route::get('/report/sales_payments', 'EcommerceControllers\ReportsController@sales_payments')->name('report.sales.payments');
            Route::get('/report/customer_list', 'EcommerceControllers\ReportsController@customer_list')->name('report.customer.list');
            Route::get('/report/product_list', 'EcommerceControllers\ReportsController@product_list')->name('report.product.list');
            Route::get('/report/product_best_selling', 'EcommerceControllers\ReportsController@best_selling')->name('report.product.best-selling');
            Route::get('/report/inventory_list', 'EcommerceControllers\ReportsController@inventory_list')->name('report.inventory.list');
            Route::get('/report/inventory_reorder_point', 'EcommerceControllers\ReportsController@inventory_reorder_point')->name('report.inventory.reorder_point');



            Route::get('/report/stock-card/{id}', 'EcommerceControllers\ReportsController@stock_card')->name('report.product.stockcard');
            Route::get('/admin/report/sales', 'EcommerceControllers\ReportsController@sales')->name('admin.report.sales');
            Route::get('/admin/report/delivery_status', 'EcommerceControllers\ReportsController@delivery_status')->name('admin.report.delivery_status');
            
        //

        // Products
            Route::resource('/admin/products','Product\ProductController');
            Route::get('/products-advance-search', 'Product\ProductController@advance_index')->name('product.index.advance-search');
            Route::post('/admin/product-get-slug','Product\ProductController@get_slug')->name('product.get-slug');
            Route::post('/admin/products/upload', 'Product\ProductController@upload')->name('products.upload');

            Route::get('/admin/product-change-status/{id}/{status}','Product\ProductController@change_status')->name('product.single-change-status');
            Route::post('/admin/product-single-delete', 'Product\ProductController@single_delete')->name('product.single.delete');
            Route::get('/admin/product/restore/{id}', 'Product\ProductController@restore')->name('product.restore');
            Route::post('/admin/product-multiple-change-status','Product\ProductController@multiple_change_status')->name('product.multiple.change.status');
            Route::post('/admin/product-multiple-delete','Product\ProductController@multiple_delete')->name('products.multiple.delete');
        //

        //Inventory
            Route::resource('/inventory','InventoryReceiverHeaderController');
            Route::get('/inventory-download-template','InventoryReceiverHeaderController@download_template')->name('inventory.download.template');
            Route::post('/inventory-upload-template','InventoryReceiverHeaderController@upload_template')->name('inventory.upload.template');
            Route::get('/inventory-post/{id}','InventoryReceiverHeaderController@post')->name('inventory.post');
            Route::get('/inventory-cancel/{id}','InventoryReceiverHeaderController@cancel')->name('inventory.cancel');
            Route::get('/inventory-view/{id}','InventoryReceiverHeaderController@view')->name('inventory.view');
        //

        // Product Categories
            Route::resource('/admin/product-categories','Product\ProductCategoryController');
            Route::post('/admin/product-category-get-slug', 'Product\ProductCategoryController@get_slug')->name('product.category.get-slug');
            Route::post('/admin/product-categories-single-delete', 'Product\ProductCategoryController@single_delete')->name('product.category.single.delete');
            Route::get('/admin/product-category/search', 'Product\ProductCategoryController@search')->name('product.category.search');
            Route::get('/admin/product-category/restore/{id}', 'Product\ProductCategoryController@restore')->name('product.category.restore');
            Route::get('/admin/product-category/{id}/{status}', 'Product\ProductCategoryController@update_status')->name('product.category.change-status');
            Route::post('/admin/product-categories-multiple-change-status','Product\ProductCategoryController@multiple_change_status')->name('product.category.multiple.change.status');
            Route::post('/admin/product-category-multiple-delete','Product\ProductCategoryController@multiple_delete')->name('product.category.multiple.delete');
        //

        //Shipping Fees
            Route::resource('/admin/shippingfee', 'ShippingFee\ShippingfeeController');

            Route::post('/shippingfee-single-delete', 'ShippingFee\ShippingfeeController@single_delete')->name('shippingfee.single.delete');
            Route::post('/shippingfee-multiple-delete','ShippingFee\ShippingfeeController@multiple_delete')->name('shippingfee.multiple.delete');

            Route::get('/admin/shippingfee-manage/{id}', 'ShippingFee\ShippingfeeController@manage')->name('shippingfee.manage');
            Route::post('/admin/shippingfee-location/store', 'ShippingFee\ShippingfeeController@location_store')->name('shippingfee_location.store');
            Route::post('/admin/shippingfee-weight/store', 'ShippingFee\ShippingfeeController@weight_store')->name('shippingfee_weight.store');
            Route::post('/admin/shippingfee-weight/update', 'ShippingFee\ShippingfeeController@weight_update')->name('shippingfee_weight.update');
            Route::post('/shippingfee-weight-single-delete', 'ShippingFee\ShippingfeeController@weight_single_delete')->name('shippingfee-weight.single-delete');
            Route::post('/shippingfee-weight-multiple-delete', 'ShippingFee\ShippingfeeController@weight_multiple_delete')->name('shippingfee-weight.multiple-delete');
            Route::post('/admin/shippingfee-weight/delete_all', 'ShippingFee\ShippingfeeController@weight_delete_all')->name('shippingfee_weight.delete_all');
            Route::post('/admin/shippingfee-weight/upload_csv', 'ShippingFee\ShippingfeeController@weight_upload_csv')->name('shippingfee_weight.upload_csv');
        //

        // Transaction Status
            Route::resource('/admin/transaction-status','Transaction\TransactionStatusController');

            Route::post('/admin/transaction-status-single-delete', 'Transaction\TransactionStatusController@single_delete')->name('transaction_status.single-delete');
            Route::get('/admin/transaction-status/restore/{id}', 'Transaction\TransactionStatusController@restore')->name('transaction_status.restore');
            Route::get('/admin/transaction-status/{id}/{status}', 'Transaction\TransactionStatusController@update_status')->name('transaction_status.change-status');
            Route::post('/admin/transaction-status-multiple-change-status','Transaction\TransactionStatusController@multiple_change_status')->name('transaction_status.multiple.change.status');
            Route::post('/admin/transaction-status-multiple-delete','Transaction\TransactionStatusController@multiple_delete')->name('transaction-status.multiple.delete');

            // Transaction
            Route::resource('/admin/transactions','Transaction\TransactionController');

            Route::get('/transactions/{id}/{status}', 'Transaction\TransactionController@update_status')->name('transactions.change-status');
            Route::post('/transactions-multiple-change-status','Transaction\TransactionController@multiple_change_status')->name('transactions.multiple.change.status');
            Route::post('/transactions-single-delete', 'Transaction\TransactionController@single_delete')->name('transactions.single.delete');
            Route::post('/transactions-multiple-delete','Transaction\TransactionController@multiple_delete')->name('transactions.multiple.delete');
            Route::get('/transactions-restore/{id}', 'Transaction\TransactionController@restore')->name('transactions.restore');


            
            // Migrate products
            Route::post('/product-upload-main','Product\ProductController@upload_main')->name('products.upload.main');
            Route::post('/product-upload-additional','Product\ProductController@upload_additional')->name('products.upload.additional');
            Route::post('/product-upload-featured','Product\ProductController@upload_featured')->name('products.upload.featured');
            Route::post('/product-upload-category','Product\ProductController@upload_category')->name('products.upload.category');










    ####### CMS Standards #######
        //News Frontend
            Route::get('/new-development/', 'News\ArticleFrontController@news_list')->name('news.front.index');
            Route::get('/new-development/{slug}', 'News\ArticleFrontController@news_view')->name('news.front.show');
            Route::get('/new-development/{slug}/print', 'News\ArticleFrontController@news_print')->name('news.front.print');
            Route::post('/new-development/{slug}/share', 'News\ArticleFrontController@news_share')->name('news.front.share');
        //

        //Pages
            Route::resource('/pages', 'PageController');
            Route::get('/pages-advance-search', 'PageController@advance_index')->name('pages.index.advance-search');
            Route::post('/pages/get-slug', 'PageController@get_slug')->name('pages.get_slug');
            Route::put('/pages/{page}/customize', 'PageController@update_customize')->name('pages.update-customize');
            Route::post('/pages-change-status', 'PageController@change_status')->name('pages.change.status');
            Route::post('/pages-delete', 'PageController@delete')->name('pages.delete');
            Route::get('/page-restore/{page}', 'PageController@restore')->name('pages.restore');
        //


            

            Route::get('/admin/sales-transaction/view-payment/{sales}', 'EcommerceControllers\SalesController@view_payment')->name('sales-transaction.view_payment');
            Route::post('/admin/sales-transaction/cancel-product', 'EcommerceControllers\SalesController@cancel_product')->name('sales-transaction.cancel_product');
            Route::get('/sales-advance-search/', 'EcommerceControllers\SalesController@advance_index')->name('admin.sales.list.advance-search');

            Route::get('/admin/sales-transaction/view-payment/{sales}', 'EcommerceControllers\SalesController@view_payment')->name('sales-transaction.view_payment');
            Route::post('/admin/sales-transaction/cancel-product', 'EcommerceControllers\SalesController@cancel_product')->name('sales-transaction.cancel_product');

            
            Route::get('/display-added-payments', 'EcommerceControllers\SalesController@display_payments')->name('display.added-payments');
            Route::get('/display-delivery-history', 'EcommerceControllers\SalesController@display_delivery')->name('display.delivery-history');

            Route::get('/sales/update-payment/{id}','EcommerceControllers\JoborderController@staff_edit_payment')->name('staff-edit-payment');
            Route::post('/sales/update-payment','EcommerceControllers\JoborderController@staff_update_payment')->name('staff-update-payment');


            Route::get('/dashboard', 'DashboardController@index')->name('dashboard');


           
            Route::resource('/admin/sales-transaction', 'EcommerceControllers\SalesController');
            Route::resource('/admin/deliveryrate', 'EcommerceControllers\DeliveryRateController');

        

        

        // Albums
            Route::resource('/albums', 'Banner\AlbumController');
            Route::post('/albums/upload', 'Banner\AlbumController@upload')->name('albums.upload');
            Route::delete('/many/album', 'Banner\AlbumController@destroy_many')->name('albums.destroy_many');
            Route::put('/albums/quick/{album}', 'Banner\AlbumController@quick_update')->name('albums.quick_update');
            Route::post('/albums/{album}/restore', 'Banner\AlbumController@restore')->name('albums.restore');
            Route::post('/albums/banners/{album}', 'Banner\AlbumController@get_album_details')->name('albums.banners');
        //


        // Files
        Route::get('/laravel-filemanager', '\UniSharp\LaravelFilemanager\Controllers\LfmController@show')->name('file-manager.show');
        Route::post('/laravel-filemanager/upload', '\UniSharp\LaravelFilemanager\Controllers\UploadController@upload')->name('file-manager.upload');
        Route::get('/file-manager', 'FileManagerController@index')->name('file-manager.index');


        // Menu
        Route::resource('/menus', 'Menu\MenuController');
        Route::delete('/many/menu', 'Menu\MenuController@destroy_many')->name('menus.destroy_many');
        Route::put('/menus/quick1/{menu}', 'Menu\MenuController@quick_update')->name('menus.quick_update');
        Route::get('/menu-restore/{menu}', 'Menu\MenuController@restore')->name('menus.restore');




        // News
        Route::resource('/news', 'News\ArticleController');
        Route::get('/news-advance-search', 'News\ArticleController@advance_index')->name('news.index.advance-search');
        Route::post('/news-get-slug', 'News\ArticleController@get_slug')->name('news.get-slug');
        Route::post('/news-change-status', 'News\ArticleController@change_status')->name('news.change.status');
        Route::post('/news-delete', 'News\ArticleController@delete')->name('news.delete');
        Route::get('/news-restore/{news}', 'News\ArticleController@restore')->name('news.restore');
        // News Category
        Route::resource('/news-categories', 'News\ArticleCategoryController');
        Route::post('/news-categories/get-slug', 'News\ArticleCategoryController@get_slug')->name('news-categories.get-slug');
        Route::post('/news-categories/delete', 'News\ArticleCategoryController@delete')->name('news-categories.delete');
        Route::get('/news-categories/restore/{id}', 'News\ArticleCategoryController@restore')->name('news-categories.restore');



        // Account
        Route::get('/account/edit', 'Settings\AccountController@edit')->name('account.edit');
        Route::put('/account/update', 'Settings\AccountController@update')->name('account.update');
        Route::put('/account/update_email', 'Settings\AccountController@update_email')->name('account.update-email');
        Route::put('/account/update_password', 'Settings\AccountController@update_password')->name('admin-account.update-password');
        // Audit
        Route::get('/audit-logs', 'Settings\LogsController@index')->name('audit-logs.index');
        // CMS
        //Route::view('/settings/cms/index', 'admin.settings.cms.index')->name('settings.cms')->middleware('checkPermission:admin/settings');


        // Users
        Route::resource('/users', 'Settings\UserController');
        Route::post('/users/deactivate', 'Settings\UserController@deactivate')->name('users.deactivate');
        Route::post('/users/activate', 'Settings\UserController@activate')->name('users.activate');
        Route::get('/user-search/', 'Settings\UserController@search')->name('user.search');
        Route::get('/profile-log-search/', 'Settings\UserController@filter')->name('user.activity.search');

        

        // Roles
        Route::resource('/role', 'Settings\RoleController');
        Route::post('/role/delete','Settings\RoleController@destroy')->name('role.delete');
        Route::get('/role/restore/{id}','Settings\RoleController@restore')->name('role.restore');
        // Access
        Route::resource('/access', 'Settings\AccessController');
        Route::post('/roles_and_permissions/update', 'Settings\AccessController@update_roles_and_permissions')->name('role-permission.update');
        
        //if (env('APP_DEBUG') == "true") {
            // Permission Routes
            Route::resource('/permission', 'Settings\PermissionController');
            Route::get('/permission-search/', 'Settings\PermissionController@search')->name('permission.search');
            Route::post('/permission/destroy', 'Settings\PermissionController@destroy')->name('permission.destroy');
            Route::get('/permission/restore/{id}', 'Settings\PermissionController@restore')->name('permission.restore');
        //}

    });
});

// Pages Frontend
Route::get('/{any}', 'FrontController@page')->where('any', '.*');
