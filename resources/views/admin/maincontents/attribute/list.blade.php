<?php
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
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
          <!-- <h5 class="card-title">
            <a href="<?=url('admin/' . $controllerRoute . '/add/')?>" class="btn btn-outline-success btn-sm">Add <?=$module['title']?></a>
          </h5> -->
          <!-- Table with stripped rows -->
          <table class="table datatable global_table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Parent Category</th>
                <th scope="col">Sub Category Name</th>
                <th scope="col">Attributes</th>
                <th scope="col-2">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if($rows){ $sl=1; foreach($rows as $row){?>
                <tr>
                  <th scope="row"><?=$sl++?></th>
                  <td>
                    <?php
                    $cat                 = Category::select('category_name')->where('id', '=', $row->parent_id)->first();
                    echo (($cat)?$cat->category_name:'');
                    ?>
                  </td>
                  <td><?=$row->category_name?></td>
                  <td>
                    <ul class="list-group">
                      <?php
                      $checkAttrs = Attribute::select('id', 'name')->where('parent_category', '=', $row->parent_id)->where('sub_category_id', '=', $row->id)->where('status', '!=', 3)->get();
                      if($checkAttrs){ foreach($checkAttrs as $checkAttr){
                      ?>
                        <li class="list-group-item">
                          <h6><strong><?=$checkAttr->name?></strong></h6>
                          <?php
                          $getAttrVals = AttributeValue::select('attr_value')->where('attr_id', '=', $checkAttr->id)->where('status', '!=', 3)->get();
                          if($getAttrVals){ foreach($getAttrVals as $getAttrVal){
                          ?>
                            <span class="badge bg-warning text-dark"><?=$getAttrVal->attr_value?></span>
                          <?php } }?>
                        </li>
                      <?php } }?>
                    </ul>
                  </td>
                  <td>
                    <div class="d-flex">
                    <a href="<?=url('admin/' . $controllerRoute . '/edit/'.Helper::encoded($row->id))?>" class="btn btn-outline-primary btn-sm m-1" title="Edit <?=$module['title']?>"><i class="fa fa-edit"></i></a>
                    <a href="<?=url('admin/' . $controllerRoute . '/delete/'.Helper::encoded($row->id))?>" class="btn btn-outline-danger btn-sm m-1" title="Delete <?=$module['title']?>" onclick="return confirm('Do You Want To Delete This <?=$module['title']?>');"><i class="fa fa-trash"></i></a>
                    <?php if($row->status){?>
                      <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id))?>" class="btn btn-outline-success btn-sm m-1" title="Activate <?=$module['title']?>"><i class="fa fa-check"></i></a>
                    <?php } else {?>
                      <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id))?>" class="btn btn-outline-warning btn-sm m-1" title="Deactivate <?=$module['title']?>"><i class="fa fa-times"></i></a>
                    <?php }?>
                    </div>
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