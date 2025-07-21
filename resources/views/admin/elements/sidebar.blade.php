<?php
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Route;
$routeName    = Route::current();
$pageName     = explode("/", $routeName->uri());
$pageSegment  = $pageName[1];
$pageFunction = ((count($pageName)>2)?$pageName[1]:'');
$parameters   = $routeName->parameters();
// dd($routeName);
if(!empty($parameters)){
  if (array_key_exists("id1",$parameters)){
    $pId1 = Helper::decoded($parameters['id1']);
  } else {
    $pId1 = Helper::decoded($parameters['id']);
  }
  if(count($parameters) > 1){
    $pId2 = Helper::decoded($parameters['id2']);
  }
}

$orderCount1                   = Order::where('status', '=', 1)->where('is_cancel_request', '=', 0)->orderBy('id', 'DESC')->count();
$orderCount2                   = Order::where('status', '=', 2)->where('is_cancel_request', '=', 0)->orderBy('id', 'DESC')->count();
$orderCount3                   = Order::where('status', '=', 3)->where('is_cancel_request', '=', 0)->orderBy('id', 'DESC')->count();
$orderCount4                   = Order::where('status', '=', 4)->where('is_cancel_request', '=', 0)->orderBy('id', 'DESC')->count();
$orderCount5                   = Order::where('status', '=', 5)->where('is_cancel_request', '=', 0)->orderBy('id', 'DESC')->count();
$orderCount6                   = Order::where('status', '=', 6)->where('is_cancel_request', '=', 0)->orderBy('id', 'DESC')->count();
$orderCount7                   = Order::where('status', '=', 7)->where('is_cancel_request', '=', 0)->orderBy('id', 'DESC')->count();
?>
<ul class="sidebar-nav" id="sidebar-nav">
  <li class="nav-item">
    <a class="nav-link <?=(($pageSegment == 'dashboard')?'active':'')?>" href="{{ url('admin/dashboard') }}">
      <i class="fa fa-home"></i>
      <span>Dashboard</span>
    </a>
  </li><!-- End Dashboard Nav -->
  <li class="nav-item">
    <a class="nav-link <?=(($pageSegment == 'stats')?'active':'')?>" href="{{ url('admin/stats') }}">
      <i class="fa fa-bar-chart" aria-hidden="true"></i>
      <span>Stats</span>
    </a>
  </li><!-- End stat Nav -->
  <?php
  //if($admin->type == 'ma'){?>
    <?php if((in_array(8, $module_id)) || (in_array(9, $module_id)) || (in_array(10, $module_id))){?>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'module' || $pageSegment == 'sub-user' || $pageSegment == 'access')?'':'collapsed')?> <?=(($pageSegment == 'module' || $pageSegment == 'sub-user' || $pageSegment == 'access')?'active':'')?>" data-bs-target="#permission-nav" data-bs-toggle="collapse" href="#">
        <i class="fa fa-lock"></i><span>Access & Permission</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="permission-nav" class="nav-content collapse <?=(($pageSegment == 'module' || $pageSegment == 'sub-user' || $pageSegment == 'access')?'show':'')?>" data-bs-parent="#sidebar-nav">
        <?php if(in_array(8, $module_id)){?>
        <li>
          <a class="<?=(($pageSegment == 'module')?'active':'')?>" href="{{ url('admin/module/list') }}">
            <i class="bi bi-arrow-right"></i><span>Modules</span>
          </a>
        </li>
        <?php }?>
        <?php if(in_array(9, $module_id)){?>
        <li>
          <a class="<?=(($pageSegment == 'sub-user')?'active':'')?>" href="{{ url('admin/sub-user/list') }}">
            <i class="bi bi-arrow-right"></i><span>Sub Users</span>
          </a>
        </li>
        <?php }?>
        <?php if(in_array(10, $module_id)){?>
        <li>
          <a class="<?=(($pageSegment == 'access')?'active':'')?>" href="{{ url('admin/access/list') }}">
            <i class="bi bi-arrow-right"></i><span>Give Access</span>
          </a>
        </li>
        <?php }?>
      </ul>
    </li><!-- End Permission Nav -->
    <?php }?>
  <?php //}?>

  <?php if(in_array(32, $module_id)){?>
    <!-- <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'payment-method')?'active':'')?>" href="{{ url('admin/payment-method/list') }}">
        <i class="fa fa-gift"></i>
        <span>Payment Methods</span>
      </a>
    </li> --><!-- End Profile Page Nav -->
  <?php }?>

  <?php if(in_array(32, $module_id)){?>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'coupon')?'active':'')?>" href="{{ url('admin/coupon/list') }}">
        <i class="fa fa-gift"></i>
        <span>Coupons</span>
      </a>
    </li><!-- End Profile Page Nav -->
  <?php }?>

  <?php if(in_array(32, $module_id)){?>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'cancel-order-reason')?'active':'')?>" href="{{ url('admin/cancel-order-reason/list') }}">
        <i class="fa fa-times"></i>
        <span>Cancel Order Reasons</span>
      </a>
    </li><!-- End Profile Page Nav -->
  <?php }?>

  <?php if((in_array(1, $module_id)) || (in_array(2, $module_id)) || (in_array(3, $module_id)) || (in_array(4, $module_id)) || (in_array(5, $module_id)) || (in_array(6, $module_id))) {?>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'unit' || $pageSegment == 'shop-produce' || $pageSegment == 'tools-used' || $pageSegment == 'materials' || $pageSegment == 'return-policy' || $pageSegment == 'parent-category' || $pageSegment == 'sub-category' || $pageSegment == 'attribute')?'':'collapsed')?> <?=(($pageSegment == 'unit' || $pageSegment == 'shop-produce' || $pageSegment == 'tools-used' || $pageSegment == 'materials' || $pageSegment == 'return-policy' || $pageSegment == 'parent-category' || $pageSegment == 'sub-category' || $pageSegment == 'attribute')?'active':'')?>" data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
        <i class="fab fa-product-hunt"></i><span>Product Master</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="master-nav" class="nav-content collapse <?=(($pageSegment == 'unit' || $pageSegment == 'shop-produce' || $pageSegment == 'tools-used' || $pageSegment == 'materials' || $pageSegment == 'return-policy' || $pageSegment == 'parent-category' || $pageSegment == 'sub-category' || $pageSegment == 'attribute')?'show':'')?>" data-bs-parent="#sidebar-nav">
        <?php if(in_array(2, $module_id)){?>
          <li>
            <a class="<?=(($pageSegment == 'unit')?'active':'')?>" href="{{ url('admin/unit/list') }}">
              <i class="bi bi-arrow-right"></i><span>Units</span>
            </a>
          </li>
        <?php }?>
        <?php if(in_array(2, $module_id)){?>
          <li>
            <a class="<?=(($pageSegment == 'shop-produce')?'active':'')?>" href="{{ url('admin/shop-produce/list') }}">
              <i class="bi bi-arrow-right"></i><span>How Shop Produced</span>
            </a>
          </li>
        <?php }?>
        <?php if(in_array(2, $module_id)){?>
          <li>
            <a class="<?=(($pageSegment == 'tools-used')?'active':'')?>" href="{{ url('admin/tools-used/list') }}">
              <i class="bi bi-arrow-right"></i><span>Tools Used</span>
            </a>
          </li>
        <?php }?>
        <?php if(in_array(2, $module_id)){?>
          <li>
            <a class="<?=(($pageSegment == 'materials')?'active':'')?>" href="{{ url('admin/materials/list') }}">
              <i class="bi bi-arrow-right"></i><span>Materials</span>
            </a>
          </li>
        <?php }?>
        <?php if(in_array(2, $module_id)){?>
          <li>
            <a class="<?=(($pageSegment == 'return-policy')?'active':'')?>" href="{{ url('admin/return-policy/list') }}">
              <i class="bi bi-arrow-right"></i><span>Return Policies</span>
            </a>
          </li>
        <?php }?>
        <?php if(in_array(2, $module_id)){?>
          <li>
            <a class="<?=(($pageSegment == 'parent-category')?'active':'')?>" href="{{ url('admin/parent-category/list') }}">
              <i class="bi bi-arrow-right"></i><span>Parent Categories <small>(Layer 1)</small></span>
            </a>
          </li>
        <?php }?>
        <?php if(in_array(2, $module_id)){?>
          <li>
            <a class="<?=(($pageSegment == 'sub-category')?'active':'')?>" href="{{ url('admin/sub-category/list') }}">
              <i class="bi bi-arrow-right"></i><span>Sub Categories <small>(Layer 2)</small></span>
            </a>
          </li>
        <?php }?>
        <?php if(in_array(2, $module_id)){?>
          <li>
            <a class="<?=(($pageSegment == 'attribute')?'active':'')?>" href="{{ url('admin/attribute/list') }}">
              <i class="bi bi-arrow-right"></i><span>Attributes</span>
            </a>
          </li>
        <?php }?>
        <?php if(in_array(2, $module_id)){?>
          <!-- <li>
            <a class="<?=(($pageSegment == 'product')?'active':'')?>" href="{{ url('admin/product/list/') }}">
              <i class="bi bi-arrow-right"></i><span>Products</span>
            </a>
          </li> -->
        <?php }?>
        
      </ul>
    </li><!-- End Masters Nav -->
  <?php }?>

  <?php if((in_array(7, $module_id))) {?>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'product')?'active':'')?>" href="{{ url('admin/product/list') }}">
        <i class="fab fa-product-hunt"></i>
        <span>Listing</span>
      </a>
    </li>
  <?php }?>

  <li class="nav-item">
    <a class="nav-link <?=(($pageSegment == 'image-gallery')?'active':'')?>" href="{{ url('admin/image-gallery') }}">
      <i class="fa-solid fa-image"></i>
      <span>Image Gallery</span>
    </a>
  </li>

  <?php if((in_array(11, $module_id)) || (in_array(12, $module_id))){?>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'orders')?'':'collapsed')?> <?=(($pageSegment == 'orders')?'active':'')?>" data-bs-target="#order-nav" data-bs-toggle="collapse" href="#">
        <i class="fa fa-list-alt"></i><span>Order Management</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="order-nav" class="nav-content collapse <?=(($pageSegment == 'orders')?'show':'')?>" data-bs-parent="#sidebar-nav">
        <?php if(in_array(11, $module_id)){?>
          <li>
            <a class="<?=((($pageSegment == 'orders') && ($pId1 == 1) && ($pId2 == 0))?'active':'')?>" href="<?=url('admin/orders/list/'.Helper::encoded(1).'/'.Helper::encoded(0))?>">
              <i class="bi bi-arrow-right"></i><span>New (<?=$orderCount1?>)</span>
            </a>
          </li>
        <?php }?>
        <?php if(in_array(11, $module_id)){?>
          <li>
            <a class="<?=((($pageSegment == 'orders') && ($pId1 == 2) && ($pId2 == 0))?'active':'')?>" href="<?=url('admin/orders/list/'.Helper::encoded(2).'/'.Helper::encoded(0))?>">
              <i class="bi bi-arrow-right"></i><span>Processing (<?=$orderCount2?>)</span>
            </a>
          </li>
        <?php }?>
        <?php if(in_array(11, $module_id)){?>
          <li>
            <a class="<?=((($pageSegment == 'orders') && ($pId1 == 3) && ($pId2 == 0))?'active':'')?>" href="<?=url('admin/orders/list/'.Helper::encoded(3).'/'.Helper::encoded(0))?>">
              <i class="bi bi-arrow-right"></i><span>Incomplete (<?=$orderCount3?>)</span>
            </a>
          </li>
        <?php }?>
        <?php if(in_array(11, $module_id)){?>
          <li>
            <a class="<?=((($pageSegment == 'orders') && ($pId1 == 4) && ($pId2 == 0))?'active':'')?>" href="<?=url('admin/orders/list/'.Helper::encoded(4).'/'.Helper::encoded(0))?>">
              <i class="bi bi-arrow-right"></i><span>Shipped (<?=$orderCount4?>)</span>
            </a>
          </li>
        <?php }?>
        <?php if(in_array(11, $module_id)){?>
          <li>
            <a class="<?=((($pageSegment == 'orders') && ($pId1 == 5) && ($pId2 == 0))?'active':'')?>" href="<?=url('admin/orders/list/'.Helper::encoded(5).'/'.Helper::encoded(0))?>">
              <i class="bi bi-arrow-right"></i><span>Complete (<?=$orderCount5?>)</span>
            </a>
          </li>
        <?php }?>
        <?php if(in_array(11, $module_id)){?>
          <li>
            <a class="<?=((($pageSegment == 'orders') && ($pId1 == 6) && ($pId2 == 0))?'active':'')?>" href="<?=url('admin/orders/list/'.Helper::encoded(6).'/'.Helper::encoded(0))?>">
              <i class="bi bi-arrow-right"></i><span>Rejected (<?=$orderCount6?>)</span>
            </a>
          </li>
        <?php }?>
        <?php if(in_array(11, $module_id)){?>
          <li>
            <a class="<?=((($pageSegment == 'orders') && ($pId1 == 7) && ($pId2 == 1))?'active':'')?>" href="<?=url('admin/orders/list/'.Helper::encoded(7).'/'.Helper::encoded(1))?>">
              <i class="bi bi-arrow-right"></i><span>Cancelled (<?=$orderCount7?>)</span>
            </a>
          </li>
        <?php }?>
        
      </ul>
    </li><!-- End FAQ Nav -->
  <?php }?>

  <?php if((in_array(11, $module_id)) || (in_array(12, $module_id))){?>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'reports')?'':'collapsed')?> <?=(($pageSegment == 'reports')?'active':'')?>" data-bs-target="#report-nav" data-bs-toggle="collapse" href="#">
        <i class="fa fa-file"></i><span>Reports</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="report-nav" class="nav-content collapse <?=(($pageSegment == 'reports')?'show':'')?>" data-bs-parent="#sidebar-nav">
        <?php if(in_array(11, $module_id)){?>
          <li>
            <a class="<?=(($pageSegment == 'reports')?'active':'')?>" href="{{ url('admin/reports/sales-report') }}">
              <i class="bi bi-arrow-right"></i><span>Sales Report</span>
            </a>
          </li>
        <?php }?>
      </ul>
    </li><!-- End FAQ Nav -->
  <?php }?>

  <?php if((in_array(7, $module_id))) {?>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'customer')?'active':'')?>" href="{{ url('admin/customer/list') }}">
        <i class="fa fa-users"></i>
        <span>Customers</span>
      </a>
    </li>
  <?php }?>

  <?php if((in_array(7, $module_id))) {?>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'reviews')?'active':'')?>" href="{{ url('admin/reviews/list') }}">
        <i class="fa fa-comments"></i>
        <span>Reviews</span>
      </a>
    </li>
  <?php }?>
  
  <?php if((in_array(1, $module_id)) || (in_array(2, $module_id)) || (in_array(3, $module_id)) || (in_array(4, $module_id)) || (in_array(5, $module_id)) || (in_array(6, $module_id))) {?>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'banner' || $pageSegment == 'testimonial' || $pageSegment == 'home-page-section2' || $pageSegment == 'home-page-section5' || $pageSegment == 'home-page-section346')?'':'collapsed')?> <?=(($pageSegment == 'banner' || $pageSegment == 'testimonial' || $pageSegment == 'home-page-section2' || $pageSegment == 'home-page-section5' || $pageSegment == 'home-page-section346')?'active':'')?>" data-bs-target="#home-nav" data-bs-toggle="collapse" href="#">
        <i class="fa fa-home"></i><span>Home Page</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="home-nav" class="nav-content collapse <?=(($pageSegment == 'banner' || $pageSegment == 'testimonial' || $pageSegment == 'home-page-section2' || $pageSegment == 'home-page-section5' || $pageSegment == 'home-page-section346')?'show':'')?>" data-bs-parent="#sidebar-nav">
        <?php if(in_array(1, $module_id)){?>
          <li>
            <a class="<?=(($pageSegment == 'banner')?'active':'')?>" href="{{ url('admin/banner/list') }}">
              <i class="bi bi-arrow-right"></i><span>Banners <small>(Section 1)</small></span>
            </a>
          </li>
        <?php }?>
        
        <?php if(in_array(2, $module_id)){?>
          <li>
            <a class="<?=(($pageSegment == 'home-page-section2')?'active':'')?>" href="{{ url('admin/home-page-section2/list') }}">
              <i class="bi bi-arrow-right"></i><span>Home Page <small>(Section 2)</small></span>
            </a>
          </li>
        <?php }?>
        <?php if(in_array(2, $module_id)){?>
          <li>
            <a class="<?=(($pageSegment == 'home-page-section346')?'active':'')?>" href="{{ url('admin/home-page-section346/list') }}">
              <i class="bi bi-arrow-right"></i><span>Home Page <small>(Section 2-3-4-5-6)</small></span>
            </a>
          </li>
        <?php }?>
        <!-- <?php if(in_array(2, $module_id)){?>
          <li>
            <a class="<?=(($pageSegment == 'home-page-section5')?'active':'')?>" href="{{ url('admin/home-page-section5/list') }}">
              <i class="bi bi-arrow-right"></i><span>Home Page <small>(Section 5)</small></span>
            </a>
          </li>
        <?php }?> -->
        <?php if(in_array(2, $module_id)){?>
          <li>
            <a class="<?=(($pageSegment == 'testimonial')?'active':'')?>" href="{{ url('admin/testimonial/list') }}">
              <i class="bi bi-arrow-right"></i><span>Testimonials <small>(Section 7)</small></span>
            </a>
          </li>
        <?php }?>
        
      </ul>
    </li>
  <?php }?>

  <?php if((in_array(7, $module_id))) {?>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'page')?'active':'')?>" href="{{ url('admin/page/list') }}">
        <i class="fa fa-file-text"></i>
        <span>Content Pages</span>
      </a>
    </li>
  <?php }?>
  
  <?php if((in_array(11, $module_id)) || (in_array(12, $module_id))){?>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'faq' || $pageSegment == 'faq-category')?'':'collapsed')?> <?=(($pageSegment == 'faq' || $pageSegment == 'faq-category')?'active':'')?>" data-bs-target="#faq-nav" data-bs-toggle="collapse" href="#">
        <i class="fa fa-question-circle"></i><span>FAQ</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="faq-nav" class="nav-content collapse <?=(($pageSegment == 'faq' || $pageSegment == 'faq-category')?'show':'')?>" data-bs-parent="#sidebar-nav">
        <?php if(in_array(11, $module_id)){?>
          <li>
            <a class="<?=(($pageSegment == 'faq-category')?'active':'')?>" href="{{ url('admin/faq-category/list') }}">
              <i class="bi bi-arrow-right"></i><span>FAQs Category</span>
            </a>
          </li>
        <?php }?>
        <?php if(in_array(11, $module_id)){?>
          <li>
            <a class="<?=(($pageSegment == 'faq')?'active':'')?>" href="{{ url('admin/faq/list') }}">
              <i class="bi bi-arrow-right"></i><span>FAQs</span>
            </a>
          </li>
        <?php }?>
      </ul>
    </li><!-- End FAQ Nav -->
  <?php }?>

  <?php if((in_array(29, $module_id))) {?>
    <!-- <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'notification')?'active':'')?>" href="{{ url('admin/notification/list') }}">
        <i class="fa fa-file-text"></i>
        <span>Notifications</span>
      </a>
    </li> -->
  <?php }?>

  <?php if((in_array(29, $module_id))) {?>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'newsletter')?'active':'')?>" href="{{ url('admin/newsletter/subscriber-list') }}">
        <i class="fa fa-envelope"></i>
        <span>Subscribers</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'newsletter')?'active':'')?>" href="{{ url('admin/newsletter/list') }}">
        <i class="fa fa-file-text"></i>
        <span>Newsletter</span>
      </a>
    </li>
  <?php }?>

  <?php if(in_array(32, $module_id)){?>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'email-logs')?'active':'')?>" href="{{ url('admin/email-logs') }}">
        <i class="fa fa-envelope"></i>
        <span>Email Logs</span>
      </a>
    </li><!-- End Profile Page Nav -->
  <?php }?>

  <?php if(in_array(33, $module_id)){?>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'login-logs')?'active':'')?>" href="{{ url('admin/login-logs') }}">
        <i class="fa fa-list"></i>
        <span>Login Logs</span>
      </a>
    </li><!-- End Profile Page Nav -->
  <?php }?>

  <?php if(in_array(34, $module_id)){?>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'enquiry')?'active':'')?>" href="{{ url('admin/enquiry/list') }}">
        <i class="fa fa-envelope"></i>
        <span>Contact Enquiries</span>
      </a>
    </li><!-- End Profile Page Nav -->
  <?php }?>

  <?php if(in_array(35, $module_id)){?>
    <li class="nav-item">
      <a class="nav-link <?=(($pageSegment == 'settings')?'active':'')?>" href="{{ url('admin/settings') }}">
        <i class="fa fa-cogs"></i>
        <span>Account Settings</span>
      </a>
    </li><!-- End Profile Page Nav -->
  <?php }?>
</ul>