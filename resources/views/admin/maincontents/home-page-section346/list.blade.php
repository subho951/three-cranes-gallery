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
      $sec2_title                           = $row->sec2_title;
      $sec2_description                     = $row->sec2_description;
      $sec3_title                           = $row->sec3_title;
      $sec3_description                     = $row->sec3_description;
      $sec3_image                           = $row->sec3_image;
      $sec4_title                           = $row->sec4_title;
      $sec4_description                     = $row->sec4_description;
      $sec5_title                           = $row->sec5_title;
      $sec5_description                     = $row->sec5_description;
      $sec6_title                           = $row->sec6_title;
      $sec6_description                     = $row->sec6_description;
      $sec7_title                           = $row->sec7_title;
      $sec7_description                     = $row->sec7_description;
    } else {
      $sec2_title                           = '';
      $sec2_description                     = '';
      $sec3_title                           = '';
      $sec3_description                     = '';
      $sec3_image                           = '';
      $sec4_title                           = '';
      $sec4_description                     = '';
      $sec5_title                           = '';
      $sec5_description                     = '';
      $sec6_title                           = '';
      $sec6_description                     = '';
      $sec7_title                           = '';
      $sec7_description                     = '';
    }
    ?>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          <form method="POST" action="" enctype="multipart/form-data">
            @csrf

            <div style="border:1px solid #dfcdaf; padding: 10px; margin-bottom: 10px; border-radius: 10px;">
              <h4>SECTION 2</h4>
              <div class="row mb-3">
                <label for="sec2_title" class="col-md-2 col-lg-2 col-form-label">Title</label>
                <div class="col-md-10 col-lg-10">
                  <textarea type="text" name="sec2_title" class="form-control" id="sec2_title" rows="5"><?=$sec2_title?></textarea>
                </div>
              </div>
              <div class="row mb-3">
                <label for="sec2_description" class="col-md-2 col-lg-2 col-form-label">Description</label>
                <div class="col-md-10 col-lg-10">
                  <textarea type="text" name="sec2_description" class="form-control" id="sec2_description" rows="5"><?=$sec2_description?></textarea>
                </div>
              </div>
            </div>

            <div style="border:1px solid #dfcdaf; padding: 10px; margin-bottom: 10px; border-radius: 10px;">
              <h4>SECTION 3</h4>
              <div class="row mb-3">
                <label for="sec3_title" class="col-md-2 col-lg-2 col-form-label">Title</label>
                <div class="col-md-10 col-lg-10">
                  <textarea type="text" name="sec3_title" class="form-control" id="sec3_title" rows="5"><?=$sec3_title?></textarea>
                </div>
              </div>
              <div class="row mb-3">
                <label for="sec3_description" class="col-md-2 col-lg-2 col-form-label">Description</label>
                <div class="col-md-10 col-lg-10">
                  <textarea type="text" name="sec3_description" class="form-control" id="sec3_description" rows="5"><?=$sec3_description?></textarea>
                </div>
              </div>
              <div class="row mb-3">
                <label for="sec3_image" class="col-md-2 col-lg-2 col-form-label">Image</label>
                <div class="col-md-10 col-lg-10">
                  <input type="file" name="sec3_image" class="form-control" id="sec3_image">
                  <small class="text-info">* Only JPG, JPEG, ICO, SVG, PNG files are allowed</small><br>
                  <?php if($sec3_image != ''){?>
                    <img src="<?=env('UPLOADS_URL').'home_page/'.$sec3_image?>" class="img-thumbnail" alt="<?=$sec3_title?>" style="width: 75px; height: 75px; margin-top: 10px;">
                  <?php } else {?>
                    <img src="<?=env('NO_IMAGE')?>" alt="<?=$sec3_title?>" class="img-thumbnail" style="width: 75px; height: 75px; margin-top: 10px;">
                  <?php }?>
                </div>
              </div>
            </div>

            <div style="border:1px solid #dfcdaf; padding: 10px; margin-bottom: 10px; border-radius: 10px;">
              <h4>SECTION 4</h4>
              <div class="row mb-3">
                <label for="sec4_title" class="col-md-2 col-lg-2 col-form-label">Title</label>
                <div class="col-md-10 col-lg-10">
                  <textarea type="text" name="sec4_title" class="form-control" id="sec4_title" rows="5" required><?=$sec4_title?></textarea>
                </div>
              </div>
              <div class="row mb-3">
                <label for="sec4_description" class="col-md-2 col-lg-2 col-form-label">Description</label>
                <div class="col-md-10 col-lg-10">
                  <textarea type="text" name="sec4_description" class="form-control" id="sec4_description" rows="5"><?=$sec4_description?></textarea>
                </div>
              </div>
            </div>

            <div style="border:1px solid #dfcdaf; padding: 10px; margin-bottom: 10px; border-radius: 10px;">
              <h4>SECTION 5</h4>
              <div class="row mb-3">
                <label for="sec5_title" class="col-md-2 col-lg-2 col-form-label">Title</label>
                <div class="col-md-10 col-lg-10">
                  <textarea type="text" name="sec5_title" class="form-control" id="sec5_title" rows="5"><?=$sec5_title?></textarea>
                </div>
              </div>
              <div class="row mb-3">
                <label for="sec5_description" class="col-md-2 col-lg-2 col-form-label">Description</label>
                <div class="col-md-10 col-lg-10">
                  <textarea type="text" name="sec5_description" class="form-control" id="sec5_description" rows="5"><?=$sec5_description?></textarea>
                </div>
              </div>
            </div>

            <div style="border:1px solid #dfcdaf; padding: 10px; margin-bottom: 10px; border-radius: 10px;">
              <h4>SECTION 6</h4>
              <div class="row mb-3">
                <label for="sec6_title" class="col-md-2 col-lg-2 col-form-label">Title</label>
                <div class="col-md-10 col-lg-10">
                  <textarea type="text" name="sec6_title" class="form-control" id="sec6_title" rows="5"><?=$sec6_title?></textarea>
                </div>
              </div>
              <div class="row mb-3">
                <label for="sec6_description" class="col-md-2 col-lg-2 col-form-label">Description</label>
                <div class="col-md-10 col-lg-10">
                  <textarea type="text" name="sec6_description" class="form-control" id="sec6_description" rows="5"><?=$sec6_description?></textarea>
                </div>
              </div>
            </div>

            <div style="border:1px solid #dfcdaf; padding: 10px; margin-bottom: 10px; border-radius: 10px;">
              <h4>SECTION 7</h4>
              <div class="row mb-3">
                <label for="sec7_title" class="col-md-2 col-lg-2 col-form-label">Title</label>
                <div class="col-md-10 col-lg-10">
                  <textarea type="text" name="sec7_title" class="form-control" id="sec7_title" rows="5"><?=$sec7_title?></textarea>
                </div>
              </div>
              <div class="row mb-3">
                <label for="sec7_description" class="col-md-2 col-lg-2 col-form-label">Description</label>
                <div class="col-md-10 col-lg-10">
                  <textarea type="text" name="sec7_description" class="form-control" id="sec7_description" rows="5"><?=$sec7_description?></textarea>
                </div>
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