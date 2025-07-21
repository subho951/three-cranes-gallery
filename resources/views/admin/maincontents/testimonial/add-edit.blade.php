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
      $name         = $row->name;
      $review       = $row->review;
      $rate         = $row->rate;
      $image        = $row->image;
      $company_name = $row->company_name;
      $company_logo = $row->company_logo;
      $designation  = $row->designation;
    } else {
      $name         = '';
      $review       = '';
      $rate         = '';
      $image        = '';
      $company_name = '';
      $company_logo = '';
      $designation  = '';
    }
    ?>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
              <label for="name" class="col-md-2 col-lg-2 col-form-label">Name</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="name" class="form-control" id="name" value="<?=$name?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="review" class="col-md-2 col-lg-2 col-form-label">Review</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="review" class="form-control" id="review" rows="5" required><?=$review?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="rate" class="col-md-2 col-lg-2 col-form-label">Rating</label>
              <div class="col-md-10 col-lg-10">
                <select name="rate" class="form-control" id="rate" required>
                  <option value="" selected>Select Rating</option>
                  <option value="1" <?=(($rate == 1)?'selected':'')?>>1</option>
                  <option value="2" <?=(($rate == 2)?'selected':'')?>>2</option>
                  <option value="3" <?=(($rate == 3)?'selected':'')?>>3</option>
                  <option value="4" <?=(($rate == 4)?'selected':'')?>>4</option>
                  <option value="5" <?=(($rate == 5)?'selected':'')?>>5</option>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="company_name" class="col-md-2 col-lg-2 col-form-label">Company Name</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="company_name" class="form-control" id="company_name" value="<?=$company_name?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="designation" class="col-md-2 col-lg-2 col-form-label">Designation</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="designation" class="form-control" id="designation" rows="5" required><?=$designation?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="image" class="col-md-2 col-lg-2 col-form-label">User Image</label>
              <div class="col-md-10 col-lg-10">
                <input type="file" name="image" class="form-control" id="image">
                <small class="text-info">* Only JPG, JPEG, ICO, SVG, PNG files are allowed</small><br>
                <?php if($image != ''){?>
                  <img src="<?=env('UPLOADS_URL').'testimonial/'.$image?>" alt="<?=$name?>" style="width: 150px; height: 150px; margin-top: 10px;border-radius: 50%;">
                <?php } else {?>
                  <img src="<?=env('NO_IMAGE')?>" alt="<?=$name?>" class="img-thumbnail" style="width: 150px; height: 150px; margin-top: 10px;">
                <?php }?>
                <!-- <div class="pt-2">
                  <a href="javascript:void(0);" class="btn btn-danger btn-sm" title="Remove image"><i class="bi bi-trash"></i></a>
                </div> -->
              </div>
            </div>
            <div class="row mb-3">
              <label for="company_logo" class="col-md-2 col-lg-2 col-form-label">Company Logo</label>
              <div class="col-md-10 col-lg-10">
                <input type="file" name="company_logo" class="form-control" id="company_logo">
                <small class="text-info">* Only JPG, JPEG, ICO, SVG, PNG files are allowed</small><br>
                <?php if($company_logo != ''){?>
                  <img src="<?=env('UPLOADS_URL').'testimonial/'.$company_logo?>" alt="<?=$company_name?>" style="width: 150px; height: 150px; margin-top: 10px;border-radius: 50%;">
                <?php } else {?>
                  <img src="<?=env('NO_IMAGE')?>" alt="<?=$company_name?>" class="img-thumbnail" style="width: 150px; height: 150px; margin-top: 10px;">
                <?php }?>
                <!-- <div class="pt-2">
                  <a href="javascript:void(0);" class="btn btn-danger btn-sm" title="Remove image"><i class="bi bi-trash"></i></a>
                </div> -->
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