<?php
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Faq;
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
?>
<style>
.datatable-table thead tr{
    border-radius: 5px
}
.datatable-table thead th{
    background: #dfcdaf;
    color: #8B2525;
    padding: 12px;
    font-size: 14px;
}
.datatable-table th:first-child{
    border-top-left-radius: 5px;
}
.datatable-table th:last-child{
    border-top-right-radius: 5px;
}
.datatable-table tbody tr:nth-child(odd){
    background: #dfcdaf24;
}
</style>
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
          <h5 class="card-title">
            
          </h5>
          <!-- Table with stripped rows -->
          <table class="table datatable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Product Image</th>
                <th scope="col">Product Name</th>
                <th scope="col">Product Price</th>
                <th scope="col">Date</th>
              </tr>
            </thead>
            <tbody>
              <?php if($rows){ $sl=1; foreach($rows as $row){?>
                <?php
                $getProduct    = Product::where('id', '=', $row->product_id)->first();
                ?>
                <tr>
                  <th scope="row"><?=$sl++?></th>
                  <td>
                    <img src="<?=env('UPLOADS_URL').'product/'.(($getProduct)?$getProduct->cover_image:'')?>" alt="<?=(($getProduct)?$getProduct->name:'')?>" class="img-thumbnail" style="width: 100px; height: 100px;">
                  </td>
                  <td>
                    <?=(($getProduct)?$getProduct->name:'')?>
                  </td>
                  <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;border-top:0">
                    <span>$<?=(($getProduct)?number_format($getProduct->base_price,2):0.00)?></span>
                  </td>
                  <td>
                    <?=date_format(date_create($row->created_at), "M d, Y h:i A")?>
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