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
      $section        = $row->section;
      $banner_text    = $row->banner_text;
      $banner_text2   = $row->banner_text2;
      $banner_link    = $row->banner_link;
      $banner_image   = $row->banner_image;
    } else {
      $section        = '';
      $banner_text    = '';
      $banner_text2   = '';
      $banner_link    = '';
      $banner_image   = '';
    }
    ?>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
              <label for="section" class="col-md-2 col-lg-2 col-form-label">Section</label>
              <div class="col-md-10 col-lg-10">
                <select name="section" class="form-control" id="section" required>
                  <option value="" selected>Select Section</option>
                  <option value="1" <?=(($section == 1)?'selected':'')?>>Section 1</option>
                  <option value="2" <?=(($section == 2)?'selected':'')?>>Section 2</option>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="banner_text" class="col-md-2 col-lg-2 col-form-label">Banner Title Text</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="banner_text" class="form-control" id="banner_text" value="<?=$banner_text?>">
              </div>
            </div>
            <div class="row mb-3">
              <label for="banner_text2" class="col-md-2 col-lg-2 col-form-label">Banner Short Description Text</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="banner_text2" class="form-control" id="banner_text2" value="<?=$banner_text2?>">
              </div>
            </div>
            <div class="row mb-3">
              <label for="banner_link" class="col-md-2 col-lg-2 col-form-label">Banner Link</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="banner_link" class="form-control" id="banner_link" value="<?=$banner_link?>">
              </div>
            </div>
            <div class="row mb-3">
              <label for="banner_image" class="col-md-2 col-lg-2 col-form-label">Banner Image</label>
              <div class="col-md-10 col-lg-10">
                <input type="file" name="banner_image" class="form-control" id="banner_image" <?=((!empty($row))?'':'required')?>>
                <small class="text-info">* Only JPG, JPEG, ICO, SVG, PNG files are allowed</small><br>
                <?php if($banner_image != ''){?>
                  <img src="<?=env('UPLOADS_URL').'banner/'.$banner_image?>" class="img-thumbnail" alt="<?=$banner_text?>" style="width: 250px; height: 120px; margin-top: 10px;">
                <?php } else {?>
                  <img src="<?=env('NO_IMAGE')?>" alt="<?=$banner_text?>" class="img-thumbnail" style="width: 150px; height: 150px; margin-top: 10px;">
                <?php }?>                
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