<?php
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductAttribute;
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
// Helper::pr(Session::all());
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
<style type="text/css">
   .choices__list--multiple .choices__item {
      background-color: #d81636;
      border: 1px solid #d81636;
   }
   .very-small{
      font-size: 10px;
   }
  .pagination {
      display: flex;
      list-style: none;
      padding: 0;
  }
  .pagination li {
      margin: 0 5px;
  }
  .pagination a {
      text-decoration: none;
      color: #007bff;
  }
  .pagination .active span {
      font-weight: bold;
  }
  .relative .z-0 .inline-flex .shadow-sm{
   display: none;
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
</div>
<!-- End Page Title -->
<!-- Modal -->
<div class="modal fade add-product-modal-details" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <form method="POST" action="">
         @csrf
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="staticBackdropLabel">First, tell us about your listing</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <p>This basic info helps us understand your listing and how it meets our policies. Next you can dive into the full listing form to add all the details that make your item special.</p>
               <div class="col-lg-12 mb-4">
                  <h5 class="sub-title">
                     Who made it? <span style="color:red;">*</span>
                  </h5>
               </div>
               <div class="col-lg-12 mb-4">
                  <div class="renuel-radio mb-2">
                     <input type="radio" id="who_made_it1" name="who_made_it" value="I did" required>
                     <label for="who_made_it1">I did</label>
                  </div>
                  <div class="renuel-radio mb-2">
                     <input type="radio" id="who_made_it2" name="who_made_it" value="A member of my shop" required>
                     <label for="who_made_it2">A member of my shop</label>
                  </div>
                  <div class="renuel-radio">
                     <input type="radio" id="who_made_it3" name="who_made_it" value="Another company or person" required>
                     <label for="who_made_it3">Another company or person</label>
                  </div>
               </div>
               <div class="col-lg-12 mb-4">
                  <h5 class="sub-title">
                     What is it? <span style="color:red;">*</span>
                  </h5>
               </div>
               <div class="col-lg-12 mb-4">
                  <div class="renuel-radio mb-2">
                     <input type="radio" id="what_is_it1" name="what_is_it" value="A finished product" required>
                     <label for="what_is_it1">A finished product</label>
                  </div>
                  <div class="renuel-radio">
                     <input type="radio" id="what_is_it2" name="what_is_it" value="A supply or tool to make things" required>
                     <label for="what_is_it2">A supply or tool to make things</label>
                  </div>
               </div>
               <div class="col-lg-6 mb-3">
                  <label for="manufacture_year" class="form-label">When was it made?</label>
                  <select class="form-select" aria-label="Default select example" id="manufacture_year" name="manufacture_year">
                     <option value="" selected>When did you make it ?</option>
                     <?php for($year=date('Y');$year>=1900;$year--) {?>
                        <option value="<?=$year?>"><?=$year?></option>
                     <?php }?>
                  </select>
               </div>
               <div class="col-lg-6 mb-3">
                  <label for="sub_category" class="form-label">Sub Categories</label>
                  <select class="form-select" aria-label="Default select example" id="sub_category" name="sub_category">
                     <option value="" selected>Sub Categories</option>
                     <?php if($subcategories){ foreach($subcategories as $subcategory){?>
                        <?php
                        $getParentCategory = Category::select('id', 'category_name')->where('status', '=', 1)->where('id', '=', $subcategory->parent_id)->first();
                        ?>
                        <option value="<?=$subcategory->id?>"><?=(($getParentCategory)?$getParentCategory->category_name:'')?> - <?=$subcategory->category_name?></option>
                     <?php } }?>
                  </select>
               </div>

               <div class="col-lg-12 mb-4">
                  <h5 class="sub-title">
                     How does your shop produce this item? <span style="color:red;">*</span>
                  </h5>
               </div>
               <div class="col-lg-12 mb-4">
                  <?php if($shop_produce_items){ $k=1; foreach($shop_produce_items as $shop_produce_item){?>
                     <div class="renuel-radio mb-2">
                        <input type="radio" id="shop_produce_item<?=$k?>" name="shop_produce_item" value="<?=$shop_produce_item->id?>" required>
                        <label for="shop_produce_item<?=$k?>"><?=$shop_produce_item->name?></label>
                        <small><?=$shop_produce_item->description?></small>
                     </div>
                  <?php $k++; } }?>
               </div>

               <div class="col-lg-12 mb-4">
                  <h5 class="sub-title">
                     What tools are used to make this item? <span style="color:red;">*</span>
                  </h5>
               </div>
               <div class="col-lg-12 mb-4">
                  <?php if($tools_useds){ $k=1; foreach($tools_useds as $tools_used){?>
                     <div class="renuel-radio mb-2">
                        <input type="checkbox" id="tools_used<?=$k?>" name="tools_used[]" value="<?=$tools_used->id?>">
                        <label for="tools_used<?=$k?>"><?=$tools_used->name?></label>
                        <small><?=$tools_used->description?></small>
                     </div>
                  <?php $k++; } }?>
               </div>
            </div>
            <div class="modal-footer d-flex justify-content-between" style="border:none;padding-top:0;">
               <a href="javascript:void(0);" data-bs-target="#exampleModalToggle" data-bs-toggle="modal" data-bs-dismiss="modal" class="btn btn-outline-danger">Cancel</a>
               <ul class="footer-btn-group">
                  <li>
                     <!-- <a class="bg" href="">Continue </a> -->
                     <button type="submit" class="bg btn btn-outline-success" name="mode" value="Continue">Continue</button>
                  </li>
               </ul>
            </div>
         </div>
      </form>
   </div>
</div>
<!-------------------- Modal-end------------------------- -->
<section class="section shop-list">
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
   </div>
   <div class="shop-list-section-1">
      <div class="row align-items-center">
         <div class="col-lg-6">
            <h2><?=$total_products?> products found</h2>
         </div>
         <div class="col-lg-6">
            <div class="row">
               <div class="col-lg-7">
                  <form class="header-search border rounded-5" method="POST">
                     @csrf
                     <button type="submit" class="btn btn-search"><i class="search-icon fas fa-search"></i></button>
                     <input type="text" list="browsers" id="searchText" placeholder="Search by title, tag or SKU" name="search">
                  </form>
                  <ul id="suggestion-section" style="list-style: none;margin-left: -16px;height: 400px;overflow-y: scroll;">
                     <!-- <li>
                        <div class="row" style="border: 1px solid #00000036;padding: 1px;border-radius: 3px;background-color: #00000012;">
                           <div class="col-md-2">
                              <img src="https://admin.threecranesgallery.com/public/uploads/product/6787d94a3ea98.avif" style="width:30px; height: 30px; border-radius: 50%;">
                           </div>
                           <div class="col-md-10">
                              <span style="font-size: 12px;">Product Name</span>
                           </div>
                        </div>
                     </li> -->
                  </ul>
               </div>
               <div class="col-lg-5">
                  <a class="new-btn-style add-listing" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#staticBackdrop">+Add a Listing</a>
               </div>
            </div>
         </div>
      </div>
   </div>
   <hr>
   <div class="shop-list-section-2 mt-4 pt-2">
      <div class="row">
         <div class="col-lg-10">
            <form method="POST" action="">
               @csrf
               <div class="product-left-side">
                  <div class="product-left-side-top-bar">
                     <ul>
                        <li>
                           <div class="dropdown">
                              <input type="checkbox" id="checkAll" name="checkAll"/>
                              <!-- <button class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></button>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                 <li><a class="dropdown-item" href="#">Action</a></li>
                                 <li><a class="dropdown-item" href="#">Another action</a></li>
                                 <li><a class="dropdown-item" href="#">Something else here</a></li>
                              </ul> -->
                           </div>
                        </li>
                        <li class="action-bar" style="display: none;">
                           <button type="submit" class="bg" name="mode" value="Active">Active</button>
                        </li>
                        <li class="action-bar" style="display: none;">
                           <button type="submit" class="bg" name="mode" value="Deactive">Deactive</button>
                        </li>
                        <li class="action-bar" style="display: none;">
                           <button type="submit" class="bg" name="mode" value="Delete">Delete</button>
                        </li>
                        <!-- <li>
                           <div class="dropdown">
                              <button class="dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">Editing option</button>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                 <li><a class="dropdown-item" href="#">Action</a></li>
                                 <li><a class="dropdown-item" href="#">Another action</a></li>
                                 <li><a class="dropdown-item" href="#">Something else here</a></li>
                              </ul>
                           </div>
                        </li> -->
                     </ul>
                  </div>
                  <?php if($view_type == 'LIST'){?>
      			      <!-------------------- list-view-start------------------------- -->
                     <div class="listing_box">
                        <?php
                        if($rows){ $sl=1; foreach($rows as $row){
                        ?>
                           <div class="product-items-list <?=(($sl % 2 == 0)?'bg-color':'')?>">
                              <div class="row align-items-center">
                                 <div class="col-lg-1">
                                    <div class="product-items-checkbox text-center">
                                       <input type="checkbox" name="product_id[]" class="checkItem" value="<?=$row->id?>" id="productId<?=$row->id?>">
                                    </div>
                                 </div>
                                 <div class="col-lg-1">
                                    <div class="product-items-images">
                                       <a href="<?=url('admin/' . $controllerRoute . '/edit/'.Helper::encoded($row->id))?>">
                                          <?php if($row->cover_image != ''){?>
                                             <img src="<?=env('UPLOADS_URL').'product/'.$row->cover_image?>" alt="<?=$row->name?>" style="width: 100%; height:100px;">
                                          <?php } else {?>
                                             <img src="<?=env('NO_IMAGE')?>" alt="<?=$row->name?>" class="img-thumbnail" style="width: 100%; height:100px;">
                                          <?php }?>
                                       </a>
                                    </div>
                                 </div>
                                 <div class="col-lg-10">
                                    <div class="product-items-descr">
                                       <div class="items-descr-name">
                                       <a href="<?=url('admin/' . $controllerRoute . '/edit/'.Helper::encoded($row->id))?>"><h2><?=$row->name?></h2></a>
                                       </div>
                                       <div class="items-descr-rating-setting">
                                          <div class="items-descr-rating">
                                             <?php if($row->is_feature){?>
                                                <i class="fa-solid fa-star" style="color: orange;"></i>
                                             <?php } else {?>
                                                <i class="fa-regular fa-star"></i>
                                             <?php } ?>
                                          </div>
                                          <div class="items-setting">
                                             <div class="dropdown">
                                                <button class="dropdown-toggle" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-gear"></i></button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                                                   <li><a class="dropdown-item" href="<?=url('admin/' . $controllerRoute . '/edit/'.Helper::encoded($row->id))?>">Edit</a></li>
                                                   <li><a class="dropdown-item" href="<?=url('admin/' . $controllerRoute . '/copy/'.Helper::encoded($row->id))?>" onclick="return confirm('Do You Want To Copy <?=$row->name?>');">Copy</a></li>
                                                   <?php if($row->status){?>
                                                      <li><a class="dropdown-item" href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id))?>">Deactivate</a></li>
                                                   <?php } else {?>
                                                      <li><a class="dropdown-item" href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id))?>">Activate</a></li>
                                                   <?php }?>
                                                   <?php if($row->is_feature){?>
                                                      <li><a class="dropdown-item" href="<?=url('admin/' . $controllerRoute . '/change-feature/'.Helper::encoded($row->id))?>">Mark Not Featured</a></li>
                                                   <?php } else {?>
                                                      <li><a class="dropdown-item" href="<?=url('admin/' . $controllerRoute . '/change-feature/'.Helper::encoded($row->id))?>">Mark Featured</a></li>
                                                   <?php }?>
                                                   <li><a class="dropdown-item" href="<?=url('admin/' . $controllerRoute . '/delete/'.Helper::encoded($row->id))?>" onclick="return confirm('Do You Want To Delete This <?=$module['title']?>');">Delete</a></li>
                                                </ul>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <a href="<?=url('admin/' . $controllerRoute . '/edit/'.Helper::encoded($row->id))?>">
                                       <ul class="stoke-details">
                                          <li><?=$row->sub_category_name?></li>
                                          <li>$<?=number_format($row->discounted_price,2)?> <span style="text-decoration: line-through;">$<?=number_format($row->base_price,2)?></span> <span>(<?=$row->price_percentage?>)</span></li>
                                          <li><?=$row->product_sku?></li>
                                          <li>
                                             <?php if($row->status == 1){?>
                                                <span class="badge bg-success"><i class="fa fa-check"></i> Active</span>
                                             <?php } elseif($row->status == 2){?>
                                                <span class="badge bg-primary"><i class="fa fa-times"></i> Draft</span>
                                             <?php } elseif($row->status == 0){?>
                                                <span class="badge bg-danger"><i class="fa fa-times"></i> Deactive</span>
                                             <?php }?>
                                          </li>
                                       </ul>
                                    </a>
                                 </div>
                              </div>
                           </div>
                        <?php $sl++; } }?>
                     </div>
      			      <!-------------------- list-view-end------------------------- -->
                  <?php }?>
                  <?php if($view_type == 'GRID'){?>
      			      <!-------------------- grid-view-start------------------------- -->
      			      <div class="row">
         				   <?php
                        if($rows){ foreach($rows as $row){
                        ?>
            				   <div class="col-lg-3 mb-3">
            					  <div class="product-items-grid d-flex flex-column h-100">
            						 <div class="product-img">
                                 <a href="<?=url('admin/' . $controllerRoute . '/edit/'.Helper::encoded($row->id))?>">
                                    <?php if($row->cover_image != ''){?>
                                       <img src="<?=env('UPLOADS_URL').'product/'.$row->cover_image?>" alt="<?=$row->name?>">
                                    <?php } else {?>
                                       <img src="<?=env('NO_IMAGE')?>" alt="<?=$row->name?>" class="img-thumbnail">
                                    <?php }?>
                                 </a>
            						 </div>
            						 <div class="product-grid-details d-flex flex-column justify-content-between h-100">
            							<div class="p-3">
                                    <a href="<?=url('admin/' . $controllerRoute . '/edit/'.Helper::encoded($row->id))?>">
                                       <h2><?=$row->name?></h2>
                                       <div class="grid-stoke-details">
                                       <p><?=$row->sub_category_name?></p>
                                       <p>$<?=number_format($row->discounted_price,2)?> <span style="text-decoration: line-through;">$<?=number_format($row->base_price,2)?></span> <span>(<?=$row->price_percentage?>)</span></p>
                                       <p><?=$row->product_sku?></p>
                                       </div>
                                    </a>
            							</div>
            							<div class="product-grid-footer border-top">
            							   <ul class="d-flex justify-content-center flex-wrap align-items-center">
            								  <li>
            									 <div class="form-check">
            										<input class="form-check-input checkItem" type="checkbox" name="product_id[]" value="<?=$row->id?>" id="productId<?=$row->id?>">
            									 </div>
            								  </li>
            								  <li>
                                          <?php if($row->is_feature){?>
                                             <i class="fa-solid fa-star" style="color: orange;"></i>
                                          <?php } else {?>
                                             <i class="fa-regular fa-star"></i>
                                          <?php } ?>
                                       </li>
         								      <li class="items-setting">
            									 <div class="dropdown">
            										<button class="dropdown-toggle" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-gear"></i></button>
            										<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
            										   <li><a class="dropdown-item" href="<?=url('admin/' . $controllerRoute . '/edit/'.Helper::encoded($row->id))?>">Edit</a></li>
                                             <li><a class="dropdown-item" href="<?=url('admin/' . $controllerRoute . '/copy/'.Helper::encoded($row->id))?>" onclick="return confirm('Do You Want To Copy <?=$row->name?>');">Copy</a></li>
                                             <?php if($row->status){?>
                                                <li><a class="dropdown-item" href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id))?>">Deactivate</a></li>
                                             <?php } else {?>
                                                <li><a class="dropdown-item" href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id))?>">Activate</a></li>
                                             <?php }?>
                                             <?php if($row->is_feature){?>
                                                <li><a class="dropdown-item" href="<?=url('admin/' . $controllerRoute . '/change-feature/'.Helper::encoded($row->id))?>">Mark Not Featured</a></li>
                                             <?php } else {?>
                                                <li><a class="dropdown-item" href="<?=url('admin/' . $controllerRoute . '/change-feature/'.Helper::encoded($row->id))?>">Mark Featured</a></li>
                                             <?php }?>
                                             <li><a class="dropdown-item" href="<?=url('admin/' . $controllerRoute . '/delete/'.Helper::encoded($row->id))?>" onclick="return confirm('Do You Want To Delete This <?=$module['title']?>');">Delete</a></li>
            										</ul>
            									 </div>
            								  </li>
                                      <li class="w-auto">
                                          <?php if($row->status == 1){?>
                                             <span class="badge bg-success"><i class="fa fa-check"></i> Active</span>
                                          <?php } elseif($row->status == 2){?>
                                             <span class="badge bg-primary"><i class="fa fa-times"></i> Draft</span>
                                          <?php } elseif($row->status == 0){?>
                                             <span class="badge bg-danger"><i class="fa fa-times"></i> Deactive</span>
                                          <?php }?>
                                      </li>
            							   </ul>
            							</div>
            						 </div>
            					  </div>
            				   </div>
                        <?php } }?>
      			      </div>
      			      <!-------------------- grid-view-end------------------------- -->
                  <?php }?>
               </div>
            </form>
         </div>
         <div class="col-lg-2">
            <div class="product-right-side border">
               <div class="product-right-side-top-1">
                  <ul>
                     <li>
                        <!-- <div class="form-check form-switch">
                           <label class="form-check-label" for="flexSwitchCheckChecked">Stats</label>
                           <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
                        </div> -->
                     </li>
                     <li>
                        <div class="shop-tab">
                           <a href="javascript:void(0);" class="product-view" data-product-view-type="GRID">
                              <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="grid-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-grid-2 ">
                                 <path fill="currentColor" d="M224 80c0-26.5-21.5-48-48-48L80 32C53.5 32 32 53.5 32 80l0 96c0 26.5 21.5 48 48 48l96 0c26.5 0 48-21.5 48-48l0-96zm0 256c0-26.5-21.5-48-48-48l-96 0c-26.5 0-48 21.5-48 48l0 96c0 26.5 21.5 48 48 48l96 0c26.5 0 48-21.5 48-48l0-96zM288 80l0 96c0 26.5 21.5 48 48 48l96 0c26.5 0 48-21.5 48-48l0-96c0-26.5-21.5-48-48-48l-96 0c-26.5 0-48 21.5-48 48zM480 336c0-26.5-21.5-48-48-48l-96 0c-26.5 0-48 21.5-48 48l0 96c0 26.5 21.5 48 48 48l96 0c26.5 0 48-21.5 48-48l0-96z" class=""></path>
                              </svg>
                           </a>
                           <a href="javascript:void(0);" class="product-view" data-product-view-type="LIST">
                              <i class="fa-solid fa-list"></i>
                           </a>
                        </div>
                     </li>
                  </ul>
               </div>
               <div class="row">
                  <div class="col-lg-12 mb-3">
                     <label for="exampleInputEmail1" class="form-label">Sort</label>
                     <form method="GET" name="PostName" action="<?=url('admin/product/product-sorting/')?>">
                        <input type="hidden" name="mode" value="filter">
                        <select class="form-select" aria-label="Default select example" name="filter_by" onchange="PostName.submit()">
                           <option value="" selected="" selected>None</option>
                           <option value="name-asc" <?=(($filter_by == 'name-asc')?'selected':'')?>>Name (A-Z)</option>
                           <option value="name-desc" <?=(($filter_by == 'name-desc')?'selected':'')?>>Name (Z-A)</option>
                           <option value="price-asc" <?=(($filter_by == 'price-asc')?'selected':'')?>>Price (A-Z)</option>
                           <option value="price-desc" <?=(($filter_by == 'price-desc')?'selected':'')?>>Price (Z-A)</option>
                        </select>
                     </form>
                  </div>
                  <div class="col-lg-12 mb-3">
                     <h5>Listing Status</h5>
                     <form method="GET" name="PostName2" action="<?=url('admin/product/product-filter/')?>">
                        <input type="hidden" name="mode" value="filter">
                        <div class="renuel-radio mb-2">
                           <input type="radio" id="Status0" name="listing_status" value="-1" <?=(($filter == -1)?'checked':'')?> onchange="PostName2.submit()">
                           <label for="Status0">All (<?=$all_products?>)</label>
                        </div>
                        <div class="renuel-radio mb-2">
                           <input type="radio" id="Status1" name="listing_status" value="1" <?=(($filter == 1)?'checked':'')?> onchange="PostName2.submit()">
                           <label for="Status1">Active (<?=$active_products?>)</label>
                        </div>
                        <div class="renuel-radio mb-2">
                           <input type="radio" id="Status2" name="listing_status" value="0" <?=(($filter == 0)?'checked':'')?> onchange="PostName2.submit()">
                           <label for="Status2">Deactive (<?=$deactive_products?>)</label>
                        </div>
                        <div class="renuel-radio mb-2">
                           <input type="radio" id="Status3" name="listing_status" value="2" <?=(($filter == 2)?'checked':'')?> onchange="PostName2.submit()">
                           <label for="Status3">Draft (<?=$draft_products?>)</label>
                        </div>
                     </form>
                  </div>
                  <div class="col-lg-12 mb-3">
                     <h5>Category Tree</h5>
                     <form method="GET" name="PostName3" action="<?=url('admin/product/product-category/')?>">
                        <input type="hidden" name="mode" value="category">
                        <?php if($categories){ foreach($categories as $cat){?>
                           <div class="renuel-radio mb-2">
                              <input type="radio" id="Status<?=$cat['category_id']?>" name="listing_category" value="<?=$cat['category_id']?>" <?=(($categoryVal == $cat['category_id'])?'checked':'')?> onchange="PostName3.submit()">
                              <label for="Status<?=$cat['category_id']?>"><?=$cat['category_name']?> (<?=$cat['product_count']?>)</label>
                              <input type="hidden" name="category_name[<?=$cat['category_id']?>][]" value="<?=$cat['category_name']?>">
                           </div>
                        <?php } }?>
                     </form>
                  </div>

               </div>
            </div>
         </div>
         <!-- Pagination Links -->
      </div>
   </div>
   <div class="pagination-links mt-3">
      @if ($rows->onFirstPage())
          <span>Previous</span>
      @else
          <a href="{{ $rows->previousPageUrl() }}" class="btn btn-sm">Previous</a>
      @endif

      @if ($rows->hasMorePages())
          <a href="{{ $rows->nextPageUrl() }}" class="btn btn-sm">Next</a>
      @else
          <span>Next</span>
      @endif
   </div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
   $(function(){
      $('.product-view').on('click', function(){
         var viewType         = $(this).attr('data-product-view-type');
         var base_url         = '<?=url('admin//')?>';
         $.ajax({
            type: "POST",
            url: base_url + "/product/update-product-view",
            data: {"_token": "{{ csrf_token() }}", viewType : viewType},
            dataType: "JSON",
            beforeSend: function () {
               
            },
            success: function (res) {
               // console.log(res);
               window.location.reload();
            }
         });
      });
   });
</script>
<script>
   $(document).ready(function () {
      // When the 'checkAll' checkbox is clicked
      $('#checkAll').on('change', function () {
         $('.checkItem').prop('checked', $(this).prop('checked'));
         if ($(this).prop('checked')) {
            $('.action-bar').show();
         } else {
            $('.action-bar').hide();
         }
      });

      // If any 'checkItem' checkbox is unchecked, uncheck 'checkAll'
      $('.checkItem').on('change', function () {
         // if (!$('.checkItem:checked').length) {
         //     $('#checkAll').prop('checked', false);
         //     $('.action-bar').hide();
         // } else if ($('.checkItem:checked').length === $('.checkItem').length) {
         //     $('#checkAll').prop('checked', true);
         //     $('.action-bar').show();
         // }
         if (!$('.checkItem:checked').length) {
             $('#checkAll').prop('checked', false);
             $('.action-bar').hide();
         } else {
             $('#checkAll').prop('checked', true);
             $('.action-bar').show();
         }
      });

      $('#suggestion-section').hide();
      $('#searchText').on('input', function(){
         var baseUrl = '<?=url('/')?>';
         var searchText = $('#searchText').val();
         if(searchText.length > 2){
            var settings = {
              "url": baseUrl + "/api/search-suggestion",
              "method": "POST",
              "timeout": 0,
              "headers": {
                "Key": "13ae7b7d7ba75ac286656a7a274905ca",
                "source": "ANDROID",
                "Authorization": "",
                "Content-Type": "application/json"
              },
              "data": JSON.stringify({
                "search_keyword": searchText
              }),
            };

            $.ajax(settings).done(function (response) {
               // console.log(response);
               $('#suggestion-section').show();
               var html = '';
               if(response.status){
                  $('#suggestion-section').empty();
                  $.each(response.data, function(key, value) {
                     var productLink = value.product_link;
                     html += '<li>\
                                 <div class="row" style="border: 1px solid #00000036;padding: 1px;border-radius: 3px;background-color: #00000012;">\
                                    <div class="col-md-2">\
                                       <a href="' + productLink + '"><img src="' + value.cover_image + '" style="width:30px; height: 30px; border-radius: 50%;"></a>\
                                    </div>\
                                    <div class="col-md-10" style="padding-left: 0px;">\
                                       <a href="' + productLink + '"><span style="font-size: 10px;">' + value.name + '</span></a>\
                                    </div>\
                                 </div>\
                              </li>';
                  });
                  $('#suggestion-section').html(html);
               }
            });
         }
      });
   });
</script>
