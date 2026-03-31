<?php
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
?>
<div class="pagetitle">
  <h1><?=$page_header?></h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?=url('admin/dashboard')?>">Home</a></li>
      <li class="breadcrumb-item active"><a href="<?=url('admin/' . $controllerRoute . '/list/')?>"><?=$module['title']?> List</a></li>
      <li class="breadcrumb-item active"><?=$page_header?></li>
    </ol>
  </nav>
</div><!-- End Page Title -->
<section class="section profile">
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
    <?php
    if($row){
      $title              = $row->title;
      $coupon_code        = $row->coupon_code;
      $discount_type      = $row->discount_type;
      $discount_amount    = $row->discount_amount;
      $start_date         = $row->start_date;
      $end_date           = $row->end_date;
      $minimum_amount     = $row->minimum_amount;
      $category           = $row->category;
    } else {
      $title              = '';
      $coupon_code        = '';
      $discount_type      = '';
      $discount_amount    = '';
      $start_date         = '';
      $end_date           = '';
      $minimum_amount     = '';
      $category           = '';
    }
    ?>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
              <label for="title" class="col-md-2 col-lg-2 col-form-label">Title</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="title" class="form-control" id="title" value="<?=$title?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="coupon_code" class="col-md-2 col-lg-2 col-form-label">Coupon Code</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="coupon_code" class="form-control" id="coupon_code" value="<?=$coupon_code?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="discount_type" class="col-md-2 col-lg-2 col-form-label">Discount Type</label>
              <div class="col-md-10 col-lg-10">
                <select name="discount_type" class="form-control" id="discount_type" required>
                  <option value="" selected>Select Discount Type</option>
                  <option value="FLAT" <?=(($discount_type == 'FLAT')?'selected':'')?>>FLAT</option>
                  <option value="PERCENTAGE" <?=(($discount_type == 'PERCENTAGE')?'selected':'')?>>PERCENTAGE</option>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="discount_amount" class="col-md-2 col-lg-2 col-form-label">Discount Amount</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="discount_amount" class="form-control" id="discount_amount" value="<?=$discount_amount?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="start_date" class="col-md-2 col-lg-2 col-form-label">Start</label>
              <div class="col-md-10 col-lg-10">
                <input type="date" name="start_date" class="form-control" id="start_date" value="<?=$start_date?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="end_date" class="col-md-2 col-lg-2 col-form-label">End</label>
              <div class="col-md-10 col-lg-10">
                <input type="date" name="end_date" class="form-control" id="end_date" value="<?=$end_date?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="minimum_amount" class="col-md-2 col-lg-2 col-form-label">Minimum Amount</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="minimum_amount" class="form-control" id="minimum_amount" value="<?=$minimum_amount?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="category" class="col-md-2 col-lg-2 col-form-label">Category</label>
              <div class="col-md-10 col-lg-10">
                <select name="category" class="form-control" id="category">
                  <option value="0" <?=(($category == 0)?'selected':'')?>>All Category</option>
                  <?php if($child_cats){ foreach($child_cats as $row){?>
                    <option value="<?=$row->id?>" <?=(($row->id == $category)?'selected':'')?>><?=$row->category_name?></option>
                  <?php } }?>
                </select>
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary"><?=(($row)?'Save':'Add')?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>