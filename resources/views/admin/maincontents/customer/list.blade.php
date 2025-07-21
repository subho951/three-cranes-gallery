<?php
use App\Models\User;
use App\Models\UserLocation;
use App\Models\Order;
use App\Models\UserWishlist;
use App\Models\UserReview;
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
?>
<div class="pagetitle">
  <h1><?=$page_header?></h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?=url('admin/dashboard')?>">Home</a></li>
      <li class="breadcrumb-item active"><?=$page_header?></li>
    </ol>
  </nav>
</div><!-- End Page Title -->
<section class="section">
  <div class="row">
    <div class="col-xl-12">
      @if(session('success_message'))
        <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show autohide" role="alert">
          {{ session('success_message') }}
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      @if(session('error_message'))
        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show autohide" role="alert">
          {{ session('error_message') }}
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
    </div>
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title pt-0">
            <a href="<?=url('admin/' . $controllerRoute . '/add/')?>" class="btn btn-outline-success btn-sm">Add <?=$module['title']?></a>
          </h5>
          <!-- Table with stripped rows -->
          <table class="table datatable global_table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Registration At</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if($rows){ $sl=1; foreach($rows as $row){?>
                <tr>
                  <th scope="row"><?=$sl++?></th>
                  <td><?=$row->first_name.' '.$row->last_name?></td>
                  <td><?=$row->email?></td>
                  <td><?=$row->phone?></td>
                  <td><?=date_format(date_create($row->created_at), "M d, Y h:i A")?></td>
                  <td>
                    <a href="<?=url('admin/' . $controllerRoute . '/delete/'.Helper::encoded($row->id))?>" class="btn btn-outline-danger btn-sm" title="Delete <?=$module['title']?>" onclick="return confirm('Do You Want To Delete This <?=$module['title']?>');"><i class="fa fa-trash"></i></a>
                    <?php if($row->status){?>
                      <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id))?>" class="btn btn-outline-success btn-sm" title="Activate <?=$module['title']?>"><i class="fa fa-check"></i></a>
                    <?php } else {?>
                      <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id))?>" class="btn btn-outline-warning btn-sm" title="Deactivate <?=$module['title']?>"><i class="fa fa-times"></i></a>
                    <?php }?>
                    <br><br>
                    <?php
                    $billingAddr            = UserLocation::where('user_id', '=', $row->id)->where('status', '=', 1)->where('type', '=', 'BILLING')->count();
                    $shippingAddr           = UserLocation::where('user_id', '=', $row->id)->where('status', '=', 1)->where('type', '=', 'SHIPPING')->count();
                    $wishlist               = UserWishlist::where('user_id', '=', $row->id)->where('status', '=', 1)->count();
                    $orders                 = Order::where('cust_id', '=', $row->id)->count();
                    $reviews                = UserReview::where('user_id', '=', $row->id)->count();
                    ?>
                    <a target="_blank" href="<?=url('admin/' . $controllerRoute . '/view-billing-address/'.Helper::encoded($row->id))?>" class="btn btn-outline-primary btn-sm" title="Edit <?=$module['title']?>"><i class="fa fa-location"></i> Billing Address (<?=$billingAddr?>)</a>
                    <br><br>
                    <a target="_blank" href="<?=url('admin/' . $controllerRoute . '/view-shipping-address/'.Helper::encoded($row->id))?>" class="btn btn-outline-primary btn-sm" title="Edit <?=$module['title']?>"><i class="fa fa-location"></i> Shipping Address (<?=$shippingAddr?>)</a>
                    <br><br>
                    <a target="_blank" href="<?=url('admin/' . $controllerRoute . '/view-wishlists/'.Helper::encoded($row->id))?>" class="btn btn-outline-primary btn-sm" title="Edit <?=$module['title']?>"><i class="fa fa-heart"></i> Wishlists (<?=$wishlist?>)</a>
                    <br><br>
                    <a target="_blank" href="<?=url('admin/' . $controllerRoute . '/view-orders/'.Helper::encoded($row->id))?>" class="btn btn-outline-primary btn-sm" title="Edit <?=$module['title']?>"><i class="fa fa-list-alt"></i> Orders (<?=$orders?>)</a>
                    <br><br>
                    <a target="_blank" href="<?=url('admin/' . $controllerRoute . '/view-reviews/'.Helper::encoded($row->id))?>" class="btn btn-outline-primary btn-sm" title="Edit <?=$module['title']?>"><i class="fa fa-comment"></i> Reviews (<?=$reviews?>)</a>
                  </td>
                </tr>
              <?php } }?>
            </tbody>
          </table>
          <!-- End Table with stripped rows -->
        </div>
      </div>
    </div>
  </div>
</section>