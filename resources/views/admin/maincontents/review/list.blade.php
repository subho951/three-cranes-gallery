<?php
use App\Models\User;
use App\Models\UserLocation;
use App\Models\Order;
use App\Models\UserWishlist;
use App\Models\UserReview;
use App\Models\Product;
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
                <th scope="col">Product</th>
                <th scope="col">Customer</th>
                <th scope="col">Rating</th>
                <th scope="col">Title</th>
                <th scope="col">Comment</th>
                <th scope="col">Review Date</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if($rows){ $sl=1; foreach($rows as $row){?>
                <?php
                $getProduct    = Product::where('id', '=', $row->product_id)->first();
                ?>
                <tr>
                  <th scope="row"><?=$sl++?></th>
                  <td>
                    <img src="<?=env('UPLOADS_URL').'product/'.(($getProduct)?$getProduct->cover_image:'')?>" alt="<?=(($getProduct)?$getProduct->name:'')?>" class="img-thumbnail" style="width: 100px; height: 100px;"><br>
                    <?=(($getProduct)?$getProduct->name:'')?><br>
                    <span>$<?=(($getProduct)?number_format($getProduct->base_price,2):0.00)?></span>
                  </td>
                  <td>
                    <?=$row->name?><br>
                    <?=$row->email?>
                  </td>
                  <td>
                    <?=$row->rating?>
                  </td>
                  <td>
                    <?=$row->title?>
                  </td>
                  <td>
                    <?=$row->comment?>
                  </td>
                  <td>
                    <?=date_format(date_create($row->created_at), "M d, Y h:i A")?>
                  </td>
                  <td>
                    <?php if($row->status == 0){?>
                      <span class="badge bg-warning">PENDING</span>
                    <?php } elseif($row->status == 1){?>
                      <span class="badge bg-success">APPROVED</span><br>
                      <small class="mt-3"><?=date_format(date_create($row->approve_reject_timestamp), "M d, Y h:i A")?></small>
                    <?php } elseif($row->status == 3){?>
                      <span class="badge bg-danger">REJECTED</span><br>
                      <small class="mt-3"><?=date_format(date_create($row->approve_reject_timestamp), "M d, Y h:i A")?></small>
                    <?php }?>
                  </td>
                  <td>
                    <a href="<?=url('admin/' . $controllerRoute . '/delete/'.Helper::encoded($row->id))?>" class="btn btn-outline-danger btn-sm" title="Delete <?=$module['title']?>" onclick="return confirm('Do You Want To Delete This <?=$module['title']?>');"><i class="fa fa-trash"></i></a>
                    <br><br>
                    <?php if($row->status == 1){?>
                      <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id).'/'.Helper::encoded(3))?>" class="btn btn-danger btn-sm" style="font-size: 10px;" title="Activate <?=$module['title']?>"><i class="fa fa-times"></i> Click To Reject</a>
                    <?php } elseif($row->status == 3){?>
                      <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id).'/'.Helper::encoded(1))?>" class="btn btn-success btn-sm" style="font-size: 10px;" title="Deactivate <?=$module['title']?>"><i class="fa fa-check"></i> Click To Approve</a>
                    <?php } elseif($row->status == 0){?>
                      <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id).'/'.Helper::encoded(1))?>" class="btn btn-success btn-sm" style="font-size: 10px;" title="Deactivate <?=$module['title']?>"><i class="fa fa-check"></i> Click To Approve</a>
                      <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id).'/'.Helper::encoded(3))?>" class="btn btn-danger btn-sm" style="font-size: 10px;" title="Activate <?=$module['title']?>"><i class="fa fa-times"></i> Click To Reject</a>
                    <?php }?>
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