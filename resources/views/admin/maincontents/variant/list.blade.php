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
      <li class="breadcrumb-item"><a href="<?=url('admin/product/list')?>">Product List</a></li>
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
          <h5 class="card-title">
            <a href="<?=url('admin/' . $controllerRoute . '/add/'.Helper::encoded($product_id))?>" class="btn btn-outline-success btn-sm">Add <?=$module['title']?></a>
          </h5>
          <!-- Table with stripped rows -->
          <table class="table datatable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Details</th>
                <th scope="col">Mark-up Price</th>
                <th scope="col">Actual Price</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if($rows){ $sl=1; foreach($rows as $row){?>
                <tr>
                  <th scope="row"><?=$sl++?></th>
                  <td>
                    <ul style="list-style: disc;">
                      <?php
                      $product_attribute_id = explode("-", $row->product_attribute_id);
                      $product_attribute_value_id = explode("-", $row->product_attribute_value_id);
                      if(count($product_attribute_id)>0){
                        for($a=0;$a<count($product_attribute_id);$a++){
                          $attr       = Attribute::select('id', 'name')->where('id', '=', $product_attribute_id[$a])->first();
                          $attrValue  = AttributeValue::select('id', 'attr_value')->where('id', '=', $product_attribute_value_id[$a])->first();
                      ?>
                        <li><strong><?=(($attr)?$attr->name:'')?></strong> : <?=(($attrValue)?$attrValue->attr_value:'')?></li>
                      <?php
                      } }
                      ?>
                    </ul>
                  </td>
                  <td><span style="text-decoration: line-through">$<?=number_format($row->markup_price,2)?></span></td>
                  <td>$<?=number_format($row->actual_price,2)?></td>
                  <td>
                    <a href="<?=url('admin/' . $controllerRoute . '/edit/'.Helper::encoded($product_id).'/'.Helper::encoded($row->id))?>" class="btn btn-outline-primary btn-sm" title="Edit <?=$module['title']?>"><i class="fa fa-edit"></i></a>
                    <a href="<?=url('admin/' . $controllerRoute . '/delete/'.Helper::encoded($product_id).'/'.Helper::encoded($row->id))?>" class="btn btn-outline-danger btn-sm" title="Delete <?=$module['title']?>" onclick="return confirm('Do You Want To Delete This <?=$module['title']?>');"><i class="fa fa-trash"></i></a>
                    <?php if($row->status){?>
                      <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($product_id).'/'.Helper::encoded($row->id))?>" class="btn btn-outline-success btn-sm" title="Activate <?=$module['title']?>"><i class="fa fa-check"></i></a>
                    <?php } else {?>
                      <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($product_id).'/'.Helper::encoded($row->id))?>" class="btn btn-outline-warning btn-sm" title="Deactivate <?=$module['title']?>"><i class="fa fa-times"></i></a>
                    <?php }?>
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