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
      $faq_category_id      = $row->faq_category_id;
      $question             = $row->question;
      $answer               = $row->answer;
      $is_home_page         = $row->is_home_page;
    } else {
      $faq_category_id      = '';
      $question             = '';
      $answer               = '';
      $is_home_page         = 0;
    }
    ?>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
              <label for="faq_category_id" class="col-md-2 col-lg-2 col-form-label">FAQ Category</label>
              <div class="col-md-10 col-lg-10">
                  <select name="faq_category_id" class="form-control" id="faq_category_id" required>
                    <option value="" selected>Select FAQ Category</option>
                    <?php if($cats){ foreach($cats as $row){?>
                    <option value="<?=$row->id?>" <?=(($row->id == $faq_category_id)?'selected':'')?>><?=$row->name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="question" class="col-md-2 col-lg-2 col-form-label">Question</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="question" class="form-control" id="question" rows="5" required><?=$question?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="answer" class="col-md-2 col-lg-2 col-form-label">Answer</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="answer" class="form-control" id="answer" rows="5" required><?=$answer?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="is_home_page" class="col-md-2 col-lg-2 col-form-label">Home Page Show/Hide</label>
              <div class="col-md-10 col-lg-10">
                <select name="is_home_page" class="form-control" id="is_home_page" required>
                  <option value="" selected>Select Home Page Show/Hide</option>
                  <option value="1" <?=(($is_home_page == 1)?'selected':'')?>>YES</option>
                  <option value="0" <?=(($is_home_page == 0)?'selected':'')?>>NO</option>
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