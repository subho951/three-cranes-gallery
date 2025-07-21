<?php
use App\Helpers\Helper;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductAttribute;
use App\Models\Attribute;
use App\Models\AttributeValue;
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
      $product_attribute_id           = explode("-", $row->product_attribute_id);
      $product_attribute_value_id     = explode("-", $row->product_attribute_value_id);
      $markup_price                   = $row->markup_price;
      $actual_price                   = $row->actual_price;
    } else {
      $product_attribute_id           = [];
      $product_attribute_value_id     = [];
      $markup_price                   = '';
      $actual_price                   = '';
    }
    ?>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          <form method="POST" action="" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
              <?php if($attrs){ foreach($attrs as $attr){?>
                <?php
                $attrValues = AttributeValue::select('id', 'attr_value')->where('attr_id', '=', $attr->id)->where('status', '=', 1)->orderBy('attr_value', 'ASC')->get();
                ?>
                <div class="col-md-6">
                  <input type="hidden" name="product_attribute_id[]" value="<?=$attr->id?>">
                  <label for="product_attribute_value_id<?=$attr->id?>" class="col-form-label"><?=$attr->name?></label>
                  <select name="product_attribute_value_id[]" class="form-control" id="product_attribute_value_id<?=$attr->id?>" required>
                    <option value="" selected>Select <?=$attr->name?></option>
                    <?php if($attrValues){ foreach($attrValues as $attrValue){?>
                    <option value="<?=$attrValue->id?>" <?=((in_array($attrValue->id, $product_attribute_value_id))?'selected':'')?>><?=$attrValue->attr_value?></option>
                    <?php } }?>
                  </select>
                </div>
              <?php } }?>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="markup_price" class="col-form-label">Mark-up Price</label>
                <input type="text" name="markup_price" class="form-control" id="markup_price" value="<?=$markup_price?>" required>
              </div>
              <div class="col-md-6">
                <label for="actual_price" class="col-form-label">Actual Price</label>
                <input type="text" name="actual_price" class="form-control" id="actual_price" value="<?=$actual_price?>" required>
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