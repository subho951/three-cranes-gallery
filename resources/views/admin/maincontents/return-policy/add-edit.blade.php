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
      $type             = json_decode($row->type);
      $timeframe        = $row->timeframe;
      $description      = $row->description;
    } else {
      $type             = [];
      $timeframe        = '';
      $description      = '';
    }
    ?>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
              <label for="name" class="col-md-2 col-lg-2 col-form-label">Type</label>
              <div class="col-md-10 col-lg-10">
                <div>
                  <label for="type1">Return</label>
                  <input type="checkbox" name="type[]" id="type1" value="Return" <?=((in_array("Return", $type))?'checked':'')?>>

                  <label for="type2" class="ms-3">Exchange</label>
                  <input type="checkbox" name="type[]" id="type2" value="Exchange" <?=((in_array("Exchange", $type))?'checked':'')?>>
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <label for="timeframe" class="col-md-2 col-lg-2 col-form-label">Timeframe</label>
              <div class="col-md-10 col-lg-10">
                <select name="timeframe" class="form-control" id="timeframe" required>
                  <option value="" selected>Select Timeframe</option>
                  <option value="7" <?=(($timeframe == 7)?'selected':'')?>>7 days of delivery</option>
                  <option value="14" <?=(($timeframe == 14)?'selected':'')?>>14 days of delivery</option>
                  <option value="21" <?=(($timeframe == 21)?'selected':'')?>>21 days of delivery</option>
                  <option value="30" <?=(($timeframe == 30)?'selected':'')?>>30 days of delivery</option>
                  <option value="45" <?=(($timeframe == 45)?'selected':'')?>>45 days of delivery</option>
                  <option value="60" <?=(($timeframe == 60)?'selected':'')?>>60 days of delivery</option>
                  <option value="90" <?=(($timeframe == 90)?'selected':'')?>>90 days of delivery</option>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="description" class="col-md-2 col-lg-2 col-form-label">Description</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="description" class="form-control" id="description" rows="5" required><?=$description?></textarea>
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