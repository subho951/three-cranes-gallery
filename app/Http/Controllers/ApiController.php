<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Services\PaymentService;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Banner;
use App\Models\Category;
use App\Models\CancelOrderReason;
use App\Models\Coupon;
use App\Models\HomePage;
use App\Models\HomePage2Section;
use App\Models\Testimonial;
use App\Models\GeneralSetting;
use App\Models\EmailLog;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\ProductAttribute;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Enquiry;
use App\Models\UserActivity;
use App\Models\Subscriber;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\UserLocation;
use App\Models\UserReview;
use App\Models\UserWishlist;
use App\Models\VariationAttribute;
use App\Models\UserView;
use App\Models\UserVisit;
use App\Models\UserWebsiteActivity;

use Auth;
use Session;
use Helper;
use Hash;
use DB;
use App\Libraries\CreatorJwt;
use App\Libraries\JWT;
use Dompdf\Dompdf;
use Dompdf\Options;
date_default_timezone_set("Asia/Calcutta");
class ApiController extends Controller
{
    protected $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    /* before login screen */
        public function getAppSetting(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $generalSetting = GeneralSetting::find(1);
                if($generalSetting){
                    $footer_links       = [];
                    $store              = [];
                    $help               = [];
                    $support            = [];
                    $footer_link_name   = (($generalSetting->footer_link_name != '')?json_decode($generalSetting->footer_link_name):[]);
                    $footer_link        = (($generalSetting->footer_link != '')?json_decode($generalSetting->footer_link):[]);
                    if(!empty($footer_link_name)){ for($i=0;$i<count($footer_link_name);$i++){
                        $store[]        = [
                            'footer_link_name'  => $footer_link_name[$i],
                            'footer_link'       => $footer_link[$i]
                        ];
                    } }
                    $footer_link_name2   = (($generalSetting->footer_link_name2 != '')?json_decode($generalSetting->footer_link_name2):[]);
                    $footer_link2        = (($generalSetting->footer_link2 != '')?json_decode($generalSetting->footer_link2):[]);
                    if(!empty($footer_link_name2)){ for($i=0;$i<count($footer_link_name2);$i++){
                        $help[]        = [
                            'footer_link_name'  => $footer_link_name2[$i],
                            'footer_link'       => $footer_link2[$i]
                        ];
                    } }
                    $footer_link_name3   = (($generalSetting->footer_link_name3 != '')?json_decode($generalSetting->footer_link_name3):[]);
                    $footer_link3        = (($generalSetting->footer_link3 != '')?json_decode($generalSetting->footer_link3):[]);
                    if(!empty($footer_link_name3)){ for($i=0;$i<count($footer_link_name3);$i++){
                        $support[]        = [
                            'footer_link_name'  => $footer_link_name3[$i],
                            'footer_link'       => $footer_link3[$i]
                        ];
                    } }
                    $footer_links       = [
                        'store'     => $store,
                        'help'      => $help,
                        'support'   => $support
                    ];
                    $apiResponse        = [
                        'site_name'             => $generalSetting->site_name,
                        'site_phone'            => $generalSetting->site_phone,
                        'site_phone2'           => $generalSetting->site_phone2,
                        'site_mail'             => $generalSetting->site_mail,
                        'site_url'              => $generalSetting->site_url,
                        'site_logo'             => env('UPLOADS_URL').$generalSetting->site_logo,
                        'site_address'          => $generalSetting->description,
                        'theme_color'           => $generalSetting->theme_color,
                        'font_color'            => $generalSetting->font_color,
                        'twitter_profile'       => $generalSetting->twitter_profile,
                        'facebook_profile'      => $generalSetting->facebook_profile,
                        'instagram_profile'     => $generalSetting->instagram_profile,
                        'linkedin_profile'      => $generalSetting->linkedin_profile,
                        'youtube_profile'       => $generalSetting->youtube_profile,
                        'topbar_text'           => $generalSetting->topbar_text,
                        'google_map_embed_code' => $generalSetting->google_analytics_code,
                        'timing'                => $generalSetting->timing,
                        'footer_text'           => $generalSetting->footer_text,
                        'footer_links'          => $footer_links,
                    ];
                }
                /* visit analytics track */
                    $userAgent                      = $request->header('User-Agent', 'unknown');
                    $acceptLanguage                 = $request->header('Accept-Language', 'en');
                    $clientIp                       = $request->ip();
                    $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                    $currentDate                    = date('Y-m-d');
                    $checkUserVisit                 = UserVisit::where('device_id', '=', $deviceId)->where('created_at', 'LIKE', '%'.$currentDate.'%')->count();
                    if($checkUserVisit <= 0){
                        $visitData = [
                            'device_id' => $deviceId
                        ];
                        UserVisit::insert($visitData);
                    }
                /* visit analytics track */
                /* view analytics track */
                    $viewData = [
                        'device_id'     => $deviceId,
                        'page'          => 'application',
                        'product_id'    => 0,
                    ];
                    UserView::insert($viewData);
                /* view analytics track */
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getStaticPages(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'page_slug'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $page_slug      = $requestData['page_slug'];
                $pageContent    = Page::select('page_title', 'long_description', 'meta_title', 'meta_description', 'meta_keywords')->where('status', '=', 1)->where('slug', '=', $page_slug)->first();
                $generalSetting = GeneralSetting::find(1);
                if($pageContent){
                    $apiResponse[] = [
                        'page_title'                => $pageContent->page_title,
                        'short_description'         => $pageContent->long_description,
                        'meta_title'                => (($pageContent->meta_title != '')?$pageContent->meta_title:$generalSetting->meta_title),
                        'meta_description'          => (($pageContent->meta_description != '')?$pageContent->meta_description:$generalSetting->meta_description),
                        'meta_keywords'             => (($pageContent->meta_keywords != '')?$pageContent->meta_keywords:$generalSetting->meta_keywords),
                    ];
                }
                /* view analytics track */
                    $userAgent                      = $request->header('User-Agent', 'unknown');
                    $acceptLanguage                 = $request->header('Accept-Language', 'en');
                    $clientIp                       = $request->ip();
                    $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                    $viewData = [
                        'device_id'     => $deviceId,
                        'page'          => $pageContent->page_title,
                        'product_id'    => 0,
                    ];
                    UserView::insert($viewData);
                /* view analytics track */
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function faq(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $faqCats = FaqCategory::select('id', 'name')->where('status', '=', 1)->get();
                if($faqCats){
                    foreach($faqCats as $faqCat){
                        $faqs           = Faq::select('question', 'answer')->where('status', '=', 1)->where('faq_category_id', '=', $faqCat->id)->get();
                        $faq_questions  = [];
                        if($faqs){
                            foreach($faqs as $faq){
                                $faq_questions[]  = [
                                    'question'  => $faq->question,
                                    'answer'    => $faq->answer,
                                ];
                            }
                        }
                        $apiResponse[]    = [
                            'faq_category_id'               => $faqCat->id,
                            'faq_category_name'             => $faqCat->name,
                            'faq_questions'                 => $faq_questions,
                        ];
                    }
                }
                /* view analytics track */
                    $userAgent                      = $request->header('User-Agent', 'unknown');
                    $acceptLanguage                 = $request->header('Accept-Language', 'en');
                    $clientIp                       = $request->ip();
                    $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                    $viewData = [
                        'device_id'     => $deviceId,
                        'page'          => 'faq',
                        'product_id'    => 0,
                    ];
                    UserView::insert($viewData);
                /* view analytics track */
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function contactUs(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();       
            $requiredFields     = ['key', 'source', 'first_name', 'last_name', 'email', 'phone', 'subject', 'description'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $postData = [
                            'name'                          => $requestData['first_name'].' '.$requestData['last_name'],
                            'email'                         => $requestData['email'],
                            'phone'                         => $requestData['phone'],
                            'subject'                       => $requestData['subject'],
                            'description'                   => $requestData['description'],
                        ];
                // Helper::pr($postData);
                Enquiry::insert($postData);
                // new password send mail
                    $generalSetting                 = GeneralSetting::find('1');
                    $subject                        = $generalSetting->site_name.' Contact Us';
                    $mailData                       = $postData;
                    $html                           = view('email-templates/contact-us', $mailData);
                    $this->sendMail($generalSetting->system_email, $subject, $html);
                    // echo $html;die;
                // new password send mail
                /* view analytics track */
                    $userAgent                      = $request->header('User-Agent', 'unknown');
                    $acceptLanguage                 = $request->header('Accept-Language', 'en');
                    $clientIp                       = $request->ip();
                    $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                    $viewData = [
                        'device_id'     => $deviceId,
                        'page'          => 'contact us',
                        'product_id'    => 0,
                    ];
                    UserView::insert($viewData);
                /* view analytics track */
                $apiStatus                  = TRUE;
                $apiMessage                 = 'Enquiry Submitted Successfully !!!';                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function submitSubscriber(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();       
            $requiredFields     = ['key', 'source', 'email'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($requestData['key'] == env('PROJECT_KEY')){
                $checkSubscriberExist = Subscriber::where('email', '=', $requestData['email'])->count();
                if($checkSubscriberExist <= 0){
                    $postData = [
                        'email'                         => $requestData['email']
                    ];
                    Subscriber::insert($postData);
                    /* view analytics track */
                        $userAgent                      = $request->header('User-Agent', 'unknown');
                        $acceptLanguage                 = $request->header('Accept-Language', 'en');
                        $clientIp                       = $request->ip();
                        $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                        $viewData = [
                            'device_id'     => $deviceId,
                            'page'          => 'submit subscriber',
                            'product_id'    => 0,
                        ];
                        UserView::insert($viewData);
                    /* view analytics track */
                    $apiStatus                  = TRUE;
                    $apiMessage                 = 'Email Subscribed Successfully !!!';
                } else {
                    $apiStatus          = FALSE;
                    $apiMessage         = 'Email Already Subscribed !!!';
                }                                             
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getHome(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                } else {
                    $uId        = 0;
                }
                $section1 = [];
                $section2 = [];
                $section3 = [];
                $section4 = [];
                $section5 = [];
                $section6 = [];
                $section7 = [];
                /* section 1 */
                    $banner = Banner::select('banner_text', 'banner_text2', 'banner_link', 'banner_image')->where('status', '=', 1)->first();
                    if($banner){
                        $section1 = [
                            'banner_text'   => $banner->banner_text,
                            'banner_text2'  => $banner->banner_text2,
                            'banner_link'   => $banner->banner_link,
                            'banner_image'  => env('UPLOADS_URL') . 'banner/' . $banner->banner_image
                        ];
                    }
                /* section 1 */
                /* section 2 */
                    $homePage2Sections = HomePage2Section::select('name', 'icon', 'short_description', 'section2_link')->where('status', '=', 1)->orderBy('id', 'DESC')->get();
                    if($homePage2Sections){
                        foreach($homePage2Sections as $homePage2Section){
                            $section2[] = [
                                'name'                  => $homePage2Section->name,
                                'short_description'     => $homePage2Section->short_description,
                                'section2_link'         => $homePage2Section->section2_link,
                                'icon'                  => env('UPLOADS_URL') . 'home_page/' . $homePage2Section->icon
                            ];
                        }
                    }
                /* section 2 */
                /* section 3 */
                    $homePage       = HomePage::where('status', '=', 1)->first();
                    $new_products   = [];
                    $products       = DB::table('products')
                                        ->join('categories', 'products.sub_category', '=', 'categories.id')
                                        ->select('products.*', 'categories.category_name as sub_category_name')
                                        ->where('products.status', '=', 1)
                                        ->where('products.is_new', '=', 1)
                                        ->orderBy('products.id', 'DESC')
                                        ->limit(10)
                                        ->get();
                    if($products){
                        foreach($products as $product){
                            $averageRating                  = UserReview::where('product_id', $product->id)->where('status', '=', 1)->avg('rating') ?? 0;
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $checkCart                      = OrderDetail::where('cust_device_id', '=', $deviceId)->where('product_id', '=', $product->id)->where('is_cart', '=', 1)->count();
                            $checkWishlist                  = UserWishlist::where('user_id', '=', $uId)->where('product_id', '=', $product->id)->count();
                            $checkProductVariation          = ProductVariation::where('product_id', '=', $product->id)->orderBy('price', 'asc')->first();
                            $new_products[]                 = [
                                'id'                    => $product->id,
                                'name'                  => $product->name,
                                'slug'                  => $product->slug,
                                'base_price'            => number_format((($checkProductVariation)?$checkProductVariation->price:$product->discounted_price),2),
                                'price_percentage'      => $product->price_percentage,
                                'discount_amount'       => $product->discount_amount,
                                'markup_price'          => number_format($product->base_price,2),
                                'sub_category_name'     => $product->sub_category_name,
                                'cover_image'           => env('UPLOADS_URL') . 'product/' . $product->cover_image,
                                'rating'                => round($averageRating),
                                'is_cart'               => (($checkCart > 0)?1:0),
                                'is_wishlist'           => (($checkWishlist > 0)?1:0),
                                'product_sku'           => $product->product_sku,
                                'product_qty'           => $product->product_qty,
                            ];
                        }
                    }
                    if($homePage){
                        $section3 = [
                            'sec3_title'        => $homePage->sec3_title,
                            'sec3_description'  => $homePage->sec3_description,
                            'products'          => $new_products,
                        ];
                    }
                /* section 3 */
                /* section 4 */
                    $trending_products   = [];
                    $products       = DB::table('products')
                                        ->join('categories', 'products.sub_category', '=', 'categories.id')
                                        ->select('products.*', 'categories.category_name as sub_category_name')
                                        ->where('products.status', '=', 1)
                                        ->where('products.is_new', '=', 1)
                                        ->orderBy('products.id', 'DESC')
                                        ->limit(10)
                                        ->get();
                    if($products){
                        foreach($products as $product){
                            $averageRating                  = UserReview::where('product_id', $product->id)->where('status', '=', 1)->avg('rating') ?? 0;
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $checkCart                      = OrderDetail::where('cust_device_id', '=', $deviceId)->where('product_id', '=', $product->id)->where('is_cart', '=', 1)->count();
                            $checkWishlist                  = UserWishlist::where('user_id', '=', $uId)->where('product_id', '=', $product->id)->count();
                            $checkProductVariation          = ProductVariation::where('product_id', '=', $product->id)->orderBy('price', 'asc')->first();
                            $trending_products[]            = [
                                'id'                    => $product->id,
                                'name'                  => $product->name,
                                'slug'                  => $product->slug,
                                'base_price'            => number_format((($checkProductVariation)?$checkProductVariation->price:$product->discounted_price),2),
                                'price_percentage'      => $product->price_percentage,
                                'discount_amount'       => $product->discount_amount,
                                'markup_price'          => number_format($product->base_price,2),
                                'sub_category_name'     => $product->sub_category_name,
                                'cover_image'           => env('UPLOADS_URL') . 'product/' . $product->cover_image,
                                'rating'                => round($averageRating),
                                'is_cart'               => (($checkCart > 0)?1:0),
                                'is_wishlist'           => (($checkWishlist > 0)?1:0),
                                'product_sku'           => $product->product_sku,
                                'product_qty'           => $product->product_qty,
                            ];
                        }
                    }
                    $homePage = HomePage::where('status', '=', 1)->first();
                    if($homePage){
                        $section4 = [
                            'sec4_title'        => $homePage->sec4_title,
                            'sec4_description'  => $homePage->sec4_description,
                            'products'          => $trending_products,
                        ];
                    }
                /* section 4 */
                /* section 5 */
                    $featured_products   = [];
                    $products       = DB::table('products')
                                        ->join('categories', 'products.sub_category', '=', 'categories.id')
                                        ->select('products.*', 'categories.category_name as sub_category_name')
                                        ->where('products.status', '=', 1)
                                        ->where('products.is_feature', '=', 1)
                                        ->orderBy('products.id', 'DESC')
                                        ->limit(10)
                                        ->get();
                    if($products){
                        foreach($products as $product){
                            $averageRating                  = UserReview::where('product_id', $product->id)->where('status', '=', 1)->avg('rating') ?? 0;
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $checkCart                      = OrderDetail::where('cust_device_id', '=', $deviceId)->where('product_id', '=', $product->id)->where('is_cart', '=', 1)->count();
                            $checkWishlist                  = UserWishlist::where('user_id', '=', $uId)->where('product_id', '=', $product->id)->count();
                            $checkProductVariation          = ProductVariation::where('product_id', '=', $product->id)->orderBy('price', 'asc')->first();
                            $featured_products[]            = [
                                'id'                    => $product->id,
                                'name'                  => $product->name,
                                'slug'                  => $product->slug,
                                'base_price'            => number_format((($checkProductVariation)?$checkProductVariation->price:$product->discounted_price),2),
                                'price_percentage'      => $product->price_percentage,
                                'discount_amount'       => $product->discount_amount,
                                'markup_price'          => number_format($product->base_price,2),
                                'sub_category_name'     => $product->sub_category_name,
                                'cover_image'           => env('UPLOADS_URL') . 'product/' . $product->cover_image,
                                'rating'                => round($averageRating),
                                'is_cart'               => (($checkCart > 0)?1:0),
                                'is_wishlist'           => (($checkWishlist > 0)?1:0),
                                'product_sku'           => $product->product_sku,
                                'product_qty'           => $product->product_qty,
                            ];
                        }
                    }
                    $homePage = HomePage::where('status', '=', 1)->first();
                    if($homePage){
                        $section5 = [
                            'sec5_title'        => $homePage->sec5_title,
                            'sec5_description'  => $homePage->sec5_description,
                            'products'          => $featured_products,
                        ];
                    }
                /* section 5 */
                /* section 6 */
                    $testimonials = Testimonial::select('name', 'review', 'rate', 'image')->where('status', '=', 1)->orderBy('id', 'DESC')->get();
                    if($testimonials){
                        foreach($testimonials as $testimonial){
                            $section6[] = [
                                'name'      => $testimonial->name,
                                'review'    => $testimonial->review,
                                'rate'      => $testimonial->rate,
                                'image'     => env('UPLOADS_URL') . 'testimonial/' . $testimonial->image
                            ];
                        }
                    }
                /* section 6 */
                /* section 7 */
                    $homePage = HomePage::where('status', '=', 1)->first();
                    if($homePage){
                        $section7 = [
                            'sec7_title'        => $homePage->sec7_title,
                            'sec7_description'  => $homePage->sec7_description,
                        ];
                    }
                /* section 7 */
                /* view analytics track */
                    $userAgent                      = $request->header('User-Agent', 'unknown');
                    $acceptLanguage                 = $request->header('Accept-Language', 'en');
                    $clientIp                       = $request->ip();
                    $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                    $viewData = [
                        'device_id'     => $deviceId,
                        'page'          => 'home',
                        'product_id'    => 0,
                    ];
                    UserView::insert($viewData);
                /* view analytics track */
                $apiResponse        = [
                    'section1' => $section1,
                    'section2' => $section2,
                    'section3' => $section3,
                    'section4' => $section4,
                    'section5' => $section5,
                    'section6' => $section6,
                    'section7' => $section7
                ];
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getParentCategory(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $parentCats = Category::select('id', 'category_name', 'slug')->where('status', '=', 1)->where('parent_id', '=', 0)->get();
                if($parentCats){
                    foreach($parentCats as $parentCat){
                        $apiResponse[]    = [
                            'parent_category_id'          => $parentCat->id,
                            'parent_category_name'        => $parentCat->category_name,
                            'parent_slug'                 => $parentCat->slug
                        ];
                    }
                }
                /* view analytics track */
                    $userAgent                      = $request->header('User-Agent', 'unknown');
                    $acceptLanguage                 = $request->header('Accept-Language', 'en');
                    $clientIp                       = $request->ip();
                    $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                    $viewData = [
                        'device_id'     => $deviceId,
                        'page'          => 'get parent category',
                        'product_id'    => 0,
                    ];
                    UserView::insert($viewData);
                /* view analytics track */
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getChildCategory(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'parent_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $parent_id = $requestData['parent_id'];
                $childCats = Category::select('id', 'category_name', 'slug')->where('status', '=', 1)->where('parent_id', '=', $parent_id)->get();
                if($childCats){
                    foreach($childCats as $childCat){
                        $apiResponse[]    = [
                            'child_category_id'          => $childCat->id,
                            'child_category_name'        => $childCat->category_name,
                            'child_slug'                 => $childCat->slug
                        ];
                    }
                }
                /* view analytics track */
                    $userAgent                      = $request->header('User-Agent', 'unknown');
                    $acceptLanguage                 = $request->header('Accept-Language', 'en');
                    $clientIp                       = $request->ip();
                    $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                    $viewData = [
                        'device_id'     => $deviceId,
                        'page'          => 'get child category',
                        'product_id'    => 0,
                    ];
                    UserView::insert($viewData);
                /* view analytics track */
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getProductListByParentCategory(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'parent_id', 'page_no'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                } else {
                    $uId        = 0;
                }
                $parent_id          = $requestData['parent_id'];
                $page_no            = $requestData['page_no'];
                $getParentCategory  = Category::select('id', 'category_name', 'slug')->where('id', '=', $parent_id)->first();
                if($getParentCategory){
                    // filter bar
                        $filter_bar = [];
                        $childCats  = Category::select('id', 'category_name', 'slug')->where('status', '=', 1)->where('parent_id', '=', $parent_id)->get();
                        if($childCats){
                            foreach($childCats as $childCat){
                                $filter_bar[]    = [
                                    'child_category_id'          => $childCat->id,
                                    'child_category_name'        => $childCat->category_name,
                                    'child_slug'                 => $childCat->slug
                                ];
                            }
                        }
                    // filter bar
                    // product bar
                        $limit          = 9; // per page elements
                        if($page_no == 1){
                            $offset = 0;
                        } else {
                            $offset = (($limit * $page_no) - $limit); // ((15 * 3) - 15)
                        }
                        $product_list   = [];
                        $products       = DB::table('products')
                                            ->join('categories', 'products.sub_category', '=', 'categories.id')
                                            ->select('products.*', 'categories.category_name as sub_category_name')
                                            ->where('products.status', '=', 1)
                                            ->where('products.main_category', '=', $parent_id)
                                            ->orderBy('products.id', 'DESC')
                                            ->offset($offset)
                                            ->limit($limit)
                                            ->get();
                        $totalProductCount = DB::table('products')
                                            ->join('categories', 'products.sub_category', '=', 'categories.id')
                                            ->select('products.*', 'categories.category_name as sub_category_name')
                                            ->where('products.status', '=', 1)
                                            ->where('products.main_category', '=', $parent_id)
                                            ->orderBy('products.id', 'DESC')
                                            ->count();
                        if($products){
                            foreach($products as $product){
                                $averageRating                  = UserReview::where('product_id', $product->id)->where('status', '=', 1)->avg('rating') ?? 0;
                                $userAgent                      = $request->header('User-Agent', 'unknown');
                                $acceptLanguage                 = $request->header('Accept-Language', 'en');
                                $clientIp                       = $request->ip();
                                $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                                $checkCart                      = OrderDetail::where('cust_device_id', '=', $deviceId)->where('product_id', '=', $product->id)->where('is_cart', '=', 1)->count();
                                $checkWishlist                  = UserWishlist::where('user_id', '=', $uId)->where('product_id', '=', $product->id)->count();
                                $checkProductVariation          = ProductVariation::where('product_id', '=', $product->id)->orderBy('price', 'asc')->first();
                                $product_list[]                 = [
                                    'id'                    => $product->id,
                                    'name'                  => $product->name,
                                    'slug'                  => $product->slug,
                                    'base_price'            => number_format((($checkProductVariation)?$checkProductVariation->price:$product->discounted_price),2),
                                    'price_percentage'      => $product->price_percentage,
                                    'discount_amount'       => $product->discount_amount,
                                    'markup_price'          => number_format($product->base_price,2),
                                    'sub_category_name'     => $product->sub_category_name,
                                    'cover_image'           => env('UPLOADS_URL') . 'product/' . $product->cover_image,
                                    'rating'                => round($averageRating),
                                    'is_cart'               => (($checkCart > 0)?1:0),
                                    'is_wishlist'           => (($checkWishlist > 0)?1:0),
                                    'product_sku'           => $product->product_sku,
                                    'product_qty'           => $product->product_qty,
                                ];
                            }
                        }
                    // product bar
                    // Extract base_price values and convert to numeric
                    if(count($products) > 0){
                        $basePrices = array_map(function ($product) {
                            return floatval(str_replace(',', '', $product['base_price']));
                        }, $product_list);
                        // Get the minimum and maximum base prices
                        $minBasePrice = min($basePrices);
                        $maxBasePrice = max($basePrices);
                    } else {
                        $product_list = [];
                        $minBasePrice = 0;
                        $maxBasePrice = 0;
                    }
                    
                    $apiResponse        = [
                        'parent_category_name'      => $getParentCategory->category_name,
                        'filter_bar'                => $filter_bar,
                        'product_list'              => $product_list,
                        'min_price'                 => $minBasePrice,
                        'max_price'                 => $maxBasePrice,
                        'total_product_count'       => $totalProductCount,
                    ];
                    /* view analytics track */
                        $userAgent                      = $request->header('User-Agent', 'unknown');
                        $acceptLanguage                 = $request->header('Accept-Language', 'en');
                        $clientIp                       = $request->ip();
                        $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                        $viewData = [
                            'device_id'     => $deviceId,
                            'page'          => 'category wise product list',
                            'product_id'    => 0,
                        ];
                        UserView::insert($viewData);
                    /* view analytics track */
                    http_response_code(200);
                    $apiStatus          = TRUE;
                    $apiMessage         = 'Data Available !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                } else {
                    http_response_code(200);
                    $apiStatus          = FALSE;
                    $apiMessage         = 'Parent Category Not Found !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                }
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getAllProductList(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                } else {
                    $uId        = 0;
                }
                // filter bar
                    $filter_bar     = [];
                    $getParentCats  = Category::select('id', 'category_name', 'slug')->where('status', '=', 1)->where('parent_id', '=', 0)->get();
                    if($getParentCats){
                        foreach($getParentCats as $getParentCat){
                            $child_category     = [];
                            $childCats          = Category::select('id', 'category_name', 'slug')->where('status', '=', 1)->where('parent_id', '=', $getParentCat->id)->get();
                            if($childCats){
                                foreach($childCats as $childCat){
                                    $child_category[]    = [
                                        'child_category_id'          => $childCat->id,
                                        'child_category_name'        => $childCat->category_name,
                                        'child_category_slug'        => $childCat->slug
                                    ];
                                }
                            }
                            $filter_bar[]       = [
                                'parent_category_id'    => $getParentCat->id,
                                'parent_category_name'  => $getParentCat->category_name,
                                'parent_category_slug'  => $getParentCat->slug,
                                'child_category'        => $child_category
                            ];
                        }
                    }
                // filter bar
                // product bar
                    $product_list   = [];
                    $products       = DB::table('products')
                                        ->join('categories', 'products.sub_category', '=', 'categories.id')
                                        ->select('products.*', 'categories.category_name as sub_category_name')
                                        ->where('products.status', '=', 1)
                                        // ->where('products.main_category', '=', $parent_id)
                                        ->orderBy('products.id', 'DESC')
                                        ->get();
                    if($products){
                        foreach($products as $product){
                            $averageRating                  = UserReview::where('product_id', $product->id)->where('status', '=', 1)->avg('rating') ?? 0;
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $checkCart                      = OrderDetail::where('cust_device_id', '=', $deviceId)->where('product_id', '=', $product->id)->where('is_cart', '=', 1)->count();
                            $checkWishlist                  = UserWishlist::where('user_id', '=', $uId)->where('product_id', '=', $product->id)->count();
                            $checkProductVariation          = ProductVariation::where('product_id', '=', $product->id)->orderBy('price', 'asc')->first();
                            $product_list[]                 = [
                                'id'                    => $product->id,
                                'name'                  => $product->name,
                                'slug'                  => $product->slug,
                                'base_price'            => number_format((($checkProductVariation)?$checkProductVariation->price:$product->discounted_price),2),
                                'price_percentage'      => $product->price_percentage,
                                'discount_amount'       => $product->discount_amount,
                                'markup_price'          => number_format($product->base_price,2),
                                'sub_category_name'     => $product->sub_category_name,
                                'cover_image'           => env('UPLOADS_URL') . 'product/' . $product->cover_image,
                                'rating'                => round($averageRating),
                                'is_cart'               => (($checkCart > 0)?1:0),
                                'is_wishlist'           => (($checkWishlist > 0)?1:0),
                                'product_sku'           => $product->product_sku,
                                'product_qty'           => $product->product_qty,
                            ];
                        }
                    }
                // product bar
                // Extract base_price values and convert to numeric
                $basePrices = array_map(function ($product) {
                    return floatval(str_replace(',', '', $product['base_price']));
                }, $product_list);
                // Get the minimum and maximum base prices
                $minBasePrice = min($basePrices);
                $maxBasePrice = max($basePrices);
                $apiResponse        = [
                    'filter_bar'                => $filter_bar,
                    'product_list'              => $product_list,
                    'min_price'                 => $minBasePrice,
                    'max_price'                 => $maxBasePrice,
                ];
                /* view analytics track */
                    $userAgent                      = $request->header('User-Agent', 'unknown');
                    $acceptLanguage                 = $request->header('Accept-Language', 'en');
                    $clientIp                       = $request->ip();
                    $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                    $viewData = [
                        'device_id'     => $deviceId,
                        'page'          => 'get all product list',
                        'product_id'    => 0,
                    ];
                    UserView::insert($viewData);
                /* view analytics track */
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function productFilter(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'parent_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                } else {
                    $uId        = 0;
                }
                $parent_id     = $requestData['parent_id'];
                $subcat_id     = $requestData['subcat_id'];
                $min_range     = $requestData['min_range'];
                $max_range     = $requestData['max_range'];
                $product_list  = [];
                if(empty($subcat_id) && ($min_range == '' && $max_range != '')){
                    http_response_code(200);
                    $apiStatus          = FALSE;
                    $apiMessage         = 'Minimum & Maximum Range Is Required !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                } elseif(empty($subcat_id) && ($min_range != '' && $max_range == '')){
                    http_response_code(200);
                    $apiStatus          = FALSE;
                    $apiMessage         = 'Minimum & Maximum Range Is Required !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                } elseif(empty($subcat_id) && ($min_range != '' && $max_range != '')){
                    $products       = DB::table('products')
                                        ->join('categories', 'products.sub_category', '=', 'categories.id')
                                        ->select('products.*', 'categories.category_name as sub_category_name')
                                        ->where('products.status', '=', 1)
                                        ->where('products.main_category', '=', $parent_id)
                                        ->where('products.base_price', '>=', $min_range)
                                        ->where('products.base_price', '<=', $max_range)
                                        ->orderBy('products.id', 'DESC')
                                        ->get();
                    if($products){
                        foreach($products as $product){
                            $averageRating                  = UserReview::where('product_id', $product->id)->where('status', '=', 1)->avg('rating') ?? 0;
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $checkCart                      = OrderDetail::where('cust_device_id', '=', $deviceId)->where('product_id', '=', $product->id)->where('is_cart', '=', 1)->count();
                            $checkWishlist                  = UserWishlist::where('user_id', '=', $uId)->where('product_id', '=', $product->id)->count();
                            $checkProductVariation          = ProductVariation::where('product_id', '=', $product->id)->orderBy('price', 'asc')->first();
                            $product_list[]                 = [
                                'id'                    => $product->id,
                                'name'                  => $product->name,
                                'slug'                  => $product->slug,
                                'base_price'            => number_format((($checkProductVariation)?$checkProductVariation->price:$product->discounted_price),2),
                                'price_percentage'      => $product->price_percentage,
                                'discount_amount'       => $product->discount_amount,
                                'markup_price'          => number_format($product->base_price,2),
                                'sub_category_name'     => $product->sub_category_name,
                                'cover_image'           => env('UPLOADS_URL') . 'product/' . $product->cover_image,
                                'rating'                => round($averageRating),
                                'is_cart'               => (($checkCart > 0)?1:0),
                                'is_wishlist'           => (($checkWishlist > 0)?1:0),
                                'product_sku'           => $product->product_sku,
                                'product_qty'           => $product->product_qty,
                            ];
                        }
                    }
                    $apiResponse = $product_list;
                    http_response_code(200);
                    $apiStatus          = TRUE;
                    $apiMessage         = 'Product Available !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                } elseif(!empty($subcat_id) && ($min_range == '' && $max_range == '')){
                    $products       = DB::table('products')
                                        ->join('categories', 'products.sub_category', '=', 'categories.id')
                                        ->select('products.*', 'categories.category_name as sub_category_name')
                                        ->where('products.status', '=', 1)
                                        ->where('products.main_category', '=', $parent_id)
                                        ->whereIn('sub_category', $subcat_id)
                                        ->orderBy('products.id', 'DESC')
                                        ->get();
                    if($products){
                        foreach($products as $product){
                            $averageRating                  = UserReview::where('product_id', $product->id)->where('status', '=', 1)->avg('rating') ?? 0;
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $checkCart                      = OrderDetail::where('cust_device_id', '=', $deviceId)->where('product_id', '=', $product->id)->where('is_cart', '=', 1)->count();
                            $checkWishlist                  = UserWishlist::where('user_id', '=', $uId)->where('product_id', '=', $product->id)->count();
                            $checkProductVariation          = ProductVariation::where('product_id', '=', $product->id)->orderBy('price', 'asc')->first();
                            $product_list[]                 = [
                                'id'                    => $product->id,
                                'name'                  => $product->name,
                                'slug'                  => $product->slug,
                                'base_price'            => number_format((($checkProductVariation)?$checkProductVariation->price:$product->discounted_price),2),
                                'price_percentage'      => $product->price_percentage,
                                'discount_amount'       => $product->discount_amount,
                                'markup_price'          => number_format($product->base_price,2),
                                'sub_category_name'     => $product->sub_category_name,
                                'cover_image'           => env('UPLOADS_URL') . 'product/' . $product->cover_image,
                                'rating'                => round($averageRating),
                                'is_cart'               => (($checkCart > 0)?1:0),
                                'is_wishlist'           => (($checkWishlist > 0)?1:0),
                                'product_sku'           => $product->product_sku,
                                'product_qty'           => $product->product_qty,
                            ];
                        }
                    }
                    $apiResponse = $product_list;
                    http_response_code(200);
                    $apiStatus          = TRUE;
                    $apiMessage         = 'Product Available !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                } elseif(!empty($subcat_id) && ($min_range != '' && $max_range != '')){
                    $products       = DB::table('products')
                                    ->join('categories', 'products.sub_category', '=', 'categories.id')
                                    ->select('products.*', 'categories.category_name as sub_category_name')
                                    ->where('products.status', '=', 1)
                                    ->where('products.main_category', '=', $parent_id)
                                    ->whereIn('sub_category', $subcat_id)
                                    ->where('products.base_price', '>=', $min_range)
                                    ->where('products.base_price', '<=', $max_range)
                                    ->orderBy('products.id', 'DESC')
                                    ->get();
                    if($products){
                        foreach($products as $product){
                            $averageRating                  = UserReview::where('product_id', $product->id)->where('status', '=', 1)->avg('rating') ?? 0;
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $checkCart                      = OrderDetail::where('cust_device_id', '=', $deviceId)->where('product_id', '=', $product->id)->where('is_cart', '=', 1)->count();
                            $checkWishlist                  = UserWishlist::where('user_id', '=', $uId)->where('product_id', '=', $product->id)->count();
                            $checkProductVariation          = ProductVariation::where('product_id', '=', $product->id)->orderBy('price', 'asc')->first();
                            $product_list[]   = [
                                'id'                    => $product->id,
                                'name'                  => $product->name,
                                'slug'                  => $product->slug,
                                'base_price'            => number_format((($checkProductVariation)?$checkProductVariation->price:$product->discounted_price),2),
                                'price_percentage'      => $product->price_percentage,
                                'discount_amount'       => $product->discount_amount,
                                'markup_price'          => number_format($product->base_price,2),
                                'sub_category_name'     => $product->sub_category_name,
                                'cover_image'           => env('UPLOADS_URL') . 'product/' . $product->cover_image,
                                'rating'                => round($averageRating),
                                'is_cart'               => (($checkCart > 0)?1:0),
                                'is_wishlist'           => (($checkWishlist > 0)?1:0),
                                'product_sku'           => $product->product_sku,
                                'product_qty'           => $product->product_qty,
                            ];
                        }
                    }
                    $apiResponse = $product_list;
                    http_response_code(200);
                    $apiStatus          = TRUE;
                    $apiMessage         = 'Product Available !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                }
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function productDetails(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'product_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                } else {
                    $uId        = 0;
                }
                $product_id  = $requestData['product_id'];
                // featured product bar
                    $featured_product_list   = [];
                    $products       = DB::table('products')
                                        ->join('categories', 'products.sub_category', '=', 'categories.id')
                                        ->select('products.*', 'categories.category_name as sub_category_name')
                                        ->where('products.status', '=', 1)
                                        ->where('products.is_feature', '=', 1)
                                        ->where('products.id', '!=', $product_id)
                                        ->orderBy('products.id', 'DESC')
                                        ->get();
                    if($products){
                        foreach($products as $product){
                            $averageRating                  = UserReview::where('product_id', $product->id)->where('status', '=', 1)->avg('rating') ?? 0;
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $checkCart                      = OrderDetail::where('cust_device_id', '=', $deviceId)->where('product_id', '=', $product->id)->where('is_cart', '=', 1)->count();
                            $checkWishlist                  = UserWishlist::where('user_id', '=', $uId)->where('product_id', '=', $product->id)->count();
                            $checkProductVariation          = ProductVariation::where('product_id', '=', $product->id)->orderBy('price', 'asc')->first();
                            $featured_product_list[]        = [
                                'id'                    => $product->id,
                                'name'                  => $product->name,
                                'slug'                  => $product->slug,
                                'base_price'            => number_format((($checkProductVariation)?$checkProductVariation->price:$product->discounted_price),2),
                                'price_percentage'      => $product->price_percentage,
                                'discount_amount'       => $product->discount_amount,
                                'markup_price'          => number_format($product->base_price,2),
                                'sub_category_name'     => $product->sub_category_name,
                                'cover_image'           => env('UPLOADS_URL') . 'product/' . $product->cover_image,
                                'rating'                => round($averageRating),
                                'is_cart'               => (($checkCart > 0)?1:0),
                                'is_wishlist'           => (($checkWishlist > 0)?1:0),
                            ];
                        }
                    }
                // featured product bar
                // product info
                    $getProduct = DB::table('products')
                                    ->join('categories', 'products.sub_category', '=', 'categories.id')
                                    ->select('products.*', 'categories.category_name as sub_category_name')
                                    ->where('products.status', '=', 1)
                                    ->where('products.id', '=', $product_id)
                                    ->first();
                    $product_info = [];
                    if($getProduct){
                        $averageRating  = UserReview::where('product_id', $product_id)->where('status', '=', 1)->avg('rating') ?? 0;
                        $reviews        = UserReview::where('product_id', $product_id)->where('status', '=', 1)->orderBy('id', 'DESC')->get();
                        $variations     = [];
                        $product_images = [];
                        $review_list    = [];
                        $productImages  = ProductImage::select('image')->where('product_id', '=', $product_id)->where('status', '=', 1)->orderBy('is_cover_image', 'DESC')->get();
                        if($productImages){
                            foreach($productImages as $productImage){
                                $product_images[] = env('UPLOADS_URL') . 'product/' . $productImage->image;
                            }
                        }
                        if($reviews){
                            foreach($reviews as $review){
                                $getUser = User::where('id', '=', $review->user_id)->first();
                                $review_list[]    = [
                                    'name'                  => $review->name,
                                    'user_profile_image'    => (($getUser->profile_image != '')?env('UPLOADS_URL').'user/'.$getUser->profile_image:env('NO_IMAGE')),
                                    'email'                 => $review->email,
                                    'rating'                => $review->rating,
                                    'title'                 => $review->title,
                                    'comment'               => $review->comment,
                                ];
                            }
                        }
                        $getParentCategory              = Category::select('id', 'category_name', 'slug')->where('id', '=', $getProduct->main_category)->first();
                        $userAgent                      = $request->header('User-Agent', 'unknown');
                        $acceptLanguage                 = $request->header('Accept-Language', 'en');
                        $clientIp                       = $request->ip();
                        $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                        $checkCart                      = OrderDetail::where('cust_device_id', '=', $deviceId)->where('product_id', '=', $getProduct->id)->where('is_cart', '=', 1)->count();
                        $checkWishlist                  = UserWishlist::where('user_id', '=', $uId)->where('product_id', '=', $getProduct->id)->count();
                        // variation
                            $dropdownValues = [];
                            $getProductparentAttrs = VariationAttribute::select('parent_attr_id')->where('status', '=', 1)->where('product_id', '=', $getProduct->id)->groupBy('parent_attr_id')->get();
                            if($getProductparentAttrs){
                                foreach($getProductparentAttrs as $getProductparentAttr){
                                    $parent_attr_id = $getProductparentAttr->parent_attr_id;
                                    $getAttributeName = Attribute::select('name')->where('status', '=', 1)->where('id', '=', $parent_attr_id)->first();
                                    $getProductparentAttrVals = VariationAttribute::select('attribute_id')->where('status', '=', 1)->where('product_id', '=', $getProduct->id)->where('parent_attr_id', '=', $parent_attr_id)->get();
                                    // Helper::pr($getProductparentAttrVals);
                                    $attr_vals = [];
                                    if($getProductparentAttrVals){
                                        foreach($getProductparentAttrVals as $getProductparentAttrVal){
                                            // $dropdownValues['variation' . $parent_attr_id][] = $parent_attr_id . '/' . $getProductparentAttrVal->attribute_id;
                                            $getAttributeValName = AttributeValue::select('attr_value')->where('status', '=', 1)->where('id', '=', $getProductparentAttrVal->attribute_id)->first();
                                            $attr_vals[] = [
                                                'attr_val_id'   => $getProductparentAttrVal->attribute_id,
                                                'attr_val_name' => (($getAttributeValName)?$getAttributeValName->attr_value:''),
                                            ];
                                            
                                        }
                                    }
                                    $dropdownValues[] = [
                                        'attr_id'   => $parent_attr_id,
                                        'attr_name' => (($getAttributeName)?$getAttributeName->name:''),
                                        'attr_vals' => $attr_vals,
                                    ];
                                }
                            }
                            
                            // Function to remove duplicates from nested arrays
                            foreach ($dropdownValues as &$attribute) {
                                $uniqueVals = [];
                                foreach ($attribute['attr_vals'] as $value) {
                                    // Use attr_val_id and attr_val_name as a unique key
                                    $key = $value['attr_val_id'] . '_' . $value['attr_val_name'];
                                    if (!isset($uniqueVals[$key])) {
                                        $uniqueVals[$key] = $value;
                                    }
                                }
                                // Replace attr_vals with unique values
                                $attribute['attr_vals'] = array_values($uniqueVals);
                            }
                            $variations = $dropdownValues;
                            // Helper::pr($variations);
                        // variation
                        // attribute
                            $product_attributes = [];
                            $proAttrs = ProductAttribute::select('product_attribute_id')->where('status', '=', 1)->where('product_id', '=', $getProduct->id)->groupBy('product_attribute_id')->get();
                            if($proAttrs){
                                foreach($proAttrs as $proAttr){
                                    $getAttr                = Attribute::select('name')->where('id', '=', $proAttr->product_attribute_id)->first();
                                    $attribute_vals         = [];
                                    $proAttrVals            = ProductAttribute::select('product_attribute_value_id')->where('status', '=', 1)->where('product_id', '=', $getProduct->id)->where('product_attribute_id', '=', $proAttr->product_attribute_id)->get();
                                    if($proAttrVals){
                                        foreach($proAttrVals as $proAttrVal){
                                            $getAttrVal                = AttributeValue::select('attr_value')->where('id', '=', $proAttrVal->product_attribute_value_id)->first();
                                            $attribute_vals[]          = (($getAttrVal)?$getAttrVal->attr_value:'');
                                        }
                                    }
                                    $product_attributes[]   = [
                                        'attribute_name' => (($getAttr)?$getAttr->name:''),
                                        'attribute_vals' => $attribute_vals
                                    ];
                                }
                            }
                        // attribute
                        $checkProductVariation          = ProductVariation::where('product_id', '=', $getProduct->id)->orderBy('price', 'asc')->first();
                        $product_info       = [
                            'id'                    => $getProduct->id,
                            'name'                  => $getProduct->name,
                            'slug'                  => $getProduct->slug,
                            'main_category'         => $getProduct->main_category,
                            'sub_category'          => $getProduct->sub_category,
                            'product_sku'           => $getProduct->product_sku,
                            'short_description'     => $getProduct->short_description,
                            'long_description'      => $getProduct->long_description,
                            'variation'             => $variations,
                            'product_attributes'    => $product_attributes,
                            'base_price'            => number_format((($checkProductVariation)?$checkProductVariation->price:$getProduct->base_price),2),
                            'price_percentage'      => $getProduct->price_percentage,
                            'markup_price'          => number_format($getProduct->markup_price,2),
                            'parent__category_name' => (($getParentCategory)?$getParentCategory->category_name:''),
                            'sub_category_name'     => $getProduct->sub_category_name,
                            'cover_image'           => env('UPLOADS_URL') . 'product/' . $getProduct->cover_image,
                            'rating'                => number_format($averageRating,1),
                            'product_images'        => $product_images,
                            'review_list'           => $review_list,
                            'is_cart'               => (($checkCart > 0)?1:0),
                            'is_wishlist'           => (($checkWishlist > 0)?1:0),
                            'product_sku'           => $getProduct->product_sku,
                            'product_qty'           => $getProduct->product_qty,
                        ];
                    }
                // product info
                $apiResponse        = [
                    'product_info'              => $product_info,
                    'featured_product_list'     => $featured_product_list,
                ];
                /* view analytics track */
                    $userAgent                      = $request->header('User-Agent', 'unknown');
                    $acceptLanguage                 = $request->header('Accept-Language', 'en');
                    $clientIp                       = $request->ip();
                    $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                    $viewData = [
                        'device_id'     => $deviceId,
                        'page'          => 'product details',
                        'product_id'    => $getProduct->id,
                    ];
                    UserView::insert($viewData);
                /* view analytics track */
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function selectVariation(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'variations'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $variationsArray  = $requestData['variations'];
                // echo $variation1 = $variationsArray[0];
                // echo $variation2 = $variationsArray[1];
                // Query rows with attribute_id = 3 and attribute_id = 11 for the same product_variation_id
                $result = DB::table('variation_attributes')
                            ->select('product_variation_id') // Only select grouped columns
                            ->whereIn('attribute_id', $variationsArray)
                            ->groupBy('product_variation_id')
                            ->havingRaw('COUNT(DISTINCT attribute_id) = 2')
                            ->first();
                if($result){
                    $product_variation_id = $result->product_variation_id;
                    $getVariationInfo = ProductVariation::select('price', 'sku')->where('id', '=', $product_variation_id)->first();
                    if($getVariationInfo){
                        $apiResponse = [
                            'price'     => number_format($getVariationInfo->price,2),
                            'sku'       => $getVariationInfo->sku,
                        ];
                    }
                }                
                /* view analytics track */
                    $userAgent                      = $request->header('User-Agent', 'unknown');
                    $acceptLanguage                 = $request->header('Accept-Language', 'en');
                    $clientIp                       = $request->ip();
                    $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                    $viewData = [
                        'device_id'     => $deviceId,
                        'page'          => 'select variation',
                        'product_id'    => 0,
                    ];
                    UserView::insert($viewData);
                /* view analytics track */
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function addCart(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'product_id', 'product_qty', 'product_rate'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $product_id         = $requestData['product_id'];
                $product_qty        = $requestData['product_qty'];
                $product_rate       = $requestData['product_rate'];
                $variationsArray    = $requestData['variations'];
                $getProduct         = DB::table('products')
                                        ->join('categories', 'products.sub_category', '=', 'categories.id')
                                        ->select('products.*', 'categories.category_name as sub_category_name')
                                        ->where('products.status', '=', 1)
                                        ->where('products.id', '=', $product_id)
                                        ->first();
                if($getProduct){
                    $generalSetting             = GeneralSetting::find('1');
                    $shipping_charge            = $generalSetting->shipping_charge;
                    $tax_percent                = $generalSetting->tax_percent;                    
                    $parent_id                  = [];
                    $parent_id_val              = [];
                    $child_id                   = [];
                    $child_id_val               = [];
                    
                    $userAgent                  = $request->header('User-Agent', 'unknown');
                    $acceptLanguage             = $request->header('Accept-Language', 'en');
                    $clientIp                   = $request->ip();
                    $deviceId                   = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                    $checkProductInCart         = OrderDetail::where('cust_device_id', '=', $deviceId)->where('product_id', '=', $product_id)->where('is_cart', '=', 1)->first();
                    // Helper::pr($variationsArray);
                    /* variation add */
                        $attrName       = [];
                        $variation_name = '';
                        $variation_id   = 0;
                        if(!empty($variationsArray)){
                            for($v=0;$v<count($variationsArray);$v++){
                                $getVariationAttrVal = AttributeValue::select('attr_value')->where('id', '=', $variationsArray[$v])->first();
                                if($getVariationAttrVal){
                                    $attrName[] = $getVariationAttrVal->attr_value;
                                }
                            }
                            $variationCount = count($variationsArray);
                            $productVariationIds = DB::table('variation_attributes')
                                ->whereIn('attribute_id', $variationsArray) // Match the attribute IDs
                                ->select('product_variation_id')
                                ->groupBy('product_variation_id') // Group by product_variation_id
                                ->havingRaw('COUNT(DISTINCT attribute_id) = ' . $variationCount) // Ensure both attribute_ids exist
                                ->pluck('product_variation_id'); // Get the product_variation_ids
                            // Helper::pr($productVariationIds);
                            $variation_name     = implode(', ', $attrName);
                            $variation_id       = (($productVariationIds)?$productVariationIds[0]:0);
                            $product_price      = $product_rate;
                        } else {
                            $checkProductVariation = ProductVariation::where('product_id', '=', $product_id)->orderBy('price', 'asc')->first();
                            if($checkProductVariation){
                                $getVariationAttrs = VariationAttribute::select('value')->where('product_id', '=', $product_id)->where('product_variation_id', '=', $checkProductVariation->id)->get();
                                if($getVariationAttrs){
                                    foreach($getVariationAttrs as $getVariationAttr){
                                        $attrName[] = $getVariationAttr->value;
                                    }
                                }
                                $variation_name     = implode(', ', $attrName);
                                $variation_id       = $checkProductVariation->id;
                                $product_price      = $checkProductVariation->price;
                            } else {
                                $variation_name     = '';
                                $variation_id       = 0;
                                $product_price      = $product_rate;
                            }
                        }
                        // echo $variation_name;die;
                    /* variation add */
                    $total                      = ($product_price * $product_qty);
                    // $shipping_amt               = (($total * $shipping_charge)/100);
                    $shipping_amt               = $getProduct->shipping_rate;
                    $tax_amt                    = (($total * $tax_percent)/100);
                    $net_amt                    = ($total + $shipping_amt + $tax_amt);
                    if($checkProductInCart){
                        $fields = [
                            'parent_id'         => json_encode($parent_id),
                            'parent_id_val'     => json_encode($parent_id_val),
                            'child_id'          => json_encode($child_id),
                            'child_id_val'      => json_encode($child_id_val),
                            'variation_id'      => $variation_id,
                            'variation_name'    => $variation_name,
                            'rate'              => $product_price,
                            'qty'               => $product_qty,
                            'total'             => $total,
                            'subtotal'          => $total,
                            'amount_after_disc' => $total,
                            'shipping_amt'      => $shipping_amt,
                            'tax_amt'           => $tax_amt,
                            'net_amt'           => $net_amt,
                            'is_cart'           => 1,
                        ];
                        OrderDetail::where('id', '=', $checkProductInCart->id)->update($fields);
                        $msg = 'Product Successfully Updated Into Cart !!!';
                    } else {
                        $fields = [
                            'cust_device_id'    => $deviceId,
                            'product_id'        => $product_id,
                            'parent_id'         => json_encode($parent_id),
                            'parent_id_val'     => json_encode($parent_id_val),
                            'child_id'          => json_encode($child_id),
                            'child_id_val'      => json_encode($child_id_val),
                            'variation_id'      => $variation_id,
                            'variation_name'    => $variation_name,
                            'rate'              => $product_price,
                            'qty'               => $product_qty,
                            'total'             => $total,
                            'subtotal'          => $total,
                            'amount_after_disc' => $total,
                            'shipping_amt'      => $shipping_amt,
                            'tax_amt'           => $tax_amt,
                            'net_amt'           => $net_amt,
                            'is_cart'           => 1,
                        ];
                        // Helper::pr($fields);
                        OrderDetail::insert($fields);
                        $msg = 'Product Successfully Added Into Cart !!!';
                    }
                    /* view analytics track */
                        $userAgent                      = $request->header('User-Agent', 'unknown');
                        $acceptLanguage                 = $request->header('Accept-Language', 'en');
                        $clientIp                       = $request->ip();
                        $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                        $viewData = [
                            'device_id'     => $deviceId,
                            'page'          => 'add to cart',
                            'product_id'    => 0,
                        ];
                        UserView::insert($viewData);
                    /* view analytics track */
                    http_response_code(200);
                    $apiStatus          = TRUE;
                    $apiMessage         = $msg;
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                } else {
                    http_response_code(200);
                    $apiStatus          = FALSE;
                    $apiMessage         = 'Product Not Found !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                }
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function createDeviceFingerprint($agent, $language, $ip)
        {
            // Gather data points for the fingerprint
            $userAgent      = $agent ?? 'unknown';
            $acceptLanguage = $language ?? 'unknown';
            $ipAddress      = $ip ?? '127.0.0.1';
            // $timeStamp      = time(); // Add current timestamp
            // $uniqueID       = uniqid('', true); // Add a unique identifier for better randomness
            // Combine data points into a single string
            $fingerprintData = implode('|', [
                $userAgent,
                $acceptLanguage,
                $ipAddress,
                // $timeStamp,
                // $uniqueID
            ]);
            // Generate a SHA-256 hash for better security
            return hash('sha256', $fingerprintData);
        }
        public function getCart(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $userAgent                      = $request->header('User-Agent', 'unknown');
                $acceptLanguage                 = $request->header('Accept-Language', 'en');
                $clientIp                       = $request->ip();
                $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                $cartItems                      = DB::table('order_details')
                                                    ->join('products', 'order_details.product_id', '=', 'products.id')
                                                    ->join('categories', 'products.sub_category', '=', 'categories.id')
                                                    ->select('order_details.*', 'products.name as product_name', 'products.markup_price as product_markup_price', 'products.cover_image as product_cover_image', 'products.product_sku as product_sku', 'categories.category_name as sub_category_name')
                                                    ->where('order_details.cust_device_id', '=', $deviceId)
                                                    ->where('order_details.is_cart', '=', 1)
                                                    ->where('order_details.status', '=', 0)
                                                    ->orderBy('order_details.id', 'DESC')
                                                    ->get();
                // Helper::pr($cartItems);
                $cart_items                     = [];
                $tot_total_amt                  = 0.00;
                $tot_disc_amt                   = 0.00;
                $tot_subtotal_amt               = 0.00;
                $tot_shipping_amt               = 0.00;
                $tot_tax_amt                    = 0.00;
                $tot_net_amt                    = 0.00;
                $coupon_code                    = '';
                $disc_type                      = '';
                if($cartItems){
                    foreach($cartItems as $cartItem){
                        $cart_items[]           = [
                            'cart_id'                       => $cartItem->id,
                            'product_id'                    => $cartItem->product_id,
                            'product_name'                  => $cartItem->product_name,
                            'product_markup_price'          => number_format($cartItem->product_markup_price,2),
                            'product_cover_image'           => env('UPLOADS_URL') . 'product/' . $cartItem->product_cover_image,
                            'product_sku'                   => $cartItem->product_sku,
                            'sub_category_name'             => $cartItem->sub_category_name,
                            'variation_name'                => $cartItem->variation_name,
                            'rate'                          => number_format($cartItem->rate,2),
                            'qty'                           => $cartItem->qty,
                            'total'                         => number_format($cartItem->total,2),
                            'disc_type'                     => $cartItem->disc_type,
                            'disc_amount'                   => number_format($cartItem->disc_amount,2),
                            'subtotal'                      => number_format($cartItem->subtotal,2),
                            'amount_after_disc'             => number_format($cartItem->amount_after_disc,2),
                            'shipping_amt'                  => number_format($cartItem->shipping_amt,2),
                            'tax_amt'                       => number_format($cartItem->tax_amt,2),
                            'net_amt'                       => number_format($cartItem->net_amt,2),
                        ];
                        $tot_total_amt                  += $cartItem->total;
                        $tot_disc_amt                   += $cartItem->disc_amount;
                        $tot_subtotal_amt               += $cartItem->amount_after_disc;
                        $tot_shipping_amt               += $cartItem->shipping_amt;
                        $tot_tax_amt                    += $cartItem->tax_amt;
                        $tot_net_amt                    += $cartItem->net_amt;
                        $coupon_code                    = $cartItem->coupon_code;
                        $disc_type                      = $cartItem->disc_type;
                    }
                }
                $apiResponse[]    = [
                    'cart_items'                => $cart_items,
                    'coupon_code'               => $coupon_code,
                    'disc_type'                 => $disc_type,
                    'tot_total_amt'             => number_format($tot_total_amt,2),
                    'tot_disc_amt'              => number_format($tot_disc_amt,2),
                    'tot_subtotal_amt'          => number_format($tot_subtotal_amt,2),
                    'tot_shipping_amt'          => number_format($tot_shipping_amt,2),
                    'tot_tax_amt'               => number_format($tot_tax_amt,2),
                    'tot_net_amt'               => number_format($tot_net_amt,2)
                ];
                /* view analytics track */
                    $userAgent                      = $request->header('User-Agent', 'unknown');
                    $acceptLanguage                 = $request->header('Accept-Language', 'en');
                    $clientIp                       = $request->ip();
                    $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                    $viewData = [
                        'device_id'     => $deviceId,
                        'page'          => 'cart',
                        'product_id'    => 0,
                    ];
                    UserView::insert($viewData);
                /* view analytics track */
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function cartItemRemove(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'cart_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $cart_id     = $requestData['cart_id'];
                OrderDetail::where('id', '=', $cart_id)->delete();
                /* view analytics track */
                    $userAgent                      = $request->header('User-Agent', 'unknown');
                    $acceptLanguage                 = $request->header('Accept-Language', 'en');
                    $clientIp                       = $request->ip();
                    $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                    $viewData = [
                        'device_id'     => $deviceId,
                        'page'          => 'cart remove',
                        'product_id'    => 0,
                    ];
                    UserView::insert($viewData);
                /* view analytics track */
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Cart Item Removed Successfully !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function updateCartItem(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'cart_id', 'qty'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $cart_id                    = $requestData['cart_id'];
                $qty                        = $requestData['qty'];
                $checkProductInCart         = OrderDetail::where('id', '=', $cart_id)->first();
                $generalSetting             = GeneralSetting::find('1');
                $shipping_charge_percent    = $generalSetting->shipping_charge_percent;
                $tax_percent                = $generalSetting->tax_percent;
                
                $total                      = ($checkProductInCart->rate * $qty);
                $shipping_amt               = (($total * $shipping_charge_percent)/100);
                $tax_amt                    = (($total * $tax_percent)/100);
                $net_amt                    = ($total + $shipping_amt + $tax_amt);
                $fields = [
                    'qty'               => $qty,
                    'total'             => $total,
                    'subtotal'          => $total,
                    'amount_after_disc' => $total,
                    'shipping_amt'      => $shipping_amt,
                    'tax_amt'           => $tax_amt,
                    'net_amt'           => $net_amt,
                    'is_cart'           => 1,
                ];
                OrderDetail::where('id', '=', $cart_id)->update($fields);
                /* view analytics track */
                    $userAgent                      = $request->header('User-Agent', 'unknown');
                    $acceptLanguage                 = $request->header('Accept-Language', 'en');
                    $clientIp                       = $request->ip();
                    $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                    $viewData = [
                        'device_id'     => $deviceId,
                        'page'          => 'update cart',
                        'product_id'    => 0,
                    ];
                    UserView::insert($viewData);
                /* view analytics track */
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Cart Item Updated Successfully !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function searchProduct(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'search_keyword'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                } else {
                    $uId        = 0;
                }
                $search_keyword         = $requestData['search_keyword'];
                $product_list           = [];
                $products               = DB::table('products')
                                            ->join('categories', 'products.sub_category', '=', 'categories.id')
                                            ->select('products.*', 'categories.category_name as sub_category_name')
                                            ->where('products.status', 1) // AND condition
                                            ->where(function ($query) use ($search_keyword) {
                                                $query->orWhere('products.name', 'like', '%' . $search_keyword . '%') // Match column1
                                                      ->orWhere('products.tags', 'like', '%' . $search_keyword . '%') // OR match column2
                                                      ->orWhere('products.short_description', 'like', '%' . $search_keyword . '%')
                                                      ->orWhere('products.long_description', 'like', '%' . $search_keyword . '%'); // OR match column3
                                            })
                                            ->get();
                
                // Helper::pr($products);
                if($products){
                    foreach($products as $product){
                        $averageRating                  = UserReview::where('product_id', $product->id)->where('status', '=', 1)->avg('rating') ?? 0;
                        $userAgent                      = $request->header('User-Agent', 'unknown');
                        $acceptLanguage                 = $request->header('Accept-Language', 'en');
                        $clientIp                       = $request->ip();
                        $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                        $checkCart                      = OrderDetail::where('cust_device_id', '=', $deviceId)->where('product_id', '=', $product->id)->where('is_cart', '=', 1)->count();
                        $checkWishlist                  = UserWishlist::where('user_id', '=', $uId)->where('product_id', '=', $product->id)->count();
                        $checkProductVariation          = ProductVariation::where('product_id', '=', $product->id)->orderBy('price', 'asc')->first();
                        $apiResponse[]                  = [
                            'id'                    => $product->id,
                            'name'                  => $product->name,
                            'slug'                  => $product->slug,
                            'base_price'            => number_format((($checkProductVariation)?$checkProductVariation->price:$product->discounted_price),2),
                            'price_percentage'      => $product->price_percentage,
                            'discount_amount'       => $product->discount_amount,
                            'markup_price'          => number_format($product->base_price,2),
                            'sub_category_name'     => $product->sub_category_name,
                            'cover_image'           => env('UPLOADS_URL') . 'product/' . $product->cover_image,
                            'rating'                => round($averageRating),
                            'is_cart'               => (($checkCart > 0)?1:0),
                            'is_wishlist'           => (($checkWishlist > 0)?1:0),
                            'product_sku'           => $product->product_sku,
                            'product_qty'           => $product->product_qty,
                        ];
                    }
                }
                /* view analytics track */
                    $userAgent                      = $request->header('User-Agent', 'unknown');
                    $acceptLanguage                 = $request->header('Accept-Language', 'en');
                    $clientIp                       = $request->ip();
                    $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                    $viewData = [
                        'device_id'     => $deviceId,
                        'page'          => 'product search',
                        'product_id'    => 0,
                    ];
                    UserView::insert($viewData);
                /* view analytics track */
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Search Result Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function searchSuggestion(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'search_keyword'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                // $app_access_token           = $headerData['authorization'][0];
                // $getTokenValue              = $this->tokenAuth($app_access_token);
                // if($getTokenValue['status']){
                //     $uId        = $getTokenValue['data'][1];
                // } else {
                //     $uId        = 0;
                // }
                $search_keyword         = $requestData['search_keyword'];
                $product_list           = [];
                $products               = DB::table('products')
                                            ->join('categories', 'products.sub_category', '=', 'categories.id')
                                            ->select('products.*', 'categories.category_name as sub_category_name')
                                            ->where('products.status', 1) // AND condition
                                            ->where(function ($query) use ($search_keyword) {
                                                $query->orWhere('products.name', 'like', '%' . $search_keyword . '%') // Match column1
                                                      ->orWhere('products.tags', 'like', '%' . $search_keyword . '%') // OR match column2
                                                      ->orWhere('products.short_description', 'like', '%' . $search_keyword . '%')
                                                      ->orWhere('products.long_description', 'like', '%' . $search_keyword . '%') // OR match column3
                                                      ->orWhere('products.product_sku', 'like', '%' . $search_keyword . '%'); // OR match column3
                                            })
                                            ->get();
                
                // Helper::pr($products);
                if($products){
                    foreach($products as $product){
                        $averageRating                  = UserReview::where('product_id', $product->id)->where('status', '=', 1)->avg('rating') ?? 0;
                        $userAgent                      = $request->header('User-Agent', 'unknown');
                        $acceptLanguage                 = $request->header('Accept-Language', 'en');
                        $clientIp                       = $request->ip();
                        $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                        $checkCart                      = OrderDetail::where('cust_device_id', '=', $deviceId)->where('product_id', '=', $product->id)->where('is_cart', '=', 1)->count();
                        // $checkWishlist                  = UserWishlist::where('user_id', '=', $uId)->where('product_id', '=', $product->id)->count();
                        $checkWishlist                  = UserWishlist::where('product_id', '=', $product->id)->count();
                        $checkProductVariation          = ProductVariation::where('product_id', '=', $product->id)->orderBy('price', 'asc')->first();
                        $apiResponse[]                  = [
                            'id'                    => $product->id,
                            'name'                  => $product->name,
                            'slug'                  => $product->slug,
                            'base_price'            => number_format((($checkProductVariation)?$checkProductVariation->price:$product->discounted_price),2),
                            'cover_image'           => env('UPLOADS_URL') . 'product/' . $product->cover_image,
                            'rating'                => round($averageRating),
                            'product_sku'           => $product->product_sku,
                            'product_qty'           => $product->product_qty,
                            'is_cart'               => (($checkCart > 0)?1:0),
                            'is_wishlist'           => (($checkWishlist > 0)?1:0),
                            'product_link'          => url('admin/product/edit/' . Helper::encoded($product->id)),
                            'frontend_product_link' => url('product/'.$product->slug.'/' . Helper::encoded($product->id)),
                        ];
                    }
                }
                /* view analytics track */
                    $userAgent                      = $request->header('User-Agent', 'unknown');
                    $acceptLanguage                 = $request->header('Accept-Language', 'en');
                    $clientIp                       = $request->ip();
                    $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                    $viewData = [
                        'device_id'     => $deviceId,
                        'page'          => 'product search suggestion',
                        'product_id'    => 0,
                    ];
                    UserView::insert($viewData);
                /* view analytics track */
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Search Suggestions Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function applyCoupon(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'coupon_code'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $userAgent                      = $request->header('User-Agent', 'unknown');
                $acceptLanguage                 = $request->header('Accept-Language', 'en');
                $clientIp                       = $request->ip();
                $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                $coupon_code                    = $requestData['coupon_code'];
                $getCoupon                      = Coupon::where('coupon_code', '=', $coupon_code)->where('status', '!=', 3)->first();
                if($getCoupon){
                    if($getCoupon->status){
                        if($getCoupon->end_date >= date('Y-m-d')){
                            $discount_type      = $getCoupon->discount_type;
                            $discount_amount    = $getCoupon->discount_amount;
                            $minimum_amount     = $getCoupon->minimum_amount;
                            $category           = $getCoupon->category;
                            $totalCartValue     = OrderDetail::select('subtotal')->where('cust_device_id', '=', $deviceId)->where('is_cart', '=', 1)->where('status', '=', 0)->sum('subtotal');
                            // Helper::pr($totalCartValue);
                            if($totalCartValue >= $minimum_amount){
                                if($category <= 0){
                                    $cartItems              = OrderDetail::where('cust_device_id', '=', $deviceId)->where('is_cart', '=', 1)->where('status', '=', 0)->get();
                                    if($cartItems){
                                        foreach($cartItems as $cartItem){
                                            $subtotal = $cartItem->subtotal;
                                            if($discount_type == 'PERCENTAGE'){
                                                $discAmt = (($subtotal * $discount_amount)/100);
                                            } else {
                                                $discAmt = $discount_amount;
                                            }
                                            $amount_after_disc = ($subtotal - $discAmt);
                                            $generalSetting = GeneralSetting::find('1');
                                            $shipping_charge_percent = $generalSetting->shipping_charge_percent;
                                            $tax_percent    = $generalSetting->tax_percent;
                                            
                                            $shipping_amt   = (($amount_after_disc * $shipping_charge_percent)/100);
                                            $tax_amt        = (($amount_after_disc * $tax_percent)/100);
                                            $net_amt        = ($amount_after_disc + $shipping_amt + $tax_amt);
                                            $couponData = [
                                                'coupon_code'       => $coupon_code,
                                                'disc_type'         => $discount_type,
                                                'disc_amount'       => $discAmt,
                                                'amount_after_disc' => $amount_after_disc,
                                                'shipping_amt'      => $shipping_amt,
                                                'tax_amt'           => $tax_amt,
                                                'net_amt'           => $net_amt,
                                            ];
                                            OrderDetail::where('id', '=', $cartItem->id)->update($couponData);
                                        }
                                        // $request->session()->put('is_coupon', 1);
                                        // $request->session()->put('sess_coupon_code', $coupon_code);
                                        // $request->session()->put('sess_disc_type', $discount_type);
                                    }
                                    http_response_code(200);
                                    $apiStatus          = TRUE;
                                    $apiMessage         = 'Coupon Applied Successfully !!!';
                                    $apiExtraField      = 'response_code';
                                    $apiExtraData       = http_response_code();
                                } else {
                                    $cartItems              = OrderDetail::where('cust_device_id', '=', $deviceId)->where('is_cart', '=', 1)->where('status', '=', 0)->get();
                                    if($cartItems){
                                        $p=0;
                                        foreach($cartItems as $cartItem){
                                            $getProduct = Product::where('id', '=', $cartItem->product_id)->first();
                                            if($getProduct){
                                                if($getProduct->sub_category == $category){
                                                    $subtotal = $cartItem->subtotal;
                                                    if($discount_type == 'PERCENTAGE'){
                                                        $discAmt = (($subtotal * $discount_amount)/100);
                                                    } else {
                                                        $discAmt = $discount_amount;
                                                    }
                                                    $amount_after_disc = ($subtotal - $discAmt);
                                                    $generalSetting = GeneralSetting::find('1');
                                                    $shipping_charge_percent = $generalSetting->shipping_charge_percent;
                                                    $tax_percent    = $generalSetting->tax_percent;
                                                    
                                                    $shipping_amt   = (($amount_after_disc * $shipping_charge_percent)/100);
                                                    $tax_amt        = (($amount_after_disc * $tax_percent)/100);
                                                    $net_amt        = ($amount_after_disc + $shipping_amt + $tax_amt);
                                                    $couponData = [
                                                        'coupon_code'       => $coupon_code,
                                                        'disc_type'         => $discount_type,
                                                        'disc_amount'       => $discAmt,
                                                        'amount_after_disc' => $amount_after_disc,
                                                        'shipping_amt'      => $shipping_amt,
                                                        'tax_amt'           => $tax_amt,
                                                        'net_amt'           => $net_amt,
                                                    ];
                                                    OrderDetail::where('id', '=', $cartItem->id)->update($couponData);
                                                    $p++;
                                                }
                                            }
                                        }
                                        if($p > 0){
                                            /* view analytics track */
                                                $userAgent                      = $request->header('User-Agent', 'unknown');
                                                $acceptLanguage                 = $request->header('Accept-Language', 'en');
                                                $clientIp                       = $request->ip();
                                                $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                                                $viewData = [
                                                    'device_id'     => $deviceId,
                                                    'page'          => 'apply coupon',
                                                    'product_id'    => 0,
                                                ];
                                                UserView::insert($viewData);
                                            /* view analytics track */
                                            http_response_code(200);
                                            $apiStatus          = TRUE;
                                            $apiMessage         = 'Coupon Applied Successfully !!!';
                                            $apiExtraField      = 'response_code';
                                            $apiExtraData       = http_response_code();
                                        } else {
                                            http_response_code(200);
                                            $apiStatus          = FALSE;
                                            $apiMessage         = 'Coupon Code Not Applied Due To Product Category Mismatched !!!';
                                            $apiExtraField      = 'response_code';
                                            $apiExtraData       = http_response_code();
                                        }
                                    }
                                }
                            } else {
                                http_response_code(200);
                                $apiStatus          = FALSE;
                                $apiMessage         = 'Cart Value Should Be Minimum '.$minimum_amount.' To Apply This Coupon Code !!!';
                                $apiExtraField      = 'response_code';
                                $apiExtraData       = http_response_code();
                            }
                        } else {
                            http_response_code(200);
                            $apiStatus          = FALSE;
                            $apiMessage         = 'Coupon Code Expired !!!';
                            $apiExtraField      = 'response_code';
                            $apiExtraData       = http_response_code();
                        }
                    } else {
                        http_response_code(200);
                        $apiStatus          = FALSE;
                        $apiMessage         = 'Coupon Code Deactivated !!!';
                        $apiExtraField      = 'response_code';
                        $apiExtraData       = http_response_code();
                    }
                } else {
                    http_response_code(200);
                    $apiStatus          = FALSE;
                    $apiMessage         = 'Coupon Code Not Available !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                }
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function removeCoupon(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $userAgent                      = $request->header('User-Agent', 'unknown');
                $acceptLanguage                 = $request->header('Accept-Language', 'en');
                $clientIp                       = $request->ip();
                $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                $cartItems                      = OrderDetail::where('cust_device_id', '=', $deviceId)->where('is_cart', '=', 1)->where('status', '=', 0)->get();
                if($cartItems){
                    foreach($cartItems as $cartItem){
                        $subtotal                   = $cartItem->subtotal;
                        $amount_after_disc          = $subtotal;
                        $generalSetting             = GeneralSetting::find('1');
                        $shipping_charge_percent    = $generalSetting->shipping_charge_percent;
                        $tax_percent                = $generalSetting->tax_percent;
                        
                        $shipping_amt               = (($amount_after_disc * $shipping_charge_percent)/100);
                        $tax_amt                    = (($amount_after_disc * $tax_percent)/100);
                        $net_amt                    = ($amount_after_disc + $shipping_amt + $tax_amt);
                        $couponData = [
                            'coupon_code'       => '',
                            'disc_type'         => NULL,
                            'disc_amount'       => 0,
                            'amount_after_disc' => $amount_after_disc,
                            'shipping_amt'      => $shipping_amt,
                            'tax_amt'           => $tax_amt,
                            'net_amt'           => $net_amt,
                        ];
                        OrderDetail::where('id', '=', $cartItem->id)->update($couponData);
                    }
                }
                /* view analytics track */
                    $userAgent                      = $request->header('User-Agent', 'unknown');
                    $acceptLanguage                 = $request->header('Accept-Language', 'en');
                    $clientIp                       = $request->ip();
                    $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                    $viewData = [
                        'device_id'     => $deviceId,
                        'page'          => 'remove coupon',
                        'product_id'    => 0,
                    ];
                    UserView::insert($viewData);
                /* view analytics track */
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Coupon Removed Successfully !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
    /* before login screen */
    /* authentication */
        public function signup(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'first_name', 'last_name', 'email', 'phone', 'password', 'confirm_password'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $first_name                         = $requestData['first_name'];
                $last_name                          = $requestData['last_name'];
                $email                              = $requestData['email'];
                $phone                              = $requestData['phone'];
                $password                           = $requestData['password'];
                $confirm_password                   = $requestData['confirm_password'];
                
                $checkUser                          = User::where('email', '=', $email)->where('status', '!=', 3)->first();
                if(!$checkUser){
                    $checkUserPhone                 = User::where('phone', '=', $phone)->where('status', '!=', 3)->first();
                    if(!$checkUserPhone){
                        if($password == $confirm_password){
                            $remember_token  = rand(1000,9999);
                            $fields     = [
                                'type'              => 1,
                                'first_name'        => $first_name,
                                'last_name'         => $last_name,
                                'email'             => $email,
                                'phone'             => $phone,
                                'display_name'      => $first_name.' '.$last_name,
                                'password'          => Hash::make($password),
                                'status'            => 0,
                                'remember_token'    => $remember_token,
                            ];
                            // Helper::pr($fields);
                            $user_id = User::insertGetId($fields);
                            
                            $mailData                   = [
                                'id'                => $user_id,
                                'email'             => $email,
                                'otp'               => $remember_token,
                            ];
                            $generalSetting             = GeneralSetting::find('1');
                            $subject                    = $generalSetting->site_name.' :: Signup Validate OTP';
                            $message                    = view('email-templates.otp',$mailData);
                            // echo $message;die;
                            $this->sendMail($requestData['email'], $subject, $message);
                            $apiResponse                        = $mailData;
                            $apiStatus                          = TRUE;

                            /* view analytics track */
                                $userAgent                      = $request->header('User-Agent', 'unknown');
                                $acceptLanguage                 = $request->header('Accept-Language', 'en');
                                $clientIp                       = $request->ip();
                                $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                                $viewData = [
                                    'device_id'     => $deviceId,
                                    'page'          => 'signup',
                                    'product_id'    => 0,
                                ];
                                UserView::insert($viewData);
                            /* view analytics track */
                            http_response_code(200);
                            $apiMessage                         = 'OTP Sent To Email Validation !!!';
                            $apiExtraField                      = 'response_code';
                            $apiExtraData                       = http_response_code();
                        } else {
                            $apiStatus                          = FALSE;
                            $apiMessage                         = 'Password & Confirm Password Mismatched !!!';
                            http_response_code(400);
                            $apiExtraField                      = 'response_code';
                            $apiExtraData                       = http_response_code();
                        }
                    } else {
                        $apiStatus                              = FALSE;
                        $apiMessage                             = 'Phone Number Already Exists !!!';
                        http_response_code(400);
                        $apiExtraField                          = 'response_code';
                        $apiExtraData                           = http_response_code();
                    }                 
                } else {
                    $apiStatus                              = FALSE;
                    $apiMessage                             = 'Email Already Exists !!!';
                    http_response_code(400);
                    $apiExtraField                          = 'response_code';
                    $apiExtraData                           = http_response_code();
                }
            } else {
                $apiStatus                          = FALSE;
                $apiMessage                         = 'Unauthenticate Request !!!';
                http_response_code(400);
                $apiExtraField                      = 'response_code';
                $apiExtraData                       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function signupValidate(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'id', 'otp'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $getUser = User::where('id', '=', $requestData['id'])->first();
                if($getUser){
                    $remember_token  = $getUser->remember_token;
                    if($remember_token == $requestData['otp']){
                        User::where('id', '=', $requestData['id'])->update(['remember_token' => 0, 'status' => 1]);
                        // $this->sendMail('subhomoysamanta1989@gmail.com', $requestData['subject'], $requestData['message']);
                        $mailData        = [
                            'id'        => $getUser->id,
                            'name'      => $getUser->first_name.' '.$getUser->last_name,
                            'email'     => $getUser->email
                        ];
                        $generalSetting             = GeneralSetting::find('1');
                        $subject                    = $generalSetting->site_name.' :: Signup Completed';
                        $message                    = view('email-templates.signup',$mailData);
                        // echo $message;die;
                        $this->sendMail($getUser->email, $subject, $message);
                        $apiResponse        = [
                            'user_id'               => $getUser->id,
                            'name'                  => $getUser->first_name.' '.$getUser->last_name,
                            'email'                 => $getUser->email,
                            'phone'                 => $getUser->phone,
                            'role'                  => 'USER'
                        ];
                        $apiStatus                          = TRUE;
                        /* view analytics track */
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $viewData = [
                                'device_id'     => $deviceId,
                                'page'          => 'signup validate',
                                'product_id'    => 0,
                            ];
                            UserView::insert($viewData);
                        /* view analytics track */
                        http_response_code(200);
                        $apiMessage                         = 'OTP Validated & Signup Successfully Completed !!!';
                        $apiExtraField                      = 'response_code';
                        $apiExtraData                       = http_response_code();
                    } else {
                        $apiStatus          = FALSE;
                        http_response_code(400);
                        $apiMessage         = 'OTP Mismatched !!!';
                        $apiExtraField      = 'response_code';
                    }
                } else {
                    $apiStatus          = FALSE;
                    http_response_code(400);
                    $apiMessage         = 'User Not Found !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                }
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function signin(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'email', 'password', 'device_token', 'fcm_token'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $email                      = $requestData['email'];
                $password                   = $requestData['password'];
                $device_type                = $headerData['source'][0];
                $device_token               = $requestData['device_token'];
                $fcm_token                  = $requestData['fcm_token'];
                // $checkUser                  = User::where('email', '=', $email)->where('status', '=', 1)->first();
                $checkUser                  = User::where(function($query) {
                                                $query->where('status', 1);
                                             })
                                             ->where(function($query) use ($email) {
                                                $query->where('email', $email)
                                                      ->orWhere('phone', $email);
                                             })
                                             ->first();
                if($checkUser){
                    if(Hash::check($password, $checkUser->password)){
                        $objOfJwt           = new CreatorJwt();
                        $app_access_token   = $objOfJwt->GenerateToken($checkUser->id, $checkUser->email, $checkUser->phone);
                        $user_id                        = $checkUser->id;
                        $fields     = [
                            'user_id'               => $user_id,
                            'device_type'           => $device_type,
                            'device_token'          => $device_token,
                            'fcm_token'             => $fcm_token,
                            'app_access_token'      => $app_access_token,
                        ];
                        $checkUserTokenExist            = UserDevice::where('user_id', '=', $user_id)->where('published', '=', 1)->where('device_type', '=', $device_type)->where('device_token', '=', $device_token)->first();
                        if(!$checkUserTokenExist){
                            UserDevice::insert($fields);
                        } else {
                            UserDevice::where('id','=',$checkUserTokenExist->id)->update($fields);
                        }
                        $apiResponse = [
                            'user_id'               => $user_id,
                            'name'                  => $checkUser->first_name.' '.$checkUser->last_name,
                            'email'                 => $checkUser->email,
                            'phone'                 => $checkUser->phone,
                            'role'                  => 'USER',
                            'device_type'           => $device_type,
                            'device_token'          => $device_token,
                            'fcm_token'             => $fcm_token,
                            'app_access_token'      => $app_access_token,
                        ];
                        /* view analytics track */
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $viewData = [
                                'device_id'     => $deviceId,
                                'page'          => 'signin',
                                'product_id'    => 0,
                            ];
                            UserView::insert($viewData);
                        /* view analytics track */
                        $apiStatus                          = TRUE;
                        $apiMessage                         = 'SignIn Successfully !!!';
                    } else {
                        $apiStatus                          = FALSE;
                        $apiMessage                         = 'Invalid Password !!!';
                    }                   
                } else {
                    $apiStatus                              = FALSE;
                    $apiMessage                             = 'We Don\'t Recognize You !!!';
                }
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function forgotPassword(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'email'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $checkEmail = User::where('email', '=', $requestData['email'])->first();
                if($checkEmail){
                    $remember_token  = rand(1000,9999);
                    User::where('id', '=', $checkEmail->id)->update(['remember_token' => $remember_token]);
                    $mailData                   = [
                        'id'                => $checkEmail->id,
                        'email'             => $checkEmail->email,
                        'otp'               => $remember_token,
                    ];
                    $generalSetting             = GeneralSetting::find('1');
                    $subject                    = $generalSetting->site_name.' :: Forgot Password OTP';
                    $message                    = view('email-templates.otp',$mailData);
                    // echo $message;die;
                    $this->sendMail($requestData['email'], $subject, $message);
                    $apiResponse                        = $mailData;
                    $apiStatus                          = TRUE;
                    /* view analytics track */
                        $userAgent                      = $request->header('User-Agent', 'unknown');
                        $acceptLanguage                 = $request->header('Accept-Language', 'en');
                        $clientIp                       = $request->ip();
                        $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                        $viewData = [
                            'device_id'     => $deviceId,
                            'page'          => 'forgot password',
                            'product_id'    => 0,
                        ];
                        UserView::insert($viewData);
                    /* view analytics track */
                    http_response_code(200);
                    $apiMessage                         = 'OTP Sent To Email Validation !!!';
                    $apiExtraField                      = 'response_code';
                    $apiExtraData                       = http_response_code();
                } else {
                    $apiStatus          = FALSE;
                    http_response_code(400);
                    $apiMessage         = 'Email Not Registered With Us !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                }
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function validateOtp(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'id', 'otp'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $getUser = User::where('id', '=', $requestData['id'])->first();
                if($getUser){
                    $remember_token  = $getUser->remember_token;
                    if($remember_token == $requestData['otp']){
                        User::where('id', '=', $requestData['id'])->update(['remember_token' => 0]);
                        // $this->sendMail('subhomoysamanta1989@gmail.com', $requestData['subject'], $requestData['message']);
                        $apiResponse        = [
                            'id'    => $getUser->id,
                            'email' => $getUser->email
                        ];
                        /* view analytics track */
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $viewData = [
                                'device_id'     => $deviceId,
                                'page'          => 'validate OTP',
                                'product_id'    => 0,
                            ];
                            UserView::insert($viewData);
                        /* view analytics track */
                        $apiStatus                          = TRUE;
                        http_response_code(200);
                        $apiMessage                         = 'OTP Validated Successfully !!!';
                        $apiExtraField                      = 'response_code';
                        $apiExtraData                       = http_response_code();
                    } else {
                        $apiStatus          = FALSE;
                        http_response_code(400);
                        $apiMessage         = 'OTP Mismatched !!!';
                        $apiExtraField      = 'response_code';
                    }
                } else {
                    $apiStatus          = FALSE;
                    http_response_code(400);
                    $apiMessage         = 'User Not Found !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                }
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function resendOtp(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $id         = $requestData['id'];
                $getUser    = User::where('id', '=', $id)->first();
                if($getUser){
                    $remember_token = rand(1000,9999);
                    $postData = [
                        'remember_token'        => $remember_token
                    ];
                    User::where('id', '=', $id)->update($postData);
                    
                    $mailData                   = [
                        'id'                => $getUser->id,
                        'email'             => $getUser->email,
                        'otp'               => $remember_token,
                    ];
                    $generalSetting             = GeneralSetting::find('1');
                    $subject                    = $generalSetting->site_name.' :: Resend OTP';
                    $message                    = view('email-templates.otp',$mailData);
                    // echo $message;die;
                    $this->sendMail($getUser->email, $subject, $message);
                    $apiResponse                        = $mailData;

                    /* view analytics track */
                        $userAgent                      = $request->header('User-Agent', 'unknown');
                        $acceptLanguage                 = $request->header('Accept-Language', 'en');
                        $clientIp                       = $request->ip();
                        $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                        $viewData = [
                            'device_id'     => $deviceId,
                            'page'          => 'resend OTP',
                            'product_id'    => 0,
                        ];
                        UserView::insert($viewData);
                    /* view analytics track */
                    $apiStatus                          = TRUE;
                    http_response_code(200);
                    $apiMessage                         = 'OTP Resend !!!';
                    $apiExtraField                      = 'response_code';
                    $apiExtraData                       = http_response_code();
                } else {
                    $apiStatus          = FALSE;
                    http_response_code(400);
                    $apiMessage         = 'User Not Found !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                }
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function resetPassword(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'id', 'password', 'confirm_password'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $getUser = User::where('id', '=', $requestData['id'])->first();
                if($getUser){
                    if($requestData['password'] == $requestData['confirm_password']){
                        User::where('id', '=', $requestData['id'])->update(['password' => Hash::make($requestData['password'])]);
                        $mailData        = [
                            'id'        => $getUser->id,
                            'name'      => $getUser->first_name.' '.$getUser->last_name,
                            'email'     => $getUser->email
                        ];
                        $generalSetting             = GeneralSetting::find('1');
                        $subject                    = $generalSetting->site_name.' :: Reset Password';
                        $message                    = view('email-templates.change-password',$mailData);
                        // echo $message;die;
                        $this->sendMail($getUser->email, $subject, $message);
                        /* view analytics track */
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $viewData = [
                                'device_id'     => $deviceId,
                                'page'          => 'reset password',
                                'product_id'    => 0,
                            ];
                            UserView::insert($viewData);
                        /* view analytics track */
                        $apiStatus                          = TRUE;
                        http_response_code(200);
                        $apiMessage                         = 'Password Reset Successfully !!!';
                        $apiExtraField                      = 'response_code';
                        $apiExtraData                       = http_response_code();
                    } else {
                        $apiStatus          = FALSE;
                        http_response_code(400);
                        $apiMessage         = 'Password & Confirm Password Not Matched !!!';
                        $apiExtraField      = 'response_code';
                    }
                } else {
                    $apiStatus          = FALSE;
                    http_response_code(400);
                    $apiMessage         = 'User Not Found !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                }
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
    /* authentication */
    /* after login */
        public function signout(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = [];
            $headerData         = $request->header();
            // Helper::pr($headerData);
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $checkUserTokenExist        = UserDevice::where('app_access_token', '=', $app_access_token)->where('published', '=', 1)->first();
                if($checkUserTokenExist){
                    UserDevice::where('app_access_token', '=', $app_access_token)->delete();
                    /* view analytics track */
                        $userAgent                      = $request->header('User-Agent', 'unknown');
                        $acceptLanguage                 = $request->header('Accept-Language', 'en');
                        $clientIp                       = $request->ip();
                        $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                        $viewData = [
                            'device_id'     => $deviceId,
                            'page'          => 'signout',
                            'product_id'    => 0,
                        ];
                        UserView::insert($viewData);
                    /* view analytics track */
                    $apiStatus                      = TRUE;
                    $apiMessage                     = 'Signout Successfully !!!';
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = 'Something Went Wrong !!!';
                }               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function dashboard(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $checkUserTokenExist        = UserDevice::where('app_access_token', '=', $app_access_token)->where('published', '=', 1)->first();
                if($checkUserTokenExist){
                    $getTokenValue              = $this->tokenAuth($app_access_token);
                    if($getTokenValue['status']){
                        $uId        = $getTokenValue['data'][1];
                        $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                        $getUser    = User::where('id', '=', $uId)->first();
                        if($getUser){
                            $order_count            = Order::where('cust_id', '=', $uId)->count();
                            $wishlist_count         = UserWishlist::where('user_id', '=', $uId)->count();
                            $approved_review_count  = UserReview::where('user_id', '=', $uId)->where('status', '=', 1)->count();
                            $pending_review_count   = UserReview::where('user_id', '=', $uId)->where('status', '=', 0)->count();
                            $billing_address        = UserLocation::where('user_id', '=', $uId)->where('status', '!=', 3)->where('type', '=', 'BILLING')->count();
                            $shipping_address       = UserLocation::where('user_id', '=', $uId)->where('status', '!=', 3)->where('type', '=', 'SHIPPING')->count();
                            $apiResponse            = [
                                'order_count'                   => $order_count,
                                'wishlist_count'                => $wishlist_count,
                                'approved_review_count'         => $approved_review_count,
                                'pending_review_count'          => $pending_review_count,
                                'billing_address'               => $billing_address,
                                'shipping_address'              => $shipping_address,
                            ];
                            /* view analytics track */
                                $userAgent                      = $request->header('User-Agent', 'unknown');
                                $acceptLanguage                 = $request->header('Accept-Language', 'en');
                                $clientIp                       = $request->ip();
                                $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                                $viewData = [
                                    'device_id'     => $deviceId,
                                    'page'          => 'dashboard',
                                    'product_id'    => 0,
                                ];
                                UserView::insert($viewData);
                            /* view analytics track */
                            $apiStatus          = TRUE;
                            $apiMessage         = 'Data Available !!!';
                        } else {
                            $apiStatus          = FALSE;
                            $apiMessage         = 'User Not Found !!!';
                        }
                    } else {
                        $apiStatus                      = FALSE;
                        $apiMessage                     = $getTokenValue['data'];
                        http_response_code(401);
                        $apiExtraData                   = http_response_code();
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = 'Something Went Wrong !!!';
                }               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function changePassword(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $old_password               = $requestData['old_password'];
                $new_password               = $requestData['new_password'];
                $confirm_password           = $requestData['confirm_password'];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        if(Hash::check($old_password, $getUser->password)){
                            if($new_password == $confirm_password){
                                if($new_password != $old_password){
                                    $fields = [
                                        'password'                  => Hash::make($new_password)
                                    ];
                                    User::where('id', '=', $uId)->update($fields);
                                    // new password send mail
                                        $generalSetting                 = GeneralSetting::find('1');
                                        $subject                        = $generalSetting->site_name.' Change Password';
                                        $mailData['name']               = $getUser->first_name.' '.$getUser->last_name;
                                        $mailData['email']              = $getUser->email;
                                        $html                           = view('email-templates/change-password', $mailData);
                                        $this->sendMail($getUser->email, $subject, $html);
                                        // echo $html;die;
                                    // new password send mail
                                        /* view analytics track */
                                            $userAgent                      = $request->header('User-Agent', 'unknown');
                                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                                            $clientIp                       = $request->ip();
                                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                                            $viewData = [
                                                'device_id'     => $deviceId,
                                                'page'          => 'change password',
                                                'product_id'    => 0,
                                            ];
                                            UserView::insert($viewData);
                                        /* view analytics track */
                                    $apiStatus          = TRUE;
                                    $apiMessage         = 'Password Updated Successfully !!!';
                                } else {
                                    $apiStatus          = FALSE;
                                    $apiMessage         = 'Current & New Password Should Not Be Same !!!';
                                }
                            } else {
                                $apiStatus          = FALSE;
                                $apiMessage         = 'New & Confirm Password Doesn\'t Matched !!!';
                            }
                        } else {
                            $apiStatus          = FALSE;
                            $apiMessage         = 'Current Password Doesn\'t Matched !!!';
                        }
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getProfile(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        $profileData    = [
                            'first_name'            => $getUser->first_name,
                            'last_name'             => $getUser->last_name,
                            'email'                 => $getUser->email,
                            'phone'                 => $getUser->phone,
                            'display_name'          => $getUser->display_name,
                            'profile_image'         => (($getUser->profile_image != '')?env('UPLOADS_URL').'user/'.$getUser->profile_image:env('NO_IMAGE')),
                        ];
                        /* view analytics track */
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $viewData = [
                                'device_id'     => $deviceId,
                                'page'          => 'get profile',
                                'product_id'    => 0,
                            ];
                            UserView::insert($viewData);
                        /* view analytics track */
                        $apiStatus          = TRUE;
                        $apiMessage         = 'Data Available !!!';
                        $apiResponse        = $profileData;
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function editProfile(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        $profileData    = [
                            'first_name'            => $getUser->first_name,
                            'last_name'             => $getUser->last_name,
                            'email'                 => $getUser->email,
                            'phone'                 => $getUser->phone,
                            'display_name'          => $getUser->display_name,
                            'profile_image'         => (($getUser->profile_image != '')?env('UPLOADS_URL').'user/'.$getUser->profile_image:env('NO_IMAGE')),
                        ];
                        /* view analytics track */
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $viewData = [
                                'device_id'     => $deviceId,
                                'page'          => 'edit profile',
                                'product_id'    => 0,
                            ];
                            UserView::insert($viewData);
                        /* view analytics track */
                        $apiStatus          = TRUE;
                        $apiMessage         = 'Data Available !!!';
                        $apiResponse        = $profileData;
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function updateProfile(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();       
            $requiredFields     = ['key', 'source', 'first_name', 'last_name', 'display_name', 'phone'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $request->header('Authorization');
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        // $profile_image  = $requestData['profile_image'];
                        // if(!empty($profile_image)){
                        //     $profile_image      = $profile_image;
                        //     $upload_type        = $profile_image['type'];
                        //     if($upload_type == 'image/jpeg' || $upload_type == 'image/jpg' || $upload_type == 'image/png' || $upload_type == 'image/gif'){
                        //         $upload_base64      = $profile_image['base64'];
                        //         $img                = $upload_base64;
                        //         $proof_type         = $profile_image['type'];
                        //         if($proof_type == 'image/png'){
                        //             $extn = 'png';
                        //         } elseif($proof_type == 'image/jpg'){
                        //             $extn = 'jpg';
                        //         } elseif($proof_type == 'image/jpeg'){
                        //             $extn = 'jpeg';
                        //         } elseif($proof_type == 'image/gif'){
                        //             $extn = 'gif';
                        //         } else {
                        //             $extn = 'png';
                        //         }
                        //         $data               = base64_decode($img);
                        //         $fileName           = uniqid() . '.' . $extn;
                        //         $file               = 'public/uploads/user/' . $fileName;
                        //         $success            = file_put_contents($file, $data);
                        //         $profile_image      = $fileName;
                        //     } else {
                        //         $apiStatus          = FALSE;
                        //         http_response_code(404);
                        //         $apiMessage         = 'Please Upload Image !!!';
                        //         $apiExtraField      = 'response_code';
                        //         $apiExtraData       = http_response_code();
                        //     }
                        // } else {
                        //     $profile_image = $getUser->profile_image;
                        // }
                        /* banner image */
                            $imageFile      = $request->file('profile_image');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('profile_image', $imageName, 'user', 'image');
                                if($uploadedFile['status']){
                                    $profile_image = $uploadedFile['newFilename'];
                                } else {
                                    $apiStatus          = FALSE;
                                    http_response_code(404);
                                    $apiMessage         = 'Please Upload Image !!!';
                                    $apiExtraField      = 'response_code';
                                    $apiExtraData       = http_response_code();
                                }
                            } else {
                                $profile_image = $getUser->profile_image;
                            }
                        /* banner image */
                        $postData = [
                                    'first_name'                    => $requestData['first_name'],
                                    'last_name'                     => $requestData['last_name'],
                                    // 'email'                         => $requestData['country'],
                                    'phone'                         => $requestData['phone'],
                                    'display_name'                  => $requestData['first_name'].' '.$requestData['last_name'],
                                    'profile_image'                 => $profile_image
                                ];
                        // Helper::pr($postData);
                        User::where('id', '=', $uId)->update($postData);
                        /* view analytics track */
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $viewData = [
                                'device_id'     => $deviceId,
                                'page'          => 'update profile',
                                'product_id'    => 0,
                            ];
                            UserView::insert($viewData);
                        /* view analytics track */
                        $apiStatus                  = TRUE;
                        $apiMessage                 = 'Profile Updated Successfully !!!';
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function uploadProfileImage(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['profile_image'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $request->header('Authorization');
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        $profile_image  = $requestData['profile_image'];
                        // if($profile_image != ''){
                        //     /* upload profile image */        
                        //         $upload_file    = $profile_image;
                        //         Helper::pr($upload_file);
                        //         // $img            = $upload_file['base64'];
                        //         $img            = str_replace('data:image/jpeg;base64,', '', $upload_file);
                        //         $img            = str_replace(' ', '+', $img);
                        //         $data           = base64_decode($img);
                        //         $fileName       = uniqid() . '.jpg';
                        //         $file           = 'public/uploads/user/' . $fileName;
                        //         $success        = file_put_contents($file, $data);
                        //         $profile_image  = $fileName;
                        //     /* upload profile image */
                        // } else {
                        //     $profile_image = $getUser->profile_image;
                        // }
                        if(!empty($profile_image)){
                            $profile_image      = $profile_image;
                            $upload_type        = $profile_image['type'];
                            if($upload_type == 'image/jpeg' || $upload_type == 'image/jpg' || $upload_type == 'image/png' || $upload_type == 'image/gif'){
                                $upload_base64      = $profile_image['base64'];
                                $img                = $upload_base64;
                                $proof_type         = $profile_image['type'];
                                if($proof_type == 'image/png'){
                                    $extn = 'png';
                                } elseif($proof_type == 'image/jpg'){
                                    $extn = 'jpg';
                                } elseif($proof_type == 'image/jpeg'){
                                    $extn = 'jpeg';
                                } elseif($proof_type == 'image/gif'){
                                    $extn = 'gif';
                                } else {
                                    $extn = 'png';
                                }
                                $data               = base64_decode($img);
                                $fileName           = uniqid() . '.' . $extn;
                                $file               = 'public/uploads/user/' . $fileName;
                                $success            = file_put_contents($file, $data);
                                $profile_image      = $fileName;
                            } else {
                                $apiStatus          = FALSE;
                                http_response_code(404);
                                $apiMessage         = 'Please Upload Image !!!';
                                $apiExtraField      = 'response_code';
                                $apiExtraData       = http_response_code();
                            }
                        } else {
                            $profile_image = $getUser->photo;
                        }
                        $postData = [
                                    'photo'         => $profile_image
                                ];
                        // Helper::pr($postData);
                        User::where('id', '=', $uId)->update($postData);
                        $apiStatus                  = TRUE;
                        $apiMessage                 = 'Profile Image Uploaded Successfully !!!';
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getAddress(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        $billingLocations   = UserLocation::where('status', '=', 1)->where('user_id', '=', $uId)->where('type', '=', 'BILLING')->get();
                        $shippingLocations  = UserLocation::where('status', '=', 1)->where('user_id', '=', $uId)->where('type', '=', 'SHIPPING')->get();
                        $billings           = [];
                        $shippings          = [];
                        if($billingLocations){
                            foreach($billingLocations as $row){
                                $billings[]           = [
                                    'title'         => $row->title,
                                    'name'          => $row->name,
                                    'email'         => $row->email,
                                    'phone'         => $row->phone,
                                    'address'       => $row->address,
                                    'country'       => $row->country,
                                    'state'         => $row->state,
                                    'city'          => $row->city,
                                    'locality'      => $row->locality,
                                    'street_no'     => $row->street_no,
                                    'zipcode'       => $row->zipcode,
                                    'lat'           => $row->lat,
                                    'lng'           => $row->lng,
                                    'address_id'    => $row->id,
                                ];
                            }
                        }
                        if($shippingLocations){
                            foreach($shippingLocations as $row){
                                $shippings[]           = [
                                    'title'         => $row->title,
                                    'name'          => $row->name,
                                    'email'         => $row->email,
                                    'phone'         => $row->phone,
                                    'address'       => $row->address,
                                    'country'       => $row->country,
                                    'state'         => $row->state,
                                    'city'          => $row->city,
                                    'locality'      => $row->locality,
                                    'street_no'     => $row->street_no,
                                    'zipcode'       => $row->zipcode,
                                    'lat'           => $row->lat,
                                    'lng'           => $row->lng,
                                    'address_id'    => $row->id,
                                ];
                            }
                        }
                        /* view analytics track */
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $viewData = [
                                'device_id'     => $deviceId,
                                'page'          => 'get address',
                                'product_id'    => 0,
                            ];
                            UserView::insert($viewData);
                        /* view analytics track */
                        $apiResponse    = [
                            'billings'            => $billings,
                            'shippings'           => $shippings,
                        ];
                        $apiStatus          = TRUE;
                        $apiMessage         = 'Data Available !!!';
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function addAddress(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();       
            $requiredFields     = ['key', 'source', 'type', 'title', 'address', 'country', 'state', 'city', 'locality', 'street_no', 'zipcode', 'lat', 'lng', 'name', 'email', 'phone'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $request->header('Authorization');
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        $postData = [
                                    'user_id'                       => $uId,
                                    'type'                          => $requestData['type'],
                                    'title'                         => $requestData['title'],
                                    'name'                          => $requestData['name'],
                                    'email'                         => $requestData['email'],
                                    'phone'                         => $requestData['phone'],
                                    'address'                       => $requestData['address'],
                                    'country'                       => $requestData['country'],
                                    'state'                         => $requestData['state'],
                                    'city'                          => $requestData['city'],
                                    'locality'                      => $requestData['locality'],
                                    'street_no'                     => $requestData['street_no'],
                                    'zipcode'                       => $requestData['zipcode'],
                                    'lat'                           => $requestData['lat'],
                                    'lng'                           => $requestData['lng'],
                                ];
                        // Helper::pr($postData);
                        UserLocation::insert($postData);
                        /* view analytics track */
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $viewData = [
                                'device_id'     => $deviceId,
                                'page'          => 'add address',
                                'product_id'    => 0,
                            ];
                            UserView::insert($viewData);
                        /* view analytics track */
                        $apiStatus                  = TRUE;
                        $apiMessage                 = $requestData['type'] . ' Address Inserted Successfully !!!';
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function deleteAddress(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();       
            $requiredFields     = ['key', 'source', 'address_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $request->header('Authorization');
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        UserLocation::where('id', '=', $requestData['address_id'])->update(['status' => 3]);
                        /* view analytics track */
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $viewData = [
                                'device_id'     => $deviceId,
                                'page'          => 'address delete',
                                'product_id'    => 0,
                            ];
                            UserView::insert($viewData);
                        /* view analytics track */
                        $apiStatus                  = TRUE;
                        $apiMessage                 = 'Address Deleted Successfully !!!';
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function deleteAccount(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $checkUserTokenExist        = UserDevice::where('app_access_token', '=', $app_access_token)->where('published', '=', 1)->first();
                if($checkUserTokenExist){
                    $getTokenValue              = $this->tokenAuth($app_access_token);
                    if($getTokenValue['status']){
                        $uId        = $getTokenValue['data'][1];
                        $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                        $getUser    = User::where('id', '=', $uId)->first();
                        if($getUser){
                            $fields = [
                                'user_type'                 => 'user',
                                'entity_name'               => $getUser->name,
                                'email'                     => $getUser->email,
                                'is_email_verify'           => 1,
                                'phone'                     => $getUser->phone,
                                'is_phone_verify'           => 1,
                            ];
                            DeleteAccountRequest::insert($fields);
                            $apiStatus          = TRUE;
                            $apiMessage         = 'Account Delete Requests Submitted Successfully !!!';
                        } else {
                            $apiStatus          = FALSE;
                            $apiMessage         = 'User Not Found !!!';
                        }
                    } else {
                        $apiStatus                      = FALSE;
                        $apiMessage                     = $getTokenValue['data'];
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = 'Something Went Wrong !!!';
                }               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getReview(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        $reviews   = DB::table('user_reviews')
                                                ->join('products', 'user_reviews.product_id', '=', 'products.id')
                                                ->join('users', 'user_reviews.user_id', '=', 'users.id')
                                                ->select('user_reviews.*', 'products.name as product_name', 'users.profile_image', 'products.cover_image as product_cover_image', 'products.id as product_id')
                                                ->where('user_reviews.status', '!=', 3)
                                                ->where('user_reviews.user_id', '=', $uId)
                                                ->orderBy('user_reviews.id', 'DESC')
                                                ->get();
                        if($reviews){
                            foreach($reviews as $row){
                                $apiResponse[]           = [
                                    'product_id'                        => $row->product_id,
                                    'product_name'                      => $row->product_name,
                                    'product_cover_image'               => (($row->product_cover_image != '')?env('UPLOADS_URL').'product/'.$row->product_cover_image:env('NO_IMAGE')),
                                    'name'                              => $row->name,
                                    'email'                             => $row->email,
                                    'user_profile_image'                => (($row->profile_image != '')?env('UPLOADS_URL').'user/'.$row->profile_image:env('NO_IMAGE')),
                                    'title'                             => $row->title,
                                    'rating'                            => $row->rating,
                                    'comment'                           => $row->comment,
                                    'status'                            => (($row->status)?'APPROVED':'REJECTED'),
                                    'approve_reject_timestamp'          => $row->approve_reject_timestamp
                                ];
                            }
                        }
                        /* view analytics track */
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $viewData = [
                                'device_id'     => $deviceId,
                                'page'          => 'review list',
                                'product_id'    => 0,
                            ];
                            UserView::insert($viewData);
                        /* view analytics track */
                        $apiStatus          = TRUE;
                        $apiMessage         = 'Data Available !!!';
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getWishlist(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        $items   = DB::table('user_wishlists')
                                                ->join('products', 'user_wishlists.product_id', '=', 'products.id')
                                                ->select('user_wishlists.*', 'products.name as product_name', 'products.base_price as product_base_price', 'products.markup_price as product_markup_price', 'products.cover_image')
                                                ->where('user_wishlists.status', '=', 1)
                                                ->where('user_wishlists.user_id', '=', $uId)
                                                ->orderBy('user_wishlists.id', 'DESC')
                                                ->get();
                        if($items){
                            foreach($items as $row){
                                $apiResponse[]           = [
                                    'wishlist_id'                       => $row->id,
                                    'product_id'                        => $row->product_id,
                                    'product_name'                      => $row->product_name,
                                    'product_cover_image'               => env('UPLOADS_URL') . 'product/' . $row->cover_image,
                                    'product_base_price'                => number_format($row->product_base_price,2),
                                    'product_markup_price'              => number_format($row->product_markup_price,2),
                                    'timestamp'                         => $row->created_at,
                                ];
                            }
                        }
                        /* view analytics track */
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $viewData = [
                                'device_id'     => $deviceId,
                                'page'          => 'wishlist',
                                'product_id'    => 0,
                            ];
                            UserView::insert($viewData);
                        /* view analytics track */
                        $apiStatus          = TRUE;
                        $apiMessage         = 'Data Available !!!';
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function deleteWishlist(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();       
            $requiredFields     = ['key', 'source', 'wishlist_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $request->header('Authorization');
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        UserWishlist::where('id', '=', $requestData['wishlist_id'])->delete();
                        /* activity track */
                            $getProduct = Product::select('name')->where('id', '=', $requestData['product_id'])->first();
                            $activityData = [
                                'user_id'       => $uId,
                                'activity_type' => 'wishlist',
                                'product_id'    => $requestData['product_id'],
                                'comment'       => $getUser->first_name . '' . $getUser->last_name . ' deleted an item ' . (($getProduct)?$getProduct->name:'') . ' from wishlist',
                            ];
                            UserWebsiteActivity::insert($activityData);
                        /* activity track */
                        /* view analytics track */
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $viewData = [
                                'device_id'     => $deviceId,
                                'page'          => 'wishlst delete',
                                'product_id'    => 0,
                            ];
                            UserView::insert($viewData);
                        /* view analytics track */
                        $apiStatus                  = TRUE;
                        $apiMessage                 = 'Wishlist Product Deleted Successfully !!!';
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function addWishlist(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();       
            $requiredFields     = ['key', 'source', 'product_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $request->header('Authorization');
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        $checkWishlist = UserWishlist::where('user_id', '=', $uId)->where('product_id', '=', $requestData['product_id'])->first();
                        if(!$checkWishlist){
                            $fields = [
                                'user_id'       => $uId,
                                'product_id'    => $requestData['product_id'],
                            ];
                            UserWishlist::insert($fields);
                            /* activity track */
                                $getProduct = Product::select('name')->where('id', '=', $requestData['product_id'])->first();
                                $activityData = [
                                    'user_id'       => $uId,
                                    'activity_type' => 'wishlist',
                                    'product_id'    => $requestData['product_id'],
                                    'comment'       => $getUser->first_name . '' . $getUser->last_name . ' wishlisted an item ' . (($getProduct)?$getProduct->name:''),
                                ];
                                UserWebsiteActivity::insert($activityData);
                            /* activity track */
                            /* view analytics track */
                                $userAgent                      = $request->header('User-Agent', 'unknown');
                                $acceptLanguage                 = $request->header('Accept-Language', 'en');
                                $clientIp                       = $request->ip();
                                $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                                $viewData = [
                                    'device_id'     => $deviceId,
                                    'page'          => 'add wishlist',
                                    'product_id'    => $requestData['product_id'],
                                ];
                                UserView::insert($viewData);
                            /* view analytics track */
                            $apiStatus                  = TRUE;
                            $apiMessage                 = 'Product Added Into Wishlist Successfully !!!';
                        } else {
                            UserWishlist::where('id', '=', $checkWishlist->id)->delete();
                            $apiStatus                  = TRUE;
                            $apiMessage                 = 'Product Deleted From Wishlist Successfully !!!';
                        }
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function addReview(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();       
            $requiredFields     = ['key', 'source', 'product_id', 'name', 'email', 'rating', 'title', 'comment'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $request->header('Authorization');
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        $checkReview = UserReview::where('user_id', '=', $uId)->where('product_id', '=', $requestData['product_id'])->count();
                        if($checkReview <= 0){
                            $fields = [
                                'user_id'       => $uId,
                                'product_id'    => $requestData['product_id'],
                                'name'          => $requestData['name'],
                                'email'         => $requestData['email'],
                                'rating'        => $requestData['rating'],
                                'title'         => $requestData['title'],
                                'comment'       => $requestData['comment'],
                            ];
                            UserReview::insert($fields);
                            /* activity track */
                                $getProduct = Product::select('name')->where('id', '=', $requestData['product_id'])->first();
                                $activityData = [
                                    'user_id'       => $uId,
                                    'activity_type' => 'review',
                                    'product_id'    => $requestData['product_id'],
                                    'comment'       => $getUser->first_name . '' . $getUser->last_name . ' reviewed an item ' . (($getProduct)?$getProduct->name:''),
                                ];
                                UserWebsiteActivity::insert($activityData);
                            /* activity track */
                            /* view analytics track */
                                $userAgent                      = $request->header('User-Agent', 'unknown');
                                $acceptLanguage                 = $request->header('Accept-Language', 'en');
                                $clientIp                       = $request->ip();
                                $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                                $viewData = [
                                    'device_id'     => $deviceId,
                                    'page'          => 'add review',
                                    'product_id'    => $requestData['product_id'],
                                ];
                                UserView::insert($viewData);
                            /* view analytics track */
                            $apiStatus                  = TRUE;
                            $apiMessage                 = 'Product Review Submitted Successfully. Wait For Admin Approval !!!';
                        } else {
                            $apiStatus                  = FALSE;
                            $apiMessage                 = 'You Cant Give Review More Than One Time On A Single Product !!!';
                        }
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function checkout(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();       
            $requiredFields     = [];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $request->header('Authorization');
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        $userAgent                      = $request->header('User-Agent', 'unknown');
                        $acceptLanguage                 = $request->header('Accept-Language', 'en');
                        $clientIp                       = $request->ip();
                        $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                        OrderDetail::where('cust_device_id', '=', $deviceId)->update(['cust_id' => $uId]);
                        /* cart items */
                            $cartItems                      = DB::table('order_details')
                                                                ->join('products', 'order_details.product_id', '=', 'products.id')
                                                                ->join('categories', 'products.sub_category', '=', 'categories.id')
                                                                ->select('order_details.*', 'products.name as product_name', 'products.markup_price as product_markup_price', 'products.cover_image as product_cover_image', 'products.product_sku as product_sku', 'categories.category_name as sub_category_name')
                                                                ->where('order_details.cust_id', '=', $uId)
                                                                ->where('order_details.is_cart', '=', 1)
                                                                ->where('order_details.status', '=', 0)
                                                                ->orderBy('order_details.id', 'DESC')
                                                                ->get();
                            // Helper::pr($cartItems);
                            $cart_items                     = [];
                            $tot_subtotal_amt               = 0.00;
                            $tot_disc_amt                   = 0.00;
                            $tot_amt_after_disc             = 0.00;
                            $tot_shipping_amt               = 0.00;
                            $tot_tax_amt                    = 0.00;
                            $tot_net_amt                    = 0.00;
                            if($cartItems){
                                foreach($cartItems as $cartItem){
                                    $cart_items[]           = [
                                        'cart_id'                       => $cartItem->id,
                                        'product_name'                  => $cartItem->product_name,
                                        'product_markup_price'          => number_format($cartItem->product_markup_price,2),
                                        'product_cover_image'           => env('UPLOADS_URL') . 'product/' . $cartItem->product_cover_image,
                                        'product_sku'                   => $cartItem->product_sku,
                                        'sub_category_name'             => $cartItem->sub_category_name,
                                        'variation_name'                => $cartItem->variation_name,
                                        'rate'                          => number_format($cartItem->rate,2),
                                        'qty'                           => $cartItem->qty,
                                        'total'                         => number_format($cartItem->total,2),
                                        'disc_type'                     => $cartItem->disc_type,
                                        'disc_amount'                   => number_format($cartItem->disc_amount,2),
                                        'subtotal'                      => number_format($cartItem->subtotal,2),
                                        'amount_after_disc'             => number_format($cartItem->amount_after_disc,2),
                                        'shipping_amt'                  => number_format($cartItem->shipping_amt,2),
                                        'tax_amt'                       => number_format($cartItem->tax_amt,2),
                                        'net_amt'                       => number_format($cartItem->net_amt,2),
                                    ];
                                    $tot_subtotal_amt               += $cartItem->subtotal;
                                    $tot_disc_amt                   += $cartItem->disc_amount;
                                    $tot_amt_after_disc             += $cartItem->amount_after_disc;
                                    $tot_shipping_amt               += $cartItem->shipping_amt;
                                    $tot_tax_amt                    += $cartItem->tax_amt;
                                    $tot_net_amt                    += $cartItem->net_amt;
                                }
                            }
                        /* cart items */
                        /* billing & shipping address */
                            $billingLocations   = UserLocation::where('status', '=', 1)->where('user_id', '=', $uId)->where('type', '=', 'BILLING')->get();
                            $shippingLocations  = UserLocation::where('status', '=', 1)->where('user_id', '=', $uId)->where('type', '=', 'SHIPPING')->get();
                            $billings           = [];
                            $shippings          = [];
                            if($billingLocations){
                                foreach($billingLocations as $row){
                                    $billings[]           = [
                                        'title'         => $row->title,
                                        'name'          => $row->name,
                                        'email'         => $row->email,
                                        'phone'         => $row->phone,
                                        'address'       => $row->address,
                                        'country'       => $row->country,
                                        'state'         => $row->state,
                                        'city'          => $row->city,
                                        'locality'      => $row->locality,
                                        'street_no'     => $row->street_no,
                                        'zipcode'       => $row->zipcode,
                                        'lat'           => $row->lat,
                                        'lng'           => $row->lng,
                                        'address_id'    => $row->id,
                                    ];
                                }
                            }
                            if($shippingLocations){
                                foreach($shippingLocations as $row){
                                    $shippings[]           = [
                                        'title'         => $row->title,
                                        'name'          => $row->name,
                                        'email'         => $row->email,
                                        'phone'         => $row->phone,
                                        'address'       => $row->address,
                                        'country'       => $row->country,
                                        'state'         => $row->state,
                                        'city'          => $row->city,
                                        'locality'      => $row->locality,
                                        'street_no'     => $row->street_no,
                                        'zipcode'       => $row->zipcode,
                                        'lat'           => $row->lat,
                                        'lng'           => $row->lng,
                                        'address_id'    => $row->id,
                                    ];
                                }
                            }
                        /* billing & shipping address */
                        $apiResponse[]    = [
                            'cart_items'                => $cart_items,
                            'billings'                  => $billings,
                            'shippings'                 => $shippings,
                            'tot_subtotal_amt'          => number_format($tot_subtotal_amt,2),
                            'tot_disc_amt'              => number_format($tot_disc_amt,2),
                            'tot_amt_after_disc'        => number_format($tot_amt_after_disc,2),
                            'tot_shipping_amt'          => number_format($tot_shipping_amt,2),
                            'tot_tax_amt'               => number_format($tot_tax_amt,2),
                            'tot_net_amt'               => number_format($tot_net_amt,2)
                        ];
                        /* view analytics track */
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $viewData = [
                                'device_id'     => $deviceId,
                                'page'          => 'checkout',
                                'product_id'    => 0,
                            ];
                            UserView::insert($viewData);
                        /* view analytics track */
                        $apiStatus          = TRUE;
                        $apiMessage         = 'Data Available !!!';
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function placeOrder(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();       
            $requiredFields     = ['key', 'source', 'payment_method', 'checkout_type'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $request->header('Authorization');
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        $userAgent                      = $request->header('User-Agent', 'unknown');
                        $acceptLanguage                 = $request->header('Accept-Language', 'en');
                        $clientIp                       = $request->ip();
                        $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                        $getLastEnquiry                 = Order::orderBy('id', 'DESC')->first();
                        if($getLastEnquiry){
                            $sl_no              = $getLastEnquiry->sl_no;
                            $next_sl_no         = $sl_no + 1;
                            $next_sl_no_string  = str_pad($next_sl_no, 7, 0, STR_PAD_LEFT);
                            $order_no           = 'TCG-'.$next_sl_no_string;
                        } else {
                            $next_sl_no         = 1;
                            $next_sl_no_string  = str_pad($next_sl_no, 7, 0, STR_PAD_LEFT);
                            $order_no           = 'TCG-'.$next_sl_no_string;
                        }
                        $payment_method = $requestData['payment_method'];
                        $checkout_type  = $requestData['checkout_type'];
                        if($checkout_type == 'EXISTING'){
                            $getShippingAddr    = [];
                            $getCustomer        = User::where('id', '=', $uId)->first();
                            if (array_key_exists("billing",$requestData)){
                                $getBillingAddr = UserLocation::where('id', '=', $requestData['billing'])->first();
                            }
                            
                            if (array_key_exists("shipping",$requestData)){
                                $getShippingAddr = UserLocation::where('id', '=', $requestData['shipping'])->first();
                            }
        
                            $b_fname        = (($getCustomer)?$getCustomer->first_name:'');
                            $b_lname        = (($getCustomer)?$getCustomer->last_name:'');
                            $b_phone        = (($getCustomer)?$getCustomer->phone:'');
                            $b_email        = (($getCustomer)?$getCustomer->email:'');
                            $b_company      = (($getBillingAddr)?$getBillingAddr->title:'');
                            $b_country      = (($getBillingAddr)?$getBillingAddr->country:'');
                            $b_street       = (($getBillingAddr)?$getBillingAddr->address:'');
                            $b_suburb       = (($getBillingAddr)?$getBillingAddr->city:'');
                            $b_state        = (($getBillingAddr)?$getBillingAddr->state:'');
                            $b_postcode     = (($getBillingAddr)?$getBillingAddr->zipcode:'');
        
                            $s_fname        = (($getCustomer)?$getCustomer->first_name:(($getCustomer)?$getCustomer->first_name:''));
                            $s_lname        = (($getCustomer)?$getCustomer->last_name:(($getCustomer)?$getCustomer->last_name:''));
                            $s_phone        = (($getCustomer)?$getCustomer->phone:(($getCustomer)?$getCustomer->phone:''));
                            $s_email        = (($getCustomer)?$getCustomer->email:(($getCustomer)?$getCustomer->email:''));
                            $s_company      = (($getShippingAddr)?$getShippingAddr->title:(($getBillingAddr)?$getBillingAddr->title:''));
                            $s_country      = (($getShippingAddr)?$getShippingAddr->country:(($getBillingAddr)?$getBillingAddr->country:''));
                            $s_street       = (($getShippingAddr)?$getShippingAddr->address:(($getBillingAddr)?$getBillingAddr->address:''));
                            $s_suburb       = (($getShippingAddr)?$getShippingAddr->city:(($getBillingAddr)?$getBillingAddr->city:''));
                            $s_state        = (($getShippingAddr)?$getShippingAddr->state:(($getBillingAddr)?$getBillingAddr->state:''));
                            $s_postcode     = (($getShippingAddr)?$getShippingAddr->zipcode:(($getBillingAddr)?$getBillingAddr->zipcode:''));
                        } else {
                            $b_fname        = $requestData['b_fname'];
                            $b_lname        = $requestData['b_lname'];
                            $b_phone        = $requestData['b_phone'];
                            $b_email        = $requestData['b_email'];
                            $b_company      = $requestData['b_company'];
                            $b_country      = $requestData['b_country'];
                            $b_street       = $requestData['b_street'];
                            $b_suburb       = $requestData['b_suburb'];
                            $b_state        = $requestData['b_state'];
                            $b_postcode     = $requestData['b_postcode'];
                            $s_fname        = $requestData['s_fname'];
                            $s_lname        = $requestData['s_lname'];
                            $s_phone        = $requestData['s_phone'];
                            $s_email        = $requestData['s_email'];
                            $s_company      = $requestData['s_company'];
                            $s_country      = $requestData['s_country'];
                            $s_street       = $requestData['s_street'];
                            $s_suburb       = $requestData['s_suburb'];
                            $s_state        = $requestData['s_state'];
                            $s_postcode     = $requestData['s_postcode'];
                        }
                        $fields1 = [
                            'sl_no'             => $next_sl_no,
                            'order_no'          => $order_no,
                            'cust_device_id'    => $deviceId,
                            'cust_id'           => (($uId != '')?$uId:0),
                            'cust_fname'        => $b_fname,
                            'cust_lname'        => $b_lname,
                            'cust_phone'        => $b_phone,
                            'cust_email'        => $b_email,
                            'order_date'        => date('Y-m-d'),
                            'order_time'        => date('H:i:s'),
                            'b_fname'           => $b_fname,
                            'b_lname'           => $b_lname,
                            'b_phone'           => $b_phone,
                            'b_email'           => $b_email,
                            'b_company'         => $b_company,
                            'b_country'         => $b_country,
                            'b_street'          => $b_street,
                            'b_suburb'          => $b_suburb,
                            'b_state'           => $b_state,
                            'b_postcode'        => $b_postcode,
                            's_fname'           => $s_fname,
                            's_lname'           => $s_lname,
                            's_phone'           => $s_phone,
                            's_email'           => $s_email,
                            's_company'         => $s_company,
                            's_country'         => $s_country,
                            's_street'          => $s_street,
                            's_suburb'          => $s_suburb,
                            's_state'           => $s_state,
                            's_postcode'        => $s_postcode,
                            'subtotal'          => $requestData['subtotal'],
                            'coupon_code'       => '',
                            'disc_type'         => 'FLAT',
                            'disc_amount'       => $requestData['disc_amount'],
                            'amount_after_disc' => $requestData['amount_after_disc'],
                            'shipping_amt'      => $requestData['shipping_amt'],
                            'tax_amt'           => $requestData['tax_amt'],
                            'net_amt'           => $requestData['net_amt'],
                            'payment_mode'      => $payment_method,
                            'checkout_type'     => $requestData['checkout_type'],
                        ];
                        // Helper::pr($fields1);die;
                        $order_id = Order::insertGetId($fields1);
                        if($order_id){
                            $fields2 = [
                                'order_id'  => $order_id,
                                'cust_id'   => (($uId != '')?$uId:0),
                                'is_cart'   => 0,
                                'status'    => 1,
                            ];
                            OrderDetail::where('cust_device_id', '=', $deviceId)->where('order_id', '=', 0)->where('is_cart', '=', 1)->update($fields2);
                        }
                        /* stock update */
                            $getOrderDetails    = OrderDetail::where('order_id', '=', $order_id)->get();
                            if($getOrderDetails){
                                foreach($getOrderDetails as $getOrderDetail){
                                    $product_id     = $getOrderDetail->product_id;
                                    $variation_id   = $getOrderDetail->variation_id;
                                    $qty            = $getOrderDetail->qty;
                                    if($variation_id > 0){
                                        $getStock = ProductVariation::select('qty')->where('product_id', '=', $product_id)->where('id', '=', $variation_id)->first();
                                        if($getStock){
                                            $oldQty = $getStock->qty;
                                            $newQty = ($oldQty - $qty);
                                            ProductVariation::where('product_id', '=', $product_id)->where('id', '=', $variation_id)->update(['qty' => $newQty]);
                                        }
                                        $getStock2 = Product::select('product_qty')->where('id', '=', $product_id)->first();
                                        if($getStock2){
                                            $oldQty2 = $getStock2->product_qty;
                                            $newQty2 = ($oldQty2 - $qty);
                                            Product::where('id', '=', $product_id)->update(['product_qty' => $newQty2]);
                                        }
                                    } else {
                                        $getStock = Product::select('product_qty')->where('id', '=', $product_id)->first();
                                        if($getStock){
                                            $oldQty = $getStock->product_qty;
                                            $newQty = ($oldQty - $qty);
                                            Product::where('id', '=', $product_id)->update(['product_qty' => $newQty]);
                                        }
                                    }
                                    /* activity track */
                                        $getProduct = Product::select('name')->where('id', '=', $product_id)->first();
                                        $activityData = [
                                            'user_id'       => $uId,
                                            'activity_type' => 'order',
                                            'product_id'    => $product_id,
                                            'comment'       => $getUser->first_name . '' . $getUser->last_name . ' ordered an item ' . (($getProduct)?$getProduct->name:''),
                                        ];
                                        UserWebsiteActivity::insert($activityData);
                                    /* activity track */
                                }
                            }
                        /* stock update */
                        $apiStatus          = TRUE;
                        /* view analytics track */
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $viewData = [
                                'device_id'     => $deviceId,
                                'page'          => 'place order',
                                'product_id'    => 0,
                            ];
                            UserView::insert($viewData);
                        /* view analytics track */
                        $apiMessage         = 'Order Placed Successfully !!!';
                        $getOrder           = Order::where('id', '=', $order_id)->first();
                        $apiResponse[]      = [
                            'order_id'                  => $order_id,
                            'order_no'                  => $order_no,
                            'payment_mode'              => $payment_method,
                            'net_amt'                   => $requestData['net_amt'],
                            'payment_status'            => (($getOrder)?$getOrder->payment_status:''),
                            'payment_txn_no'            => (($getOrder)?$getOrder->payment_txn_no:''),
                            'payment_date_time'         => (($getOrder)?$getOrder->payment_date_time:''),
                        ];
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function paymentProcess(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'card_number', 'expiry_date', 'card_code', 'order_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $request->header('Authorization');
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        // Helper::pr($requestData);
                        $card_number    = $requestData['card_number'];
                        $expiry_date    = '20' . $requestData['expiry_date'];
                        $card_code      = $requestData['card_code'];
                        $order_id       = $requestData['order_id'];
                        $getOrder       = Order::where('id', '=', $order_id)->first();
                        if($getOrder){
                            // $request->validate([
                            //     'card_number' => 'required|string',
                            //     'expiry_date' => 'required|string',
                            //     'card_code' => 'required|string',
                            //     'amount' => 'required|numeric',
                            //     'user_details.first_name' => 'required|string',
                            //     'user_details.last_name' => 'required|string',
                            //     'user_details.address' => 'string',
                            //     'user_details.city' => 'string',
                            //     'user_details.state' => 'string',
                            //     'user_details.zip' => 'string',
                            //     'user_details.country' => 'required|string',
                            //     'user_details.email' => 'required|email',
                            //     'order_details.invoice_number' => 'required|string',
                            //     'order_details.description' => 'required|string'
                            // ]);
                            $user_details = [
                                "first_name"    => $getOrder->b_fname, 
                                "last_name"     => $getOrder->b_lname, 
                                "address"       => $getOrder->b_suburb, 
                                "city"          => $getOrder->b_suburb, 
                                "state"         => $getOrder->b_state, 
                                "zip"           => $getOrder->b_postcode, 
                                "country"       => $getOrder->b_country, 
                                "email"         => $getOrder->b_email 
                            ];
                            $order_details = [
                                "invoice_number"    => $getOrder->order_no, 
                                "description"       => $getOrder->order_no . " order payment"
                            ];
                            $result = $this->paymentService->processPayment(
                                $card_number,
                                $expiry_date,
                                $card_code,
                                $getOrder->net_amt,
                                $user_details,
                                $order_details
                            );
                            
                            if ($result['success']) {
                                // return response()->json([
                                //     'success' => true,
                                //     'transaction_id' => $result['transaction_id']
                                // ]);
                                $fields = [
                                    'payment_status'        => 1,
                                    'payment_txn_no'        => $result['transaction_id'],
                                    'payment_date_time'     => date('Y-m-d H:i:s'),
                                    'currency'              => 'USD',
                                    'particulars'           => $getOrder->order_no . " order payment",
                                    'card_last_4_digits'    => substr($card_number, -4),
                                    'expiry_month'          => explode("-", $expiry_date)[1],
                                    'expiry_year'           => explode("-", $expiry_date)[0],
                                ];
                                // Helper::pr($fields);
                                Order::where('id', '=', $order_id)->update($fields);
                                $getOrder   = DB::table('orders')
                                                ->join('users', 'orders.cust_id', '=', 'users.id')
                                                ->select('orders.*', 'users.first_name', 'users.last_name', 'users.email')
                                                ->where('orders.cust_id', '=', $uId)
                                                ->where('orders.id', '=', $order_id)
                                                ->first();
                                /* generate inspection pdf & save it to directory */
                                    $enquiry_no                     = (($getOrder)?$getOrder->order_no:'');
                                    $data['generalSetting']         = GeneralSetting::find('1');
                                    $data['getOrderDetail']         = $getOrder;
                                    $subject                        = $data['generalSetting']->site_name . ' Invoice' . $enquiry_no;
                                    $message                        = view('email-templates.print-invoice',$data);
                                    // Initialize Dompdf with options
                                    $options    = new Options();
                                    $options->set('defaultFont', 'Courier');
                                    $dompdf     = new Dompdf($options);
                                    // Load HTML content
                                    $html       = $message;
                                    // You can also load HTML content from a file or URL
                                    $dompdf->loadHtml($html);
                                    // (Optional) Set up the paper size and orientation
                                    $dompdf->setPaper('A4', 'portrait');
                                    // Render the HTML as PDF
                                    $dompdf->render();
                                    // Get the generated PDF content
                                    $output = $dompdf->output();
                                    // Output the generated PDF to browser
                                    // $dompdf->stream("document.pdf", array("Attachment" => false));die;
                                    // Define the path where the PDF will be saved
                                    $filename   = $enquiry_no.'.pdf';
                                    $pdfFilePath = 'public/uploads/orders/' . $filename;
                                    // Save the PDF to a file
                                    file_put_contents($pdfFilePath, $output);
                                    Order::where('id', '=', $order_id)->update(['invoice_pdf' => $filename]);
                                /* generate inspection pdf & save it to directory */
                                /* order details items */
                                    OrderDetail::where('order_id', '=', $order_id)->update(['cust_id' => $uId]);
                                    $cartItems                      = DB::table('order_details')
                                                                        ->join('products', 'order_details.product_id', '=', 'products.id')
                                                                        ->join('categories', 'products.sub_category', '=', 'categories.id')
                                                                        ->select('order_details.*', 'products.name as product_name', 'products.markup_price as product_markup_price', 'products.cover_image as product_cover_image', 'products.product_sku as product_sku', 'categories.category_name as sub_category_name')
                                                                        ->where('order_details.cust_id', '=', $uId)
                                                                        ->where('order_details.order_id', '=', $order_id)
                                                                        ->where('order_details.status', '=', 1)
                                                                        ->orderBy('order_details.id', 'DESC')
                                                                        ->get();
                                    $cart_items                     = [];
                                    $tot_subtotal_amt               = 0.00;
                                    $tot_shipping_amt               = 0.00;
                                    $tot_tax_amt                    = 0.00;
                                    $tot_net_amt                    = 0.00;
                                    if($cartItems){
                                        foreach($cartItems as $cartItem){
                                            $cart_items[]           = [
                                                'cart_id'                       => $cartItem->id,
                                                'product_name'                  => $cartItem->product_name,
                                                'product_markup_price'          => number_format($cartItem->product_markup_price,2),
                                                'product_cover_image'           => env('UPLOADS_URL') . 'product/' . $cartItem->product_cover_image,
                                                'product_sku'                   => $cartItem->product_sku,
                                                'sub_category_name'             => $cartItem->sub_category_name,
                                                'variation_name'                => $cartItem->variation_name,
                                                'rate'                          => number_format($cartItem->rate,2),
                                                'qty'                           => $cartItem->qty,
                                                'total'                         => number_format($cartItem->total,2),
                                                'disc_type'                     => $cartItem->disc_type,
                                                'disc_amount'                   => number_format($cartItem->disc_amount,2),
                                                'subtotal'                      => number_format($cartItem->subtotal,2),
                                                'amount_after_disc'             => number_format($cartItem->amount_after_disc,2),
                                                'shipping_amt'                  => number_format($cartItem->shipping_amt,2),
                                                'tax_amt'                       => number_format($cartItem->tax_amt,2),
                                                'net_amt'                       => number_format($cartItem->net_amt,2),
                                            ];
                                            $tot_subtotal_amt               += $cartItem->amount_after_disc;
                                            $tot_shipping_amt               += $cartItem->shipping_amt;
                                            $tot_tax_amt                    += $cartItem->tax_amt;
                                            $tot_net_amt                    += $cartItem->net_amt;
                                        }
                                    }
                                /* order details items */
                                if($getOrder){
                                    /* email functionality */
                                        $mailData['getOrder']       = Order::where('id', '=', $order_id)->first();
                                        $message                    = view('email-templates.order-place', $mailData);                    
                                        $generalSetting             = GeneralSetting::find('1');
                                        $subject                    = 'Order Confirmation - Your Order with '.$generalSetting->site_name.' ['.$mailData['getOrder']->order_no.'] has been successfully placed!';
                                        $this->sendMail($generalSetting->system_email, $subject, $message);
                                    /* email functionality */
                                    /* email log save */
                                        $postData2 = [
                                            'name'                  => $mailData['getOrder']->b_fname.' '.$mailData['getOrder']->b_lname,
                                            'email'                 => $mailData['getOrder']->b_email,
                                            'subject'               => $subject,
                                            'message'               => $message
                                        ];
                                        EmailLog::insertGetId($postData2);
                                    /* email log save */
                        
                                    $apiResponse                = [
                                        'order_id'              => $getOrder->id,
                                        'order_no'              => $getOrder->order_no,
                                        'customer_name'         => $getOrder->first_name.' '.$getOrder->last_name,
                                        'customer_email'        => $getOrder->email,
                                        'order_date'            => date_format(date_create($getOrder->order_date), "M d, Y"),
                                        'order_time'            => date_format(date_create($getOrder->order_time), "h:i A"),
                                        'b_name'                => $getOrder->b_fname.' '.$getOrder->b_lname,
                                        'b_phone'               => $getOrder->b_phone,
                                        'b_email'               => $getOrder->b_email,
                                        'b_company'             => $getOrder->b_company,
                                        'b_country'             => $getOrder->b_country,
                                        'b_street'              => $getOrder->b_street,
                                        'b_suburb'              => $getOrder->b_suburb,
                                        'b_state'               => $getOrder->b_state,
                                        'b_postcode'            => $getOrder->b_postcode,
                                        's_name'                => $getOrder->s_fname.' '.$getOrder->s_lname,
                                        's_phone'               => $getOrder->s_phone,
                                        's_email'               => $getOrder->s_email,
                                        's_company'             => $getOrder->s_company,
                                        's_country'             => $getOrder->s_country,
                                        's_street'              => $getOrder->s_street,
                                        's_suburb'              => $getOrder->s_suburb,
                                        's_state'               => $getOrder->s_state,
                                        's_postcode'            => $getOrder->s_postcode,
                                        'payment_status'        => (($getOrder->payment_status)?'SUCCESS':'UNPAID'),
                                        'payment_mode'          => $getOrder->payment_mode,
                                        'payment_txn_no'        => $getOrder->payment_txn_no,
                                        'payment_date_time'     => date_format(date_create($getOrder->payment_date_time), "M d, Y h:i A"),
                                        'currency'              => $getOrder->currency,
                                        'particulars'           => $getOrder->particulars,
                                        'cart_items'            => $cart_items,
                                        'tot_subtotal_amt'      => number_format($tot_subtotal_amt,2),
                                        'tot_shipping_amt'      => number_format($tot_shipping_amt,2),
                                        'tot_tax_amt'           => number_format($tot_tax_amt,2),
                                        'tot_net_amt'           => number_format($tot_net_amt,2)
                                    ];
                                    /* view analytics track */
                                        $userAgent                      = $request->header('User-Agent', 'unknown');
                                        $acceptLanguage                 = $request->header('Accept-Language', 'en');
                                        $clientIp                       = $request->ip();
                                        $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                                        $viewData = [
                                            'device_id'     => $deviceId,
                                            'page'          => 'order payment',
                                            'product_id'    => 0,
                                        ];
                                        UserView::insert($viewData);
                                    /* view analytics track */
                                    $apiStatus                  = TRUE;
                                    $apiMessage                 = 'Order Placed & Payment Completed Successfully !!!';
                                } else {
                                    $apiStatus                  = FALSE;
                                    $apiMessage                 = 'Order Not Found !!!';
                                }
                            } else {
                                // return response()->json([
                                //     'success' => false,
                                //     'error_code' => $result['error_code'],
                                //     'error_message' => $result['error_message']
                                // ], 400);
                                $apiStatus          = FALSE;
                                $apiMessage         = $result['error_message'];
                            }
                        } else {
                            $apiStatus          = FALSE;
                            $apiMessage         = 'Order Not Found !!!';
                        }
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function orderList(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        $new_orders             = [];
                        $processing_orders      = [];
                        $incomplete_orders      = [];
                        $shipped_orders         = [];
                        $complete_orders        = [];
                        $rejected_orders        = [];
                        $cancelled_orders       = [];
                        /* new orders */
                            $newOrders = Order::select('id', 'order_no', 'net_amt', 'payment_mode', 'order_date', 'order_time', 'invoice_pdf')->where('cust_id', '=', $uId)->where('status', '=', 1)->orderBy('id', 'DESC')->get();
                            if($newOrders){
                                foreach($newOrders as $row){
                                    $new_orders[]             = [
                                        'order_id'      => $row->id,
                                        'order_no'      => $row->order_no,
                                        'net_amt'       => number_format($row->net_amt,2),
                                        'payment_mode'  => $row->payment_mode,
                                        'order_date'    => date_format(date_create($row->order_date), "M d, Y"),
                                        'order_time'    => date_format(date_create($row->order_time), "h:i A"),
                                        'invoice_pdf'   => (($row->invoice_pdf != '')?env('UPLOADS_URL').'orders/'.$row->invoice_pdf:''),
                                        'order_status'  => 'new',
                                    ];
                                }
                            }
                        /* new orders */
                        /* processing orders */
                            $processingOrders = Order::select('id', 'order_no', 'net_amt', 'payment_mode', 'order_date', 'order_time', 'invoice_pdf')->where('cust_id', '=', $uId)->where('status', '=', 2)->orderBy('id', 'DESC')->get();
                            if($processingOrders){
                                foreach($processingOrders as $row){
                                    $processing_orders[]             = [
                                        'order_id'      => $row->id,
                                        'order_no'      => $row->order_no,
                                        'net_amt'       => number_format($row->net_amt,2),
                                        'payment_mode'  => $row->payment_mode,
                                        'order_date'    => date_format(date_create($row->order_date), "M d, Y"),
                                        'order_time'    => date_format(date_create($row->order_time), "h:i A"),
                                        'invoice_pdf'   => (($row->invoice_pdf != '')?env('UPLOADS_URL').'orders/'.$row->invoice_pdf:''),
                                        'order_status'  => 'processing',
                                    ];
                                }
                            }
                        /* processing orders */
                        /* incomplete orders */
                            $incompleteOrders = Order::select('id', 'order_no', 'net_amt', 'payment_mode', 'order_date', 'order_time', 'invoice_pdf')->where('cust_id', '=', $uId)->where('status', '=', 3)->orderBy('id', 'DESC')->get();
                            if($incompleteOrders){
                                foreach($incompleteOrders as $row){
                                    $incomplete_orders[]             = [
                                        'order_id'      => $row->id,
                                        'order_no'      => $row->order_no,
                                        'net_amt'       => number_format($row->net_amt,2),
                                        'payment_mode'  => $row->payment_mode,
                                        'order_date'    => date_format(date_create($row->order_date), "M d, Y"),
                                        'order_time'    => date_format(date_create($row->order_time), "h:i A"),
                                        'invoice_pdf'   => (($row->invoice_pdf != '')?env('UPLOADS_URL').'orders/'.$row->invoice_pdf:''),
                                        'order_status'  => 'incomplete',
                                    ];
                                }
                            }
                        /* incomplete orders */
                        /* shipped orders */
                            $shippedOrders = Order::select('id', 'order_no', 'net_amt', 'payment_mode', 'order_date', 'order_time', 'invoice_pdf')->where('cust_id', '=', $uId)->where('status', '=', 4)->orderBy('id', 'DESC')->get();
                            if($shippedOrders){
                                foreach($shippedOrders as $row){
                                    $shipped_orders[]             = [
                                        'order_id'      => $row->id,
                                        'order_no'      => $row->order_no,
                                        'net_amt'       => number_format($row->net_amt,2),
                                        'payment_mode'  => $row->payment_mode,
                                        'order_date'    => date_format(date_create($row->order_date), "M d, Y"),
                                        'order_time'    => date_format(date_create($row->order_time), "h:i A"),
                                        'invoice_pdf'   => (($row->invoice_pdf != '')?env('UPLOADS_URL').'orders/'.$row->invoice_pdf:''),
                                        'order_status'  => 'shipped',
                                    ];
                                }
                            }
                        /* shipped orders */
                        /* complete orders */
                            $completeOrders = Order::select('id', 'order_no', 'net_amt', 'payment_mode', 'order_date', 'order_time', 'invoice_pdf')->where('cust_id', '=', $uId)->where('status', '=', 5)->orderBy('id', 'DESC')->get();
                            if($completeOrders){
                                foreach($completeOrders as $row){
                                    $complete_orders[]             = [
                                        'order_id'      => $row->id,
                                        'order_no'      => $row->order_no,
                                        'net_amt'       => number_format($row->net_amt,2),
                                        'payment_mode'  => $row->payment_mode,
                                        'order_date'    => date_format(date_create($row->order_date), "M d, Y"),
                                        'order_time'    => date_format(date_create($row->order_time), "h:i A"),
                                        'invoice_pdf'   => (($row->invoice_pdf != '')?env('UPLOADS_URL').'orders/'.$row->invoice_pdf:''),
                                        'order_status'  => 'complete',
                                    ];
                                }
                            }
                        /* complete orders */
                        /* rejected orders */
                            $rejetedOrders = Order::select('id', 'order_no', 'net_amt', 'payment_mode', 'order_date', 'order_time', 'invoice_pdf')->where('cust_id', '=', $uId)->where('status', '=', 6)->orderBy('id', 'DESC')->get();
                            if($rejetedOrders){
                                foreach($rejetedOrders as $row){
                                    $rejected_orders[]             = [
                                        'order_id'      => $row->id,
                                        'order_no'      => $row->order_no,
                                        'net_amt'       => number_format($row->net_amt,2),
                                        'payment_mode'  => $row->payment_mode,
                                        'order_date'    => date_format(date_create($row->order_date), "M d, Y"),
                                        'order_time'    => date_format(date_create($row->order_time), "h:i A"),
                                        'invoice_pdf'   => (($row->invoice_pdf != '')?env('UPLOADS_URL').'orders/'.$row->invoice_pdf:''),
                                        'order_status'  => 'rejected',
                                    ];
                                }
                            }
                        /* rejected orders */
                        /* cancelled orders */
                            $cancelledOrders = Order::select('id', 'order_no', 'net_amt', 'payment_mode', 'order_date', 'order_time', 'invoice_pdf')->where('cust_id', '=', $uId)->where('status', '=', 7)->orderBy('id', 'DESC')->get();
                            if($cancelledOrders){
                                foreach($cancelledOrders as $row){
                                    $cancelled_orders[]             = [
                                        'order_id'      => $row->id,
                                        'order_no'      => $row->order_no,
                                        'net_amt'       => number_format($row->net_amt,2),
                                        'payment_mode'  => $row->payment_mode,
                                        'order_date'    => date_format(date_create($row->order_date), "M d, Y"),
                                        'order_time'    => date_format(date_create($row->order_time), "h:i A"),
                                        'invoice_pdf'   => (($row->invoice_pdf != '')?env('UPLOADS_URL').'orders/'.$row->invoice_pdf:''),
                                        'order_status'  => 'cancelled',
                                    ];
                                }
                            }
                        /* cancelled orders */
                        $apiResponse        = [
                            'new_orders'                        => $new_orders,
                            'new_orders_count'                  => count($new_orders),
                            'processing_orders'                 => $processing_orders,
                            'processing_orders_count'           => count($processing_orders),
                            'incomplete_orders'                 => $incomplete_orders,
                            'incomplete_orders_count'           => count($incomplete_orders),
                            'shipped_orders'                    => $shipped_orders,
                            'shipped_orders_count'              => count($shipped_orders),
                            'complete_orders'                   => $complete_orders,
                            'complete_orders_count'             => count($complete_orders),
                            'rejected_orders'                   => $rejected_orders,
                            'rejected_orders_count'             => count($rejected_orders),
                            'cancelled_orders'                  => $cancelled_orders,
                            'cancelled_orders_count'            => count($cancelled_orders),
                        ];
                        /* view analytics track */
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $viewData = [
                                'device_id'     => $deviceId,
                                'page'          => 'orderlist',
                                'product_id'    => 0,
                            ];
                            UserView::insert($viewData);
                        /* view analytics track */
                        $apiStatus          = TRUE;
                        $apiMessage         = 'Data Available !!!';
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function orderDetails(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();       
            $requiredFields     = ['key', 'source', 'order_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $request->header('Authorization');
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        $order_id   = $requestData['order_id'];
                        $getOrder   = DB::table('orders')
                                                ->join('users', 'orders.cust_id', '=', 'users.id')
                                                ->select('orders.*', 'users.first_name', 'users.last_name', 'users.email')
                                                ->where('orders.cust_id', '=', $uId)
                                                ->where('orders.id', '=', $order_id)
                                                ->first();
                        /* order details items */
                            OrderDetail::where('order_id', '=', $order_id)->update(['cust_id' => $uId]);
                            $cartItems                      = DB::table('order_details')
                                                                ->join('products', 'order_details.product_id', '=', 'products.id')
                                                                ->join('categories', 'products.sub_category', '=', 'categories.id')
                                                                ->select('order_details.*', 'products.name as product_name', 'products.markup_price as product_markup_price', 'products.cover_image as product_cover_image', 'products.product_sku as product_sku', 'categories.category_name as sub_category_name')
                                                                ->where('order_details.cust_id', '=', $uId)
                                                                ->where('order_details.order_id', '=', $order_id)
                                                                // ->where('order_details.status', '=', 1)
                                                                ->orderBy('order_details.id', 'DESC')
                                                                ->get();
                            $cart_items                     = [];
                            $tot_amt_before_disc            = 0.00;
                            $tot_disc_amt                   = 0.00;
                            $tot_subtotal_amt               = 0.00;
                            $tot_shipping_amt               = 0.00;
                            $tot_tax_amt                    = 0.00;
                            $tot_net_amt                    = 0.00;
                            if($cartItems){
                                foreach($cartItems as $cartItem){
                                    $cart_items[]           = [
                                        'cart_id'                       => $cartItem->id,
                                        'product_name'                  => $cartItem->product_name,
                                        'product_markup_price'          => number_format($cartItem->product_markup_price,2),
                                        'product_cover_image'           => env('UPLOADS_URL') . 'product/' . $cartItem->product_cover_image,
                                        'product_sku'                   => $cartItem->product_sku,
                                        'sub_category_name'             => $cartItem->sub_category_name,
                                        'variation_name'                => $cartItem->variation_name,
                                        'rate'                          => number_format($cartItem->rate,2),
                                        'qty'                           => $cartItem->qty,
                                        'total'                         => number_format($cartItem->total,2),
                                        'disc_type'                     => $cartItem->disc_type,
                                        'disc_amount'                   => number_format($cartItem->disc_amount,2),
                                        'subtotal'                      => number_format($cartItem->subtotal,2),
                                        'amount_after_disc'             => number_format($cartItem->amount_after_disc,2),
                                        'shipping_amt'                  => number_format($cartItem->shipping_amt,2),
                                        'tax_amt'                       => number_format($cartItem->tax_amt,2),
                                        'net_amt'                       => number_format($cartItem->net_amt,2),
                                    ];
                                    $tot_amt_before_disc            += $cartItem->subtotal;
                                    $tot_disc_amt                   += $cartItem->disc_amount;
                                    $tot_subtotal_amt               += $cartItem->amount_after_disc;
                                    $tot_shipping_amt               += $cartItem->shipping_amt;
                                    $tot_tax_amt                    += $cartItem->tax_amt;
                                    $tot_net_amt                    += $cartItem->net_amt;
                                }
                            }
                        /* order details items */
                        if($getOrder){
                            $apiResponse                = [
                                'order_id'              => $getOrder->id,
                                'order_no'              => $getOrder->order_no,
                                'customer_name'         => $getOrder->first_name.' '.$getOrder->last_name,
                                'customer_email'        => $getOrder->email,
                                'order_date'            => date_format(date_create($getOrder->order_date), "M d, Y"),
                                'order_time'            => date_format(date_create($getOrder->order_time), "h:i A"),
                                'b_name'                => $getOrder->b_fname.' '.$getOrder->b_lname,
                                'b_phone'               => $getOrder->b_phone,
                                'b_email'               => $getOrder->b_email,
                                'b_company'             => $getOrder->b_company,
                                'b_country'             => $getOrder->b_country,
                                'b_street'              => $getOrder->b_street,
                                'b_suburb'              => $getOrder->b_suburb,
                                'b_state'               => $getOrder->b_state,
                                'b_postcode'            => $getOrder->b_postcode,
                                's_name'                => $getOrder->s_fname.' '.$getOrder->s_lname,
                                's_phone'               => $getOrder->s_phone,
                                's_email'               => $getOrder->s_email,
                                's_company'             => $getOrder->s_company,
                                's_country'             => $getOrder->s_country,
                                's_street'              => $getOrder->s_street,
                                's_suburb'              => $getOrder->s_suburb,
                                's_state'               => $getOrder->s_state,
                                's_postcode'            => $getOrder->s_postcode,
                                'payment_status'        => (($getOrder->payment_status)?'SUCCESS':'UNPAID'),
                                'payment_mode'          => $getOrder->payment_mode,
                                'payment_txn_no'        => $getOrder->payment_txn_no,
                                'payment_date_time'     => date_format(date_create($getOrder->payment_date_time), "M d, Y h:i A"),
                                'currency'              => $getOrder->currency,
                                'particulars'           => $getOrder->particulars,
                                'cart_items'            => $cart_items,
                                'tot_amt_before_disc'   => number_format($tot_amt_before_disc,2),
                                'tot_disc_amt'          => number_format($tot_disc_amt,2),
                                'tot_subtotal_amt'      => number_format($tot_subtotal_amt,2),
                                'tot_shipping_amt'      => number_format($tot_shipping_amt,2),
                                'tot_tax_amt'           => number_format($tot_tax_amt,2),
                                'tot_net_amt'           => number_format($tot_net_amt,2),
                                'invoice_pdf'           => (($getOrder->invoice_pdf != '')?env('UPLOADS_URL').'orders/'.$getOrder->invoice_pdf:''),
                            ];
                            /* view analytics track */
                                $userAgent                      = $request->header('User-Agent', 'unknown');
                                $acceptLanguage                 = $request->header('Accept-Language', 'en');
                                $clientIp                       = $request->ip();
                                $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                                $viewData = [
                                    'device_id'     => $deviceId,
                                    'page'          => 'order details',
                                    'product_id'    => 0,
                                ];
                                UserView::insert($viewData);
                            /* view analytics track */
                            $apiStatus                  = TRUE;
                            $apiMessage                 = 'Data Available !!!';
                        } else {
                            $apiStatus                  = FALSE;
                            $apiMessage                 = 'Order Not Found !!!';
                        }
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function printInvoice(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();       
            $requiredFields     = ['key', 'source', 'order_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $request->header('Authorization');
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        $order_id   = $requestData['order_id'];
                        $getOrder   = DB::table('orders')
                                                ->join('users', 'orders.cust_id', '=', 'users.id')
                                                ->select('orders.*', 'users.first_name', 'users.last_name', 'users.email')
                                                ->where('orders.cust_id', '=', $uId)
                                                ->where('orders.id', '=', $order_id)
                                                ->first();
                        /* order details items */
                            OrderDetail::where('order_id', '=', $order_id)->update(['cust_id' => $uId]);
                            $cartItems                      = DB::table('order_details')
                                                                ->join('products', 'order_details.product_id', '=', 'products.id')
                                                                ->join('categories', 'products.sub_category', '=', 'categories.id')
                                                                ->select('order_details.*', 'products.name as product_name', 'products.markup_price as product_markup_price', 'products.cover_image as product_cover_image', 'products.product_sku as product_sku', 'categories.category_name as sub_category_name')
                                                                ->where('order_details.cust_id', '=', $uId)
                                                                ->where('order_details.order_id', '=', $order_id)
                                                                ->where('order_details.status', '=', 1)
                                                                ->orderBy('order_details.id', 'DESC')
                                                                ->get();
                            $cart_items                     = [];
                            $tot_subtotal_amt               = 0.00;
                            $tot_shipping_amt               = 0.00;
                            $tot_tax_amt                    = 0.00;
                            $tot_net_amt                    = 0.00;
                            if($cartItems){
                                foreach($cartItems as $cartItem){
                                    $cart_items[]           = [
                                        'cart_id'                       => $cartItem->id,
                                        'product_name'                  => $cartItem->product_name,
                                        'product_markup_price'          => number_format($cartItem->product_markup_price,2),
                                        'product_cover_image'           => env('UPLOADS_URL') . 'product/' . $cartItem->product_cover_image,
                                        'product_sku'                   => $cartItem->product_sku,
                                        'sub_category_name'             => $cartItem->sub_category_name,
                                        'variation_name'                => $cartItem->variation_name,
                                        'rate'                          => number_format($cartItem->rate,2),
                                        'qty'                           => $cartItem->qty,
                                        'total'                         => number_format($cartItem->total,2),
                                        'disc_type'                     => $cartItem->disc_type,
                                        'disc_amount'                   => number_format($cartItem->disc_amount,2),
                                        'subtotal'                      => number_format($cartItem->subtotal,2),
                                        'amount_after_disc'             => number_format($cartItem->amount_after_disc,2),
                                        'shipping_amt'                  => number_format($cartItem->shipping_amt,2),
                                        'tax_amt'                       => number_format($cartItem->tax_amt,2),
                                        'net_amt'                       => number_format($cartItem->net_amt,2),
                                    ];
                                    $tot_subtotal_amt               += $cartItem->amount_after_disc;
                                    $tot_shipping_amt               += $cartItem->shipping_amt;
                                    $tot_tax_amt                    += $cartItem->tax_amt;
                                    $tot_net_amt                    += $cartItem->net_amt;
                                }
                            }
                        /* order details items */
                        if($getOrder){
                            $apiResponse                = [
                                'order_id'              => $getOrder->id,
                                'order_no'              => $getOrder->order_no,
                                'customer_name'         => $getOrder->first_name.' '.$getOrder->last_name,
                                'customer_email'        => $getOrder->email,
                                'order_date'            => date_format(date_create($getOrder->order_date), "M d, Y"),
                                'order_time'            => date_format(date_create($getOrder->order_time), "h:i A"),
                                'b_name'                => $getOrder->b_fname.' '.$getOrder->b_lname,
                                'b_phone'               => $getOrder->b_phone,
                                'b_email'               => $getOrder->b_email,
                                'b_company'             => $getOrder->b_company,
                                'b_country'             => $getOrder->b_country,
                                'b_street'              => $getOrder->b_street,
                                'b_suburb'              => $getOrder->b_suburb,
                                'b_state'               => $getOrder->b_state,
                                'b_postcode'            => $getOrder->b_postcode,
                                's_name'                => $getOrder->s_fname.' '.$getOrder->s_lname,
                                's_phone'               => $getOrder->s_phone,
                                's_email'               => $getOrder->s_email,
                                's_company'             => $getOrder->s_company,
                                's_country'             => $getOrder->s_country,
                                's_street'              => $getOrder->s_street,
                                's_suburb'              => $getOrder->s_suburb,
                                's_state'               => $getOrder->s_state,
                                's_postcode'            => $getOrder->s_postcode,
                                'payment_status'        => (($getOrder->payment_status)?'SUCCESS':'UNPAID'),
                                'payment_mode'          => $getOrder->payment_mode,
                                'payment_txn_no'        => $getOrder->payment_txn_no,
                                'payment_date_time'     => date_format(date_create($getOrder->payment_date_time), "M d, Y h:i A"),
                                'currency'              => $getOrder->currency,
                                'particulars'           => $getOrder->particulars,
                                'cart_items'            => $cart_items,
                                'tot_subtotal_amt'      => number_format($tot_subtotal_amt,2),
                                'tot_shipping_amt'      => number_format($tot_shipping_amt,2),
                                'tot_tax_amt'           => number_format($tot_tax_amt,2),
                                'tot_net_amt'           => number_format($tot_net_amt,2)
                            ];
                            /* view analytics track */
                                $userAgent                      = $request->header('User-Agent', 'unknown');
                                $acceptLanguage                 = $request->header('Accept-Language', 'en');
                                $clientIp                       = $request->ip();
                                $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                                $viewData = [
                                    'device_id'     => $deviceId,
                                    'page'          => 'print invoice',
                                    'product_id'    => 0,
                                ];
                                UserView::insert($viewData);
                            /* view analytics track */
                            $apiStatus                  = TRUE;
                            $apiMessage                 = 'Data Available !!!';
                        } else {
                            $apiStatus                  = FALSE;
                            $apiMessage                 = 'Order Not Found !!!';
                        }
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function cancelOrderReason(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        $getReasons = CancelOrderReason::where('status', '=', 1)->get();
                        if($getReasons){
                            foreach($getReasons as $getReason){
                                $apiResponse[]    = [
                                    'id'                => $getReason->id,
                                    'name'              => $getReason->name,
                                ];
                            }
                        }
                        /* view analytics track */
                            $userAgent                      = $request->header('User-Agent', 'unknown');
                            $acceptLanguage                 = $request->header('Accept-Language', 'en');
                            $clientIp                       = $request->ip();
                            $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                            $viewData = [
                                'device_id'     => $deviceId,
                                'page'          => 'cancel order reason',
                                'product_id'    => 0,
                            ];
                            UserView::insert($viewData);
                        /* view analytics track */
                        $apiStatus          = TRUE;
                        $apiMessage         = 'Data Available !!!';
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function cancelOrder(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();       
            $requiredFields     = ['key', 'source', 'order_id', 'cancel_order_reason'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $request->header('Authorization');
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = User::where('id', '=', $uId)->first();
                    if($getUser){
                        $order_id                   = $requestData['order_id'];
                        $cancel_order_reason        = $requestData['cancel_order_reason'];
                        $cancel_order_description   = $requestData['cancel_order_description'];
                        $getOrder                   = DB::table('orders')
                                                        ->select('orders.*')
                                                        ->where('orders.id', '=', $order_id)
                                                        ->first();
                        if($getOrder){
                            Order::where('id', '=', $order_id)->update(['status' => 7, 'cancel_order_reason' => $cancel_order_reason, 'cancel_order_description' => $cancel_order_description]);
                            OrderDetail::where('order_id', '=', $order_id)->update(['status' => 3]);
                            $generalSetting                 = GeneralSetting::find('1');
                            $statusName                     = 'Cancelled';
                            $getOrder                       = Order::where('id', '=', $order_id)->first();
                            $cust_email                     = $getOrder->cust_email;
                            /* email functionality */
                                $mailData['getOrder']       = Order::where('id', '=', $order_id)->first();;
                                $mailData['mailHeader']     = 'Order Status - Your Order with '.$generalSetting->site_name.' ['.$mailData['getOrder']->order_no.'] has been successfully updated into '.$statusName;
                                $message                    = view('email-templates.order-status-update', $mailData);
                                $generalSetting             = GeneralSetting::find('1');
                                $subject                    = 'Order Status - Your Order with '.$generalSetting->site_name.' ['.$mailData['getOrder']->order_no.'] has been successfully updated into '.$statusName;
                                $this->sendMail($cust_email, $subject, $message);
                            /* email functionality */
                            /* email log save */
                                $postData2 = [
                                    'name'                  => $getOrder->cust_fname.' '.$getOrder->cust_lname,
                                    'email'                 => $cust_email,
                                    'subject'               => $subject,
                                    'message'               => $message
                                ];
                                EmailLog::insertGetId($postData2);
                            /* email log save */
                            /* view analytics track */
                                $userAgent                      = $request->header('User-Agent', 'unknown');
                                $acceptLanguage                 = $request->header('Accept-Language', 'en');
                                $clientIp                       = $request->ip();
                                $deviceId                       = $this->createDeviceFingerprint($userAgent, $acceptLanguage, $clientIp);
                                $viewData = [
                                    'device_id'     => $deviceId,
                                    'page'          => 'cancel order',
                                    'product_id'    => 0,
                                ];
                                UserView::insert($viewData);
                            /* view analytics track */
                            $apiStatus                  = TRUE;
                            $apiMessage                 = (($getOrder)?$getOrder->order_no:'') . ' Marked As Cancelled Successfully !!!';
                        } else {
                            $apiStatus                  = FALSE;
                            $apiMessage                 = 'Order Not Found !!!';
                        }
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(401);
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
    /* after login */
    /*
    Get http response code
    Author : Subhomoy
    */
    private function getResponseCode($code = NULL){
        if ($code !== NULL) {
            switch ($code) {
                case 100: $text = 'Continue'; break;
                case 101: $text = 'Switching Protocols'; break;
                case 200: $text = 'OK'; break;
                case 201: $text = 'Created'; break;
                case 202: $text = 'Accepted'; break;
                case 203: $text = 'Non-Authoritative Information'; break;
                case 204: $text = 'No Content'; break;
                case 205: $text = 'Reset Content'; break;
                case 206: $text = 'Partial Content'; break;
                case 300: $text = 'Multiple Choices'; break;
                case 301: $text = 'Moved Permanently'; break;
                case 302: $text = 'Moved Temporarily'; break;
                case 303: $text = 'See Other'; break;
                case 304: $text = 'Not Modified'; break;
                case 305: $text = 'Use Proxy'; break;
                case 400: $text = 'Unauthenticated Request !!!'; break;
                case 401: $text = 'Token Not Found !!!'; break;
                case 402: $text = 'Payment Required'; break;
                case 403: $text = 'Token Has Expired !!!'; break;
                case 404: $text = 'User Not Found !!!'; break;
                case 405: $text = 'Method Not Allowed'; break;
                case 406: $text = 'All Data Are Not Present !!!'; break;
                case 407: $text = 'Proxy Authentication Required'; break;
                case 408: $text = 'Request Time-out'; break;
                case 409: $text = 'Conflict'; break;
                case 410: $text = 'Gone'; break;
                case 411: $text = 'Length Required'; break;
                case 412: $text = 'Precondition Failed'; break;
                case 413: $text = 'Request Entity Too Large'; break;
                case 414: $text = 'Request-URI Too Large'; break;
                case 415: $text = 'Unsupported Media Type'; break;
                case 500: $text = 'Internal Server Error'; break;
                case 501: $text = 'Not Implemented'; break;
                case 502: $text = 'Bad Gateway'; break;
                case 503: $text = 'Service Unavailable'; break;
                case 504: $text = 'Gateway Time-out'; break;
                case 505: $text = 'HTTP Version not supported'; break;
                default:
                    exit('Unknown http status code "' . htmlentities($code) . '"');
                break;
            }
            $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
            header($protocol . ' ' . $code . ' ' . $text);
            $GLOBALS['http_response_code'] = $code;
        } else {
            $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
            $text = '';
        }
        return $text;
    }
    /*
    Generate JWT tokens for authentication
    Author : Subhomoy
    */
    private static function generateToken($userId, $email, $phone){
        $token      = array(
            'id'                => $userId,
            'email'             => $email,
            'phone'             => $phone,
            'exp'               => time() + (30 * 24 * 60 * 60) // 30 days
        );
        // pr($token);
        return JWT::encode($token, TOKEN_SECRET, 'HS256');
    }
    /*
    Check Authentication
    Author : Subhomoy
    */
    private function tokenAuth($appAccessToken){
        $headers = apache_request_headers();
        if (isset($appAccessToken) && !empty($appAccessToken)) :
            $userdata = $this->matchToken($appAccessToken);
            // pr($userdata);
            if ($userdata['status']) :
                $checkToken =  UserDevice::where('user_id', '=', $userdata['data']->id)->where('app_access_token', '=', $appAccessToken)->first();
                // echo $this->db->last_query();
                // pr($userdata);
                if (!empty($checkToken)) :
                    if ($userdata['data']->exp && $userdata['data']->exp > time()) :
                        $tokenStatus = array(TRUE, $userdata['data']->id, $userdata['data']->email, $userdata['data']->phone, $userdata['data']->exp);
                    else :
                        $tokenStatus = array(FALSE, 'Token Has Expired 1 !!!');
                    endif;
                else :
                    $tokenStatus = array(FALSE, 'Token Has Expired 2 !!!');
                endif;
            else :
                $tokenStatus = array(FALSE, 'Token Not Found !!!');
            endif;
        else :
            $tokenStatus = array(FALSE, 'Token Not Found In Request !!!');
        endif;
        if ($tokenStatus[0]) :
            $this->userId           = $tokenStatus[1];
            $this->userEmail        = $tokenStatus[2];
            $this->userMobile       = $tokenStatus[3];
            $this->userExpiry       = $tokenStatus[4];
            // pr($tokenStatus);
            return array('status' => TRUE, 'data' => $tokenStatus);
        else :
            return array('status' => FALSE, 'data' => $tokenStatus[1]);
            // $this->response_to_json(FALSE, $tokenStatus[1]);
        endif;
    }
    /*
    Match JWT token with user token saved in database
    Author : Subhomoy
    */
    private static function matchToken($token){
        // try{
        //     // $decoded    = JWT::decode($token, TOKEN_SECRET, 'HS256');
        //     $decoded    = JWT::decode($token, new Key(TOKEN_SECRET, 'HS256'));
        //     // pr($decoded);
        // } catch (\Exception $e) {
        //     //echo 'Caught exception: ',  $e->getMessage(), "\n";
        //     return array('status' => FALSE, 'data' => '');
        // }
        
        // return array('status' => TRUE, 'data' => $decoded);
        try{
            $key = "1234567890qwertyuiopmnbvcxzasdfghjkl";
            $decoded = JWT::decode($token, $key, array('HS256'));
            // $decodedData = (array) $decoded;
        } catch (\Exception $e) {
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
            return array('status' => FALSE, 'data' => '');
        }
        return array('status' => TRUE, 'data' => $decoded);
    }
}
