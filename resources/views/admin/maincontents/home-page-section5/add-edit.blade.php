<?php
use App\Helpers\Helper;
$sessionType                    = Session::get('type');
$controllerRoute                = $module['controller_route'];
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
      $name                           = $row->name;
      $icon                           = $row->icon;
      $short_description              = $row->short_description;
      $long_description               = $row->long_description;
    } else {
      $name                           = '';
      $icon                           = '';
      $short_description              = '';
      $long_description               = '';
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
              <label for="icon" class="col-md-2 col-lg-2 col-form-label">Icon</label>
              <div class="col-md-10 col-lg-10">
                <input type="file" name="icon" class="form-control" id="icon">
                <small class="text-info">* Only JPG, JPEG, ICO, SVG, PNG files are allowed</small><br>
                <?php if($icon != ''){?>
                  <img src="<?=env('UPLOADS_URL').'home_page/'.$icon?>" class="img-thumbnail" alt="<?=$name?>" style="width: 75px; height: 75px; margin-top: 10px;">
                <?php } else {?>
                  <img src="<?=env('NO_IMAGE')?>" alt="<?=$name?>" class="img-thumbnail" style="width: 75px; height: 75px; margin-top: 10px;">
                <?php }?>
              </div>
            </div>
            <div class="row mb-3">
              <label for="short_description" class="col-md-2 col-lg-2 col-form-label">Short Description</label>
              <div class="col-md-10 col-lg-10">
                <textarea type="text" name="short_description" class="form-control" id="ckeditor1" rows="5" required><?=$short_description?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="long_description" class="col-md-2 col-lg-2 col-form-label">Long Description</label>
              <div class="col-md-10 col-lg-10">
                <textarea type="text" name="long_description" class="form-control" id="ckeditor2" rows="5" required><?=$long_description?></textarea>
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