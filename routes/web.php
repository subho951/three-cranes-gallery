<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\PayPalController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('/', function () {
//     return view('welcome');
// });
/* Front Panel */
    // before login
        Route::match(['get', 'post'], '/', 'App\Http\Controllers\FrontController@home');
    // before login
    // after login

    // after login
/* Front Panel */
/* Admin Panel */
    Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function(){
        Route::match(['get', 'post'], '/', 'UserController@login');
        Route::match(['get','post'],'/forgot-password', 'UserController@forgotPassword');
        Route::match(['get','post'],'/validateOtp/{id}', 'UserController@validateOtp');
        Route::match(['get','post'],'/changePassword/{id}', 'UserController@changePassword');
        Route::group(['middleware' => ['admin']], function(){
            Route::get('dashboard', 'UserController@dashboard');
            Route::get('dashboard-filter', 'UserController@dashboardFilter');
            Route::get('logout', 'UserController@logout');
            Route::get('email-logs', 'UserController@emailLogs');
            Route::match(['get','post'],'/email-logs/details/{id}', 'UserController@emailLogsDetails');
            Route::get('login-logs', 'UserController@loginLogs');
            // Route::match(['get','post'], 'update-product-view', 'UserController@update_product_view')->name('updateProductView');;
            Route::match(['get', 'post'], 'image-gallery', 'UserController@imageGallery');
            Route::get('dashboard-new', 'UserController@dashboardNew');
            Route::get('stats', 'UserController@stats');
            Route::get('message', 'UserController@message');
            Route::get('user-all-activity', 'UserController@userAllActivity');
            /* setting */
                Route::get('settings', 'UserController@settings');
                Route::post('profile-settings', 'UserController@profile_settings');
                Route::post('general-settings', 'UserController@general_settings');
                Route::post('change-password', 'UserController@change_password');
                Route::post('email-settings', 'UserController@email_settings');
                Route::post('email-template', 'UserController@email_template');
                Route::post('sms-settings', 'UserController@sms_settings');
                Route::post('footer-settings', 'UserController@footer_settings');
                Route::post('seo-settings', 'UserController@seo_settings');
                Route::post('payment-settings', 'UserController@payment_settings');
                Route::post('shipping-settings', 'UserController@shipping_settings');
          		Route::get('test-email', 'UserController@testEmail');
            /* setting */
            /* access & permission */
                /* module */
                    Route::get('module/list', 'ModuleController@list');
                    Route::match(['get', 'post'], 'module/add', 'ModuleController@add');
                    Route::match(['get', 'post'], 'module/edit/{id}', 'ModuleController@edit');
                    Route::get('module/delete/{id}', 'ModuleController@delete');
                    Route::get('module/change-status/{id}', 'ModuleController@change_status');
                /* module */
                /* sub users */
                    Route::get('sub-user/list', 'SubUserController@list');
                    Route::match(['get', 'post'], 'sub-user/add', 'SubUserController@add');
                    Route::match(['get', 'post'], 'sub-user/edit/{id}', 'SubUserController@edit');
                    Route::get('sub-user/delete/{id}', 'SubUserController@delete');
                    Route::get('sub-user/change-status/{id}', 'SubUserController@change_status');
                /* sub users */
                /* give access */
                    Route::get('access/list', 'AccessController@list');
                    Route::match(['get', 'post'], 'access/add', 'AccessController@add');
                    Route::match(['get', 'post'], 'access/edit/{id}', 'AccessController@edit');
                    Route::get('access/delete/{id}', 'AccessController@delete');
                    Route::get('access/change-status/{id}', 'AccessController@change_status');
                /* give access */
            /* access & permission */
            /* product */
                /* unit */
                    Route::get('unit/list', 'UnitController@list');
                    Route::match(['get', 'post'], 'unit/add', 'UnitController@add');
                    Route::match(['get', 'post'], 'unit/edit/{id}', 'UnitController@edit');
                    Route::get('unit/delete/{id}', 'UnitController@delete');
                    Route::get('unit/change-status/{id}', 'UnitController@change_status');
                /* unit */
                /* shop produce */
                    Route::get('shop-produce/list', 'ShopProduceController@list');
                    Route::match(['get', 'post'], 'shop-produce/add', 'ShopProduceController@add');
                    Route::match(['get', 'post'], 'shop-produce/edit/{id}', 'ShopProduceController@edit');
                    Route::get('shop-produce/delete/{id}', 'ShopProduceController@delete');
                    Route::get('shop-produce/change-status/{id}', 'ShopProduceController@change_status');
                /* shop produce */
                /* tools used */
                    Route::get('tools-used/list', 'ToolsUsedController@list');
                    Route::match(['get', 'post'], 'tools-used/add', 'ToolsUsedController@add');
                    Route::match(['get', 'post'], 'tools-used/edit/{id}', 'ToolsUsedController@edit');
                    Route::get('tools-used/delete/{id}', 'ToolsUsedController@delete');
                    Route::get('tools-used/change-status/{id}', 'ToolsUsedController@change_status');
                /* tools used */
                /* materials */
                    Route::get('materials/list', 'MaterialController@list');
                    Route::match(['get', 'post'], 'materials/add', 'MaterialController@add');
                    Route::match(['get', 'post'], 'materials/edit/{id}', 'MaterialController@edit');
                    Route::get('materials/delete/{id}', 'MaterialController@delete');
                    Route::get('materials/change-status/{id}', 'MaterialController@change_status');
                /* materials */
                /* return policy */
                    Route::get('return-policy/list', 'ReturnPolicyController@list');
                    Route::match(['get', 'post'], 'return-policy/add', 'ReturnPolicyController@add');
                    Route::match(['get', 'post'], 'return-policy/edit/{id}', 'ReturnPolicyController@edit');
                    Route::get('return-policy/delete/{id}', 'ReturnPolicyController@delete');
                    Route::get('return-policy/change-status/{id}', 'ReturnPolicyController@change_status');
                /* return policy */
                /* parent category */
                    Route::get('parent-category/list', 'ParentCategoryController@list');
                    Route::match(['get', 'post'], 'parent-category/add', 'ParentCategoryController@add');
                    Route::match(['get', 'post'], 'parent-category/edit/{id}', 'ParentCategoryController@edit');
                    Route::get('parent-category/delete/{id}', 'ParentCategoryController@delete');
                    Route::get('parent-category/change-status/{id}', 'ParentCategoryController@change_status');
                    Route::get('parent-category/get-main-category-products/{id}', 'ParentCategoryController@get_main_category_products');
                /* parent category */
                /* sub category */
                    Route::get('sub-category/list', 'SubCategoryController@list');
                    Route::match(['get', 'post'], 'sub-category/add', 'SubCategoryController@add');
                    Route::match(['get', 'post'], 'sub-category/edit/{id}', 'SubCategoryController@edit');
                    Route::get('sub-category/delete/{id}', 'SubCategoryController@delete');
                    Route::get('sub-category/change-status/{id}', 'SubCategoryController@change_status');
                    Route::get('sub-category/get-sub-category-products/{id}', 'SubCategoryController@get_sub_category_products');
                /* sub category */
                /* attribute */
                    Route::get('attribute/list', 'AttributeController@list');
                    Route::match(['get', 'post'], 'attribute/add', 'AttributeController@add');
                    Route::match(['get', 'post'], 'attribute/edit/{id}', 'AttributeController@edit');
                    Route::get('attribute/delete/{id}', 'AttributeController@delete');
                    Route::get('attribute/change-status/{id}', 'AttributeController@change_status');
                    Route::get('get-attr-values', 'AttributeController@getAttrValues');
                /* attribute */
                /* product */
                    Route::get('product/list', 'ProductController@list');
                    Route::post('product/list', 'ProductController@list');
                    Route::match(['get', 'post'], 'product/add', 'ProductController@add');
                    Route::match(['get', 'post'], 'product/edit/{id}', 'ProductController@edit');
                    Route::get('product/copy/{id}', 'ProductController@copy');
                    Route::get('product/delete/{id}', 'ProductController@delete');
                    Route::get('product/change-status/{id}', 'ProductController@change_status');
                    Route::get('product/change-feature/{id}', 'ProductController@change_feature');
                    Route::get('product/delete-single-image/{id1}/{id2}', 'ProductController@deleteSingleImage');
                    Route::post('product/get-product-attribute', 'ProductController@getProductAttribute');
                    Route::post('product/update-product-view', 'ProductController@updateProductView');
                    Route::match(['get', 'post'], 'product/product-sorting', 'ProductController@productSorting');
                    Route::match(['get', 'post'], 'product/generate-product-variation', 'ProductController@generateProductVariation');
                    Route::match(['get', 'post'], 'product/generate-product-variation-2', 'ProductController@generateProductVariation2');
                    Route::match(['get', 'post'], 'product/product-filter', 'ProductController@productFilter');
                    Route::match(['get', 'post'], 'product/product-category', 'ProductController@productCategory');
                    Route::match(['get', 'post'], 'product/update-cover-image', 'ProductController@updateCoverImage');
                /* product */
                /* variant */
                    Route::get('variant/list/{id1}', 'VariantController@list');
                    Route::match(['get', 'post'], 'variant/add/{id1}', 'VariantController@add');
                    Route::match(['get', 'post'], 'variant/edit/{id1}/{id2}', 'VariantController@edit');
                    Route::get('variant/delete/{id1}/{id2}', 'VariantController@delete');
                    Route::get('variant/change-status/{id1}/{id2}', 'VariantController@change_status');
                /* variant */
            /* product */
            /* Order Management */
                Route::get('orders/list/{id1}/{id2}', 'OrderController@list');
                Route::get('orders/print-invoice/{id1}', 'OrderController@printInvoice');
                Route::get('orders/change-status/{id1}/{id2}', 'OrderController@change_status');
                Route::get('orders/status-update/{id1}/{id2}', 'OrderController@status_update');
                Route::get('orders/order-details/{id1}', 'OrderController@orderDetails');
            /* Order Management */
            /* payment methods */
                Route::get('payment-method/list', 'PaymentMethodController@list');
                Route::match(['get', 'post'], 'payment-method/add', 'PaymentMethodController@add');
                Route::match(['get', 'post'], 'payment-method/edit/{id1}', 'PaymentMethodController@edit');
                Route::get('payment-method/delete/{id1}', 'PaymentMethodController@delete');
                Route::get('payment-method/change-status/{id1}', 'PaymentMethodController@change_status');
            /* payment methods */
            /* coupon */
                Route::get('coupon/list', 'CouponController@list');
                Route::match(['get', 'post'], 'coupon/add', 'CouponController@add');
                Route::match(['get', 'post'], 'coupon/edit/{id1}', 'CouponController@edit');
                Route::get('coupon/delete/{id1}', 'CouponController@delete');
                Route::get('coupon/change-status/{id1}', 'CouponController@change_status');
            /* coupon */
            /* cancel order reason */
                Route::get('cancel-order-reason/list', 'CancelOrderReasonController@list');
                Route::match(['get', 'post'], 'cancel-order-reason/add', 'CancelOrderReasonController@add');
                Route::match(['get', 'post'], 'cancel-order-reason/edit/{id}', 'CancelOrderReasonController@edit');
                Route::get('cancel-order-reason/delete/{id}', 'CancelOrderReasonController@delete');
                Route::get('cancel-order-reason/change-status/{id}', 'CancelOrderReasonController@change_status');
            /* cancel order reason */
            /* customer */
                Route::get('customer/list', 'CustomerController@list');
                Route::match(['get', 'post'], 'customer/add', 'CustomerController@add');
                Route::match(['get', 'post'], 'customer/edit/{id}', 'CustomerController@edit');
                Route::get('customer/delete/{id}', 'CustomerController@delete');
                Route::get('customer/change-status/{id}', 'CustomerController@change_status');
                Route::get('customer/view-billing-address/{id1}', 'CustomerController@viewBillingAddress');
                Route::get('customer/view-shipping-address/{id1}', 'CustomerController@viewShippingAddress');
                Route::get('customer/view-wishlists/{id1}', 'CustomerController@viewWishlist');
                Route::get('customer/view-orders/{id1}', 'CustomerController@viewOrders');
                Route::get('customer/view-reviews/{id1}', 'CustomerController@viewReviews');
            /* customer */
            /* customer */
                Route::get('reviews/list', 'ReviewController@list');
                Route::get('reviews/delete/{id}', 'ReviewController@delete');
                Route::get('reviews/change-status/{id}/{id2}', 'ReviewController@change_status');
            /* customer */
            /* reports */
                /* sales report */
                    Route::get('reports/sales-report', 'ReportController@salesReport');
                    Route::post('reports/sales-report', 'ReportController@salesReport');
                /* sales report */
            /* reports */
            /* home page */
                /* banner */
                    Route::get('banner/list', 'BannerController@list');
                    Route::match(['get', 'post'], 'banner/add', 'BannerController@add');
                    Route::match(['get', 'post'], 'banner/edit/{id}', 'BannerController@edit');
                    Route::get('banner/delete/{id}', 'BannerController@delete');
                    Route::get('banner/change-status/{id}', 'BannerController@change_status');
                /* banner */
                /* testimonial */
                    Route::get('testimonial/list', 'TestimonialController@list');
                    Route::match(['get', 'post'], 'testimonial/add', 'TestimonialController@add');
                    Route::match(['get', 'post'], 'testimonial/edit/{id}', 'TestimonialController@edit');
                    Route::get('testimonial/delete/{id}', 'TestimonialController@delete');
                    Route::get('testimonial/change-status/{id}', 'TestimonialController@change_status');
                /* testimonial */
                /* section 2 */
                    Route::get('home-page-section2/list', 'HomePageSection2Controller@list');
                    Route::match(['get', 'post'], 'home-page-section2/add', 'HomePageSection2Controller@add');
                    Route::match(['get', 'post'], 'home-page-section2/edit/{id}', 'HomePageSection2Controller@edit');
                    Route::get('home-page-section2/delete/{id}', 'HomePageSection2Controller@delete');
                    Route::get('home-page-section2/change-status/{id}', 'HomePageSection2Controller@change_status');
                /* section 2 */
                /* section 5 */
                    Route::match(['get', 'post'], 'home-page-section346/list', 'HomePageSection346Controller@list');
                /* section 5 */
                /* section 5 */
                    Route::get('home-page-section5/list', 'HomePageSection5Controller@list');
                    Route::match(['get', 'post'], 'home-page-section5/add', 'HomePageSection5Controller@add');
                    Route::match(['get', 'post'], 'home-page-section5/edit/{id}', 'HomePageSection5Controller@edit');
                    Route::get('home-page-section5/delete/{id}', 'HomePageSection5Controller@delete');
                    Route::get('home-page-section5/change-status/{id}', 'HomePageSection5Controller@change_status');
                /* section 5 */
            /* home page */
            /* page */
                Route::get('page/list', 'PageController@list');
                Route::match(['get', 'post'], 'page/add', 'PageController@add');
                Route::match(['get', 'post'], 'page/edit/{id}', 'PageController@edit');
                Route::get('page/delete/{id}', 'PageController@delete');
                Route::get('page/change-status/{id}', 'PageController@change_status');
            /* page */
            /* FAQs */
                /* faq category */
                    Route::get('faq-category/list', 'FaqCategoryController@list');
                    Route::match(['get', 'post'], 'faq-category/add', 'FaqCategoryController@add');
                    Route::match(['get', 'post'], 'faq-category/edit/{id}', 'FaqCategoryController@edit');
                    Route::get('faq-category/delete/{id}', 'FaqCategoryController@delete');
                    Route::get('faq-category/change-status/{id}', 'FaqCategoryController@change_status');
                    Route::get('faq-category/change-home-page-status/{id}', 'FaqCategoryController@change_home_page_status');
                /* faq category */
                /* faq */
                    Route::get('faq/list', 'FaqController@list');
                    Route::match(['get', 'post'], 'faq/add', 'FaqController@add');
                    Route::match(['get', 'post'], 'faq/edit/{id}', 'FaqController@edit');
                    Route::get('faq/delete/{id}', 'FaqController@delete');
                    Route::get('faq/change-status/{id}', 'FaqController@change_status');
                    Route::get('faq/change-home-page-status/{id}', 'FaqController@change_home_page_status');
                /* faq */
            /* FAQs */
            /* enquiries */
                Route::get('enquiry/list', 'EnquiryController@list');
                Route::get('enquiry/view-details/{id}', 'EnquiryController@details');
            /* enquiries */
            /* notifications */
                Route::get('notification/list', 'NotificationController@list');
                Route::match(['get', 'post'], 'notification/add', 'NotificationController@add');
                Route::match(['get', 'post'], 'notification/edit/{id}', 'NotificationController@edit');
                Route::get('notification/delete/{id}', 'NotificationController@delete');
                Route::get('notification/change-status/{id}', 'NotificationController@change_status');
                Route::get('notification/send/{id}', 'NotificationController@send');
                Route::post('notification/get-user', 'NotificationController@getUser');
            /* notifications */
            /* newsletter */
                Route::get('newsletter/list', 'NewsletterController@list');
                Route::match(['get', 'post'], 'newsletter/add', 'NewsletterController@add');
                Route::match(['get', 'post'], 'newsletter/edit/{id}', 'NewsletterController@edit');
                Route::get('newsletter/delete/{id}', 'NewsletterController@delete');
                Route::get('newsletter/change-status/{id}', 'NewsletterController@change_status');
                Route::get('newsletter/send/{id}', 'NewsletterController@send');
                Route::post('newsletter/get-user', 'NewsletterController@getUser');
                Route::get('newsletter/subscriber-list', 'NewsletterController@subscriber_list');
            /* newsletter */
        });
    });
/* Admin Panel */
/* Api */
    Route::prefix('api')->namespace('App\Http\Controllers')->group(function(){
        // Other Version 2 routes
        /* before login */
            Route::match(['get'], '/get-app-setting', 'ApiController@getAppSetting');
            Route::match(['post'], '/get-static-pages', 'ApiController@getStaticPages');
            Route::match(['post'], '/signup', 'ApiController@signup');
            Route::match(['post'], '/signup-validate', 'ApiController@signupValidate');
            Route::match(['post'], '/signin', 'ApiController@signin');
            Route::match(['post'], '/forgot-password', 'ApiController@forgotPassword');
            Route::match(['post'], '/validate-otp', 'ApiController@validateOtp');
            Route::match(['post'], '/resend-otp', 'ApiController@resendOtp');
            Route::match(['post'], '/reset-password', 'ApiController@resetPassword');
            Route::match(['get'], '/get-home', 'ApiController@getHome');
            Route::match(['get'], '/faq', 'ApiController@faq');
            Route::match(['post'], '/contact-us', 'ApiController@contactUs');
            Route::match(['post'], '/submit-subscriber', 'ApiController@submitSubscriber');
            Route::match(['get'], '/get-parent-category', 'ApiController@getParentCategory');
            Route::match(['post'], '/get-child-category', 'ApiController@getChildCategory');
            Route::match(['post'], '/get-product-list-by-parent-category', 'ApiController@getProductListByParentCategory');
            Route::match(['get'], '/get-all-product-list', 'ApiController@getAllProductList');
            Route::match(['post'], '/product-filter', 'ApiController@productFilter');
            Route::match(['post'], '/product-details', 'ApiController@productDetails');
            Route::match(['post'], '/select-variation', 'ApiController@selectVariation');
            Route::match(['post'], '/add-cart', 'ApiController@addCart');
            Route::match(['get'], '/get-cart', 'ApiController@getCart');
            Route::match(['post'], '/cart-item-remove', 'ApiController@cartItemRemove');
            Route::match(['post'], '/update-cart-item', 'ApiController@updateCartItem');
            Route::match(['post'], '/search-product', 'ApiController@searchProduct');
            Route::match(['post'], '/search-suggestion', 'ApiController@searchSuggestion');
            Route::match(['post'], '/apply-coupon', 'ApiController@applyCoupon');
            Route::match(['get'], '/remove-coupon', 'ApiController@removeCoupon');
            Route::match(['post'], '/payment-process', 'ApiController@paymentProcess');
        /* before login */
        /* after login */
            Route::match(['get'], '/signout', 'ApiController@signout');
            Route::match(['get'], '/dashboard', 'ApiController@dashboard');
            Route::match(['post'], '/change-password', 'ApiController@changePassword');
            Route::match(['get'], '/get-profile', 'ApiController@getProfile');
            Route::match(['get'], '/edit-profile', 'ApiController@editProfile');
            Route::match(['post'], '/update-profile', 'ApiController@updateProfile');
            Route::match(['post'], '/upload-profile-image', 'ApiController@uploadProfileImage');
            Route::match(['get'], '/get-address', 'ApiController@getAddress');
            Route::match(['post'], '/add-address', 'ApiController@addAddress');
            Route::match(['post'], '/delete-address', 'ApiController@deleteAddress');
            Route::match(['get'], '/get-reviews', 'ApiController@getReview');
            Route::match(['get'], '/get-wishlist', 'ApiController@getWishlist');
            Route::match(['post'], '/delete-wishlist', 'ApiController@deleteWishlist');
            Route::match(['post'], '/add-wishlist', 'ApiController@addWishlist');
            Route::match(['post'], '/add-review', 'ApiController@addReview');
            Route::match(['get'], '/checkout', 'ApiController@checkout');
            Route::match(['post'], '/place-order', 'ApiController@placeOrder');
            Route::match(['get'], '/order-list', 'ApiController@orderList');
            Route::match(['post'], '/order-details', 'ApiController@orderDetails');
            Route::match(['post'], '/print-invoice', 'ApiController@printInvoice');
            Route::match(['get'], '/cancel-order-reason', 'ApiController@cancelOrderReason');
            Route::match(['post'], '/cancel-order', 'ApiController@cancelOrder');
        /* after login */
    });
/* Api */