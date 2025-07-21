<?php
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductAttribute;
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
?>
<div class="pagetitle">
  <h1><?=$page_header?></h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?=url('dashboard')?>">Home</a></li>
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
            <a href="<?=url('' . $controllerRoute . '/add/')?>" class="btn btn-outline-success btn-sm">Add <?=$module['title']?></a>
          </h5>
          <!-- Table with stripped rows -->
          <table class="table datatable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Parent Category<br>Sub Category</th>
                <th scope="col">Name</th>
                <th scope="col">Base Price</th>
                <!-- <th scope="col">Mark-up Price</th> -->
                <th scope="col">SKU</th>
                <th scope="col">Is Feature</th>
                <!-- <th scope="col">Related Products</th>
                <th scope="col">Variants</th> -->
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if($rows){ $sl=1; foreach($rows as $row){?>
                <tr>
                  <th scope="row"><?=$sl++?></th>
                  <td>
                    <?php
                    $cat1                 = Category::select('category_name')->where('id', '=', $row->main_category)->first();
                    echo (($cat1)?$cat1->category_name:'');
                    ?>
                    <br>
                    <?php
                    $cat2                 = Category::select('category_name')->where('id', '=', $row->sub_category)->first();
                    echo (($cat2)?$cat2->category_name:'');
                    ?>
                  </td>
                  <td>
                    <?=$row->name?><br>
                    <?php if($row->external_product_link != ''){?>
                      <a href="<?=$row->external_product_link?>" target="_blank"><span class="badge bg-info"><i class="fa fa-link"></i> External Product Link</span></a>
                    <?php }?>
                  </td>
                  <td><?=number_format($row->base_price,2)?></td>
                  <!-- <td style="text-decoration: line-through;"><?=number_format($row->markup_price,2)?></td> -->
                  <td><?=$row->product_sku?></td>
                  <td>
                    <?php if($row->is_feature){?>
                      <a href="<?=url('' . $controllerRoute . '/change-feature/'.Helper::encoded($row->id))?>" class="btn btn-outline-success btn-sm" title="Activate <?=$module['title']?>">Featured</a>
                    <?php } else {?>
                      <a href="<?=url('' . $controllerRoute . '/change-feature/'.Helper::encoded($row->id))?>" class="btn btn-outline-danger btn-sm" title="Deactivate <?=$module['title']?>">Non-featured</a>
                    <?php }?>
                  </td>
                  <!-- <td><?=count(json_decode($row->related_products))?></td>
                  <td>
                    <?php
                    echo $variant = ProductAttribute::where('product_id', '=', $row->id)->where('status', '!=', 3)->count();
                    ?> Variants<br>
                    <a href="<?=url('variant/list/'.Helper::encoded($row->id))?>" class="btn btn-primary btn-sm">Manage Variants</a>
                  </td> -->
                  <td>
                    <a href="<?=url('' . $controllerRoute . '/edit/'.Helper::encoded($row->id))?>" class="btn btn-outline-primary btn-sm" title="Edit <?=$module['title']?>"><i class="fa fa-edit"></i></a>
                    <a href="<?=url('' . $controllerRoute . '/delete/'.Helper::encoded($row->id))?>" class="btn btn-outline-danger btn-sm" title="Delete <?=$module['title']?>" onclick="return confirm('Do You Want To Delete This <?=$module['title']?>');"><i class="fa fa-trash"></i></a>
                    <?php if($row->status){?>
                      <a href="<?=url('' . $controllerRoute . '/change-status/'.Helper::encoded($row->id))?>" class="btn btn-outline-success btn-sm" title="Activate <?=$module['title']?>"><i class="fa fa-check"></i></a>
                    <?php } else {?>
                      <a href="<?=url('' . $controllerRoute . '/change-status/'.Helper::encoded($row->id))?>" class="btn btn-outline-warning btn-sm" title="Deactivate <?=$module['title']?>"><i class="fa fa-times"></i></a>
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