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
      $title          = $row->title;
      $description    = $row->description;
      $to_users       = $row->to_users;
      $users          = json_decode($row->users);
    } else {
      $title          = '';
      $description    = '';
      $to_users       = [];
      $users          = [];
    }
    ?>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
              <label for="faq_category_id" class="col-md-2 col-lg-2 col-form-label">User Type</label>
              <div class="col-md-10 col-lg-10">
                  <select class="form-control" name="to_users" id="to_users" onchange="getUsers(this.value);">
                    <option value="" selected>Select User Type</option>
                    <option value="0" <?=(($to_users == 0)?'selected':'')?>>All</option>
                    <option value="1" <?=(($to_users == 1)?'selected':'')?>>Landlords</option>
                    <option value="2" <?=(($to_users == 2)?'selected':'')?>>Tenant</option>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="faq_category_id" class="col-md-2 col-lg-2 col-form-label">Users</label>
              <div class="col-md-10 col-lg-10">
                  <select class="form-control" name="users[]" id="users1" multiple>
                    <?php if($allUsers){ foreach($allUsers as $allUser){?>
                      <option value="<?=$allUser->id?>" <?=((in_array($allUser->id, $users))?'selected':'')?>><?=$allUser->first_name.' '.$allUser->last_name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="title" class="col-md-2 col-lg-2 col-form-label">Title</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="title" class="form-control" id="title" rows="5" required><?=$title?></textarea>
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
