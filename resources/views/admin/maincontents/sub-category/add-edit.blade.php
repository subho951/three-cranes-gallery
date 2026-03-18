<?php
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
<style type="text/css">
    .choices__list--multiple .choices__item {
        background-color: #d81636;
        border: 1px solid #d81636;
    }
</style>
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
      $parent_id                  = $row->parent_id;
      $category_name              = $row->category_name;
      $cover_image                = $row->cover_image;
      $banner_image               = $row->banner_image;
      $short_description          = $row->short_description;
      $description                = $row->description;
      $meta_title                 = $row->meta_title;
      $meta_description           = $row->meta_description;
      $meta_keywords              = $row->meta_keywords;
    } else {
      $parent_id                  = '';
      $category_name              = '';
      $cover_image                = '';
      $banner_image               = '';
      $short_description          = '';
      $description                = '';
      $meta_title                 = '';
      $meta_description           = '';
      $meta_keywords              = '';
    }
    ?>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
              <label for="parent_id" class="col-md-2 col-lg-2 col-form-label">Parent Category</label>
              <div class="col-md-10 col-lg-10">
                  <select name="parent_id" class="form-control" id="parent_id" required>
                    <option value="" selected>Select Parent Category</option>
                    <?php if($cats){ foreach($cats as $row){?>
                    <option value="<?=$row->id?>" <?=(($row->id == $parent_id)?'selected':'')?>><?=$row->category_name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="category_name" class="col-md-2 col-lg-2 col-form-label">Category Name</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="category_name" class="form-control" id="category_name" value="<?=$category_name?>" required>
              </div>
            </div>
            <!-- <div class="row mb-3">
              <label for="cover_image" class="col-md-2 col-lg-2 col-form-label">Cover Image</label>
              <div class="col-md-10 col-lg-10">
                <input type="file" name="cover_image" class="form-control" id="cover_image">
                <small class="text-info">* Only JPG, JPEG, ICO, SVG, PNG files are allowed</small><br>
                <?php if($cover_image != ''){?>
                  <img src="<?=env('UPLOADS_URL').'category/'.$cover_image?>" alt="<?=$category_name?>" style="width: 150px; height: 150px; margin-top: 10px;border-radius: 50%;">
                <?php } else {?>
                  <img src="<?=env('NO_IMAGE')?>" alt="<?=$category_name?>" class="img-thumbnail" style="width: 150px; height: 150px; margin-top: 10px;">
                <?php }?>
              </div>
            </div>
            <div class="row mb-3">
              <label for="banner_image" class="col-md-2 col-lg-2 col-form-label">Banner Image</label>
              <div class="col-md-10 col-lg-10">
                <input type="file" name="banner_image" class="form-control" id="banner_image">
                <small class="text-info">* Only JPG, JPEG, ICO, SVG, PNG files are allowed</small><br>
                <?php if($banner_image != ''){?>
                  <img src="<?=env('UPLOADS_URL').'category/'.$banner_image?>" alt="<?=$category_name?>" style="width: 150px; height: 150px; margin-top: 10px;border-radius: 50%;">
                <?php } else {?>
                  <img src="<?=env('NO_IMAGE')?>" alt="<?=$category_name?>" class="img-thumbnail" style="width: 150px; height: 150px; margin-top: 10px;">
                <?php }?>
              </div>
            </div>
            <div class="row mb-3">
              <label for="short_description" class="col-md-2 col-lg-2 col-form-label">Short Description</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="short_description" class="form-control" id="short_description" rows="3"><?=$short_description?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="description" class="col-md-2 col-lg-2 col-form-label">Long Description</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="description" class="form-control" id="ckeditor1" rows="3"><?=$description?></textarea>
              </div>
            </div> -->
            <div class="row mb-3">
              <label for="meta_title" class="col-md-2 col-lg-2 col-form-label">Meta Title</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="meta_title" class="form-control" id="meta_title" rows="3"><?=$meta_title?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="meta_description" class="col-md-2 col-lg-2 col-form-label">Meta Description</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="meta_description" class="form-control" id="meta_description" rows="3"><?=$meta_description?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="meta_keywords" class="col-md-2 col-lg-2 col-form-label">Meta Keywords</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="meta_keywords" class="form-control" id="meta_keywords" rows="3"><?=$meta_keywords?></textarea>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){    
    var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
        removeItemButton: true,
        maxItemCount:30,
        searchResultLimit:30,
        renderChoiceLimit:30
    });     
  });
</script>