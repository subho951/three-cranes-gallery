<?php
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductAttribute;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductVariation;
use App\Models\VariationAttribute;
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
// Helper::pr(Session::all());
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
<style type="text/css">
    .choices__list--multiple .choices__item {
      background: #dfcdaf;
      border-left: 1px solid #dfcdaf;
      border: 1px solid #dfcdaf;
      opacity: 1;
      color: #8B2525;
      font-weight: 600;
   }
   .choices[data-type*=select-multiple] .choices__button, .choices[data-type*=text] .choices__button{
       filter: brightness(1) invert(1)
    }
    .very-small{
      font-size: 10px;
    }
    .badge {
        display: inline-flex;
        align-items: center;
        margin: 2px;
        background-color: #000;
    }
    .badge .remove {
        cursor: pointer;
        margin-left: 5px;
    }
</style>
<div class="pagetitle">
   <h1><?=$page_header?></h1>
   <nav>
      <ol class="breadcrumb">
         <li class="breadcrumb-item"><a href="<?=url('admin/dashboard')?>">Home</a></li>
         <li class="breadcrumb-item active"><a href="<?=url('admin/' . $controllerRoute . '/list/')?>"><?=$module['title']?> List</a></li>
         <li class="breadcrumb-item active"><?=$page_header?></li>
      </ol>
   </nav>
</div>
<!-- End Page Title -->
<section class="section product-list">
   <form method="POST" action="" enctype="multipart/form-data">
      @csrf
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
      <?php
      if($row){
         $GetParentCategory            = Category::select('parent_id', 'category_name')->where('id', '=', $row->main_category)->first();
         $GetChildCategory             = Category::select('category_name')->where('id', '=', $row->sub_category)->first();

         $product_id                   = $row->id;
         $who_made_it                  = $row->who_made_it;
         $what_is_it                   = $row->what_is_it;
         $manufacture_year             = $row->manufacture_year;
         $shop_produce_item            = $row->shop_produce_item;
         $tools_used                   = (($row->tools_used != '')?json_decode($row->tools_used):[]);
         $main_category                = $row->main_category;
         $sub_category                 = $row->sub_category;
         $name                         = $row->name;
         $base_price                   = $row->base_price;
         $price_percentage             = $row->price_percentage;
         $markup_price                 = $row->markup_price;
         $discount_amount              = $row->discount_amount;
         $discounted_price             = $row->discounted_price;
         $cover_image                  = $row->cover_image;
         $short_description            = $row->short_description;
         $long_description             = $row->long_description;
         $is_personalization           = $row->is_personalization;
         $personalization_instruction  = $row->personalization_instruction;
         $product_sku                  = $row->product_sku;
         $product_qty                  = $row->product_qty;
         $product_weight_lb            = $row->product_weight_lb;
         $product_weight_oz            = $row->product_weight_oz;
         $product_length               = $row->product_length;
         $product_width                = $row->product_width;
         $product_height               = $row->product_height;
         $is_feature                   = $row->is_feature;
         $product_video_code           = $row->product_video_code;
         $product_video                = $row->product_video;
         $tags                         = $row->tags;
         $materialData                 = (($row->materials != '')?json_decode($row->materials):[]);
         $shipping_policy_id           = $row->shipping_policy_id;
         $shipping_info                = $row->shipping_info;
         $shipping_type                = $row->shipping_type;
         $shipping_rate                = $row->shipping_rate;
         $return_policy_id             = $row->return_policy_id;
         $meta_title                   = $row->meta_title;
         $meta_description             = $row->meta_description;
         $meta_keywords                = $row->meta_keywords;
      } else {
         $sessionSubCategory           = session('product_session_data')['sub_category'];
         $GetParentCategory            = Category::select('parent_id', 'category_name')->where('id', '=', $sessionSubCategory)->first();
         $GetChildCategory             = Category::select('category_name')->where('id', '=', $sessionSubCategory)->first();

         $product_id                   = 0;
         $who_made_it                  = '';
         $what_is_it                   = '';
         $manufacture_year             = '';
         $shop_produce_item            = '';
         $tools_used                   = [];
         $main_category                = (( $GetParentCategory)? $GetParentCategory->parent_id:0);
         $sub_category                 = $sessionSubCategory;
         $name                         = '';
         $base_price                   = '';
         $price_percentage             = 0;
         $markup_price                 = '';
         $discount_amount              = 0;
         $discounted_price             = '';
         $cover_image                  = '';
         $short_description            = '';
         $long_description             = '';
         $is_personalization           = 0;
         $personalization_instruction  = '';
         $product_sku                  = '';
         $product_qty                  = '';
         $product_weight_lb            = 0;
         $product_weight_oz            = 0;
         $product_length               = 0;
         $product_width                = 0;
         $product_height               = 0;
         $is_feature                   = 0;
         $product_video_code           = '';
         $product_video                = '';
         $tags                         = '';
         $materialData                 = [];
         $shipping_policy_id           = '';
         $shipping_info                = '';
         $shipping_type                = '';
         $shipping_rate                = '';
         $return_policy_id             = '';
         $meta_title                   = '';
         $meta_description             = '';
         $meta_keywords                = '';
      }
      // echo $main_category.' || '.$sub_category;
      $attrs = Attribute::select('id', 'name')->where('status', '=', 1)->where('parent_category', '=', $main_category)->where('sub_category_id', '=', $sub_category)->get();
      ?>
      <div class="row">
         <div class="col-lg-12">
            <ul id="menu" class="top-listing-nav mb-2">
               <li class="active">
                  <a href="#about-new">About</a>
               </li>
               <li >
                  <a href="#Price-new">Price & Inventory </a>
               </li>
               <li>
                  <a href="#variations-new">Variations </a>
               </li>
               <li>
                  <a href="#details-new">Details  </a>
               </li>
               <li>
                  <a href="#shipping-new">Shipping  </a>
               </li>
               <li>
                  <a href="#settings-new"><i class="fa fa-cog" aria-hidden="true"></i> Settings  </a>
               </li>
            </ul>
         </div>
      </div>
      <div class="card about-new" id="about-new">
         <div class="card-body ">
            <div class="row">
               <div class="col-lg-12 col-md-12 mb-3">
                  <h2 class="card-title">About </h2>
                  <p >Tell the world all about your item and why they’ll love it.</p>
               </div>
               <div class="col-lg-12  col-md-12 mb-3">
                  <h5 class="sub-title pt-2">Title</h5>
                  <p class="mb-3">Include keywords that buyers would use to search for this item.</p>
                  <input type="text" name="name" class="form-control" id="name" value="<?=$name?>" required>
               </div>
               <div class="col-lg-12 col-md-12 mb-3">
                  <?php
                  $productImages = ProductImage::where('product_id', '=', $product_id)->where('status', '=', 1)->get();
                  if(count($productImages) <= 9){
                  ?>
                     <div class="upload">
                        <fieldset class="upload_dropZone text-center mb-3 p-4">
                           <p class="small my-2">Drag &amp; Drop <i>or</i></p>
                           <input id="upload_image_background" data-post-name="image_background"  class="position-absolute invisible" type="file" multiple accept="image/apng, image/avif, image/gif, image/jpeg, image/png, image/svg+xml, image/webp" name="product_image[]"/>
                           <label class="btn btn-upload mb-3 mt-2" for="upload_image_background">+ Add up to 10 photos</label>
                           <div class="upload_gallery d-flex flex-wrap justify-content-center gap-3 mb-0"></div>
                        </fieldset>
                     </div>
                  <?php }?>
                  <p class="text-primary mb-3"><?=(10 - count($productImages))?> images left to upload</p>
                  <div class="row align-items-center mb-3">
                     <?php if($productImages){ foreach($productImages as $productImage){?>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                           <div class="card">
                              <div class="card-body">
                                 <img class="img-thumbnail" src="<?=env('UPLOADS_URL') . '/product/' . $productImage->image?>" style="height: 341px;width: 100%; object-fit: contain;">
                              </div>
                              <div class="card-footer text-center">
                                 <a href="<?=url('admin/' . $controllerRoute . '/delete-single-image/'.Helper::encoded($productImage->id).'/'.Helper::encoded($product_id))?>" class="btn btn-danger btn-sm" onclick="return confirm('Do You Want To Delete This Product Image ?');"><i class="fa fa-trash"></i> Delete</a>
                                 <p style="margin-top: 10px">
                                    <input type="radio" name="is_cover_image" value="<?=$productImage->id?>" id="image<?=$productImage->id?>" <?=(($productImage->is_cover_image)?'checked':'')?>>
                                    <label for="image<?=$productImage->id?>"><small>Make This Cover Image</small></label>
                                 </p>
                              </div>
                           </div>
                        </div>
                     <?php } }?>
                  </div>
               </div>
               <div class="col-lg-6 col-md-6 mb-3">
                  <h5 class="sub-title">Product Video</h5>
                  <input type="text" name="product_video" class="form-control" id="product_video" value="<?=$product_video?>">
               </div>
               <div class="col-lg-6 col-md-6 mb-3">
                  <?php if($product_video != ''){?>
                     <p class="mt-3">
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/<?=$product_video_code?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                     </p>
                  <?php }?>
               </div>
               <div class="col-lg-12 col-md-12 mb-3">
                  <h5 class="sub-title">Short Description</h5>
                  <p class="mb-3">What makes your item special? Buyers will only see the first few lines unless they expand the description.</p>
                  <textarea class="form-control" id="ckeditor11" name="short_description"><?=$short_description?></textarea>
               </div>
               <div class="col-lg-12 col-md-12 mb-3">
                  <h5 class="sub-title">Long Description</h5>
                  <p class="mb-3">What makes your item special? Buyers will only see the first few lines unless they expand the description.</p>
                  <textarea class="form-control" id="ckeditor1" name="long_description"><?=$long_description?></textarea>
               </div>
            </div>
            <div class="row align-items-center mb-3">
               <div class="col-lg-9 col-md-8 col-sm-12">
                  <h5 class="sub-title">Personalization</h5>
                  <p>Collect personalized information for this listing.</p>
               </div>
               <div class="col-lg-3 col-md-4 col-sm-12">
                  <a class="new-btn-style" href="javascript:void(0);" id="add-personalization" <?=(($is_personalization)?'style="display: none;"':'')?>>+ Add personlization</a>
                  <a class="new-btn-style" href="javascript:void(0);" id="remove-personalization" <?=(($is_personalization)?'':'style="display: none;"')?>>- Remove personlization</a>
               </div>
               <div class="col-lg-12 col-md-12 mt-3 mb-3" id="personalization" <?=(($is_personalization)?'':'style="display: none;"')?>>
                  <h5 class="sub-title">Instructions for buyers</h5>
                  <p class="mb-3">Enter the personalization instructions you want buyers to see.</p>
                  <textarea class="form-control" id="ckeditor2" name="personalization_instruction"><?=$personalization_instruction?></textarea>
               </div>
            </div>
         </div>
      </div>
      <div class="card Price-new" id="Price-new">
         <div class="card-body ">
            <div class="row">
               <div class="col-lg-12  col-md-12 col-sm-12 mb-3">
                  <h2 class="card-title">
                     Price & Inventory 
                  </h2>
                  <p class="mb-3">Set a price for your item and indicate how many are available for sale.</p>
                  <div class="row">
                     <div class="col-lg-3 mb-3">
                        <label for="exampleInputEmail1" class="form-label">Price<span style="color:red;">*</span></label>
                        <input type="text" class="form-control" name="base_price" id="base_price" placeholder="$" value="<?=$base_price?>" required>
                     </div>
                     <div class="col-lg-3 mb-3">
                        <label for="exampleInputEmail1" class="form-label">Discount Type<span style="color:red;">*</span></label>
                        <select class="form-control" name="price_percentage" id="price_percentage" required>
                           <option value="" selected>Select Discount Type</option>
                           <option value="PERCENTAGE" <?=(($price_percentage == 'PERCENTAGE')?'selected':'')?>>PERCENTAGE</option>
                           <option value="FLAT" <?=(($price_percentage == 'FLAT')?'selected':'')?>>FLAT</option>
                        </select>
                     </div>
                     <div class="col-lg-3 mb-3">
                        <label for="exampleInputEmail1" class="form-label">Discount Amount<span style="color:red;">*</span></label>
                        <input type="text" class="form-control" name="discount_amount" id="discount_amount" placeholder="%" value="<?=$discount_amount?>" required>
                     </div>
                     <div class="col-lg-3 mb-3">
                        <label for="exampleInputEmail1" class="form-label">Discounted Price<span style="color:red;">*</span></label>
                        <input type="text" class="form-control" name="discounted_price" id="discounted_price" placeholder="$" value="<?=$discounted_price?>" required>
                     </div>
                  </div>
               </div>
            </div>
            <!-- <div class="row align-items-center mb-3">
               <div class="col-lg-10  col-md-9 col-sm-12">
                  <h5 class="sub-title">
                     Let buyers make offers on this listing
                  </h5>
                  <p class="mb-3">Getting offers from buyers can help you learn where the pricing “sweet spot” is to attract shoppers and still protect your bottom line.</p>
                  <p>
                     You’ll receive offers for up to 40% off<i class="fa fa-question-circle px-1" aria-hidden="true"></i>
                  </p>
               </div>
               <div class="col-lg-2  col-md-3 col-sm-12">
                  <div class="form-check form-switch form-switch-lg">
                     <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-3  col-md-12 col-sm-12 mb-3">
                  <label for="exampleInputEmail1" class="form-label">Quantity<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" >
               </div>
            </div> -->
            <div class="row" id="sku-add" <?=(($product_sku != '')?'style="display: none;"':'')?>>
               <div class="col-lg-12 col-md-12 col-sm-12 mt-2 ">
                  <a class="new-btn-style add-sku bg-new-btn" href="javascript:void(0);" id="add-sku">+Add SKU</a>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-3 mb-3" id="sku-row" <?=(($product_sku != '')?'':'style="display: none;"')?>>
                  <label for="product_sku" class="form-label">SKU<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" name="product_sku" id="product_sku" placeholder="SKU" value="<?=$product_sku?>" required>
               </div>
               <div class="col-lg-3 mb-3" id="qty-row" <?=(($product_sku != '')?'':'style="display: none;"')?>>
                  <label for="product_qty" class="form-label">Qty<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" name="product_qty" id="product_qty" placeholder="Qty" value="<?=$product_qty?>" onkeypress="return isNumber(event)" required>
               </div>
            </div>
         </div>
      </div>
      <?php
      $getAttributeCount = Attribute::where('status', '=', 1)->where('sub_category_id', '=', $sub_category)->count();
      if($getAttributeCount > 0){
      ?>
         <div class="card variations-new" id="variations-new">
            <div class="card-body">
               <div class="row">
                  <div class="col-lg-8 col-md-8 col-sm-8 mb-3">
                     <h2 class="card-title ">
                        Variations 
                     </h2>
                     <p>Choose attributes for variation first.</p>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-4 mb-3">
                     <a class="new-btn-style" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#variationModal">Manage Variation</a>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 mb-3" id="variationTable">
                     
                  </div>
               </div>
            </div>
         </div>
      <?php }?>
      <div class="card details-new" id="details-new">
         <div class="card-body row">
            <div class="row">
               <div class="col-lg-12  col-md-12 col-sm-12 mb-3">
                  <h2 class="card-title ">
                     Details 
                  </h2>
                  <p>Share a few more specifics about your item to make it easier to find in search, and to help buyers know what to expect.</p>
               </div>
            </div>
            <!-- <div class="row">
               <div class="col-lg-12 mb-3">
                  <label for="exampleInputEmail1" class="form-label">Core details<span style="color:red;">*</span></label>
                  <div class="core-product-list border rounded ">
                     <div class="row align-items-center ">
                        <div class="col-lg-9  col-md-8 col-sm-12">
                           <div class="core-product-items-block ">
                              <div class="core-product-image">
                                 <img src="https://threecranesgallery.itiffyconsultants.com/public/uploads/1726130627logo.jpg" alt="ThreeCranesGallery" style="width: 100%; height:100px;">
                              </div>
                              <div class="core-product-description">
                                 <h5 class="sub-title">
                                    description
                                 </h5>
                                 <p class="mb-3">Include keywords that buyers would use to search for this item.</p>
                                 <p>Include keywords that buyers would use to search for this item.</p>
                                 <p>Include keywords that buyers would use to search for this item.</p>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-3  col-md-4 col-sm-12">
                           <a class="new-btn-style bg-new-btn" href="">Change</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div> -->
            <div class="row">
               <div class="col-lg-12  col-md-12 col-sm-12 mb-3">
                  <h5 class="sub-title">Parent Category<span style="color:red;">*</span></h5>
                  <div id="search-wrapper" class="border rounded-4 category-search mb-5">
                     <!-- <i class="search-icon fas fa-search"></i>
                     <input type="text" id="search" placeholder="Search for a category, e.g. Hats, Rings, Pillows, etc."> -->
                     <?=(($GetParentCategory)?$GetParentCategory->category_name:'')?>
                  </div>
                  <hr class="mb-3">
                  <h5 class="sub-title">Child Category<span style="color:red;">*</span></h5>
                  <div id="search-wrapper" class="border rounded-4 category-search mb-5">
                     <?//=(($GetChildCategory)?$GetChildCategory->category_name:'')?>
                     <select class="form-select" aria-label="Default select example" id="sub_category" name="sub_category">
                        <option value="" selected>Sub Categories</option>
                        <?php if($subcategories){ foreach($subcategories as $subcategory){?>
                           <?php
                           $getParentCategory = Category::select('id', 'category_name')->where('status', '=', 1)->where('id', '=', $subcategory->parent_id)->first();
                           ?>
                           <option value="<?=$subcategory->id?>" <?=(($subcategory->id == $sub_category)?'selected':'')?>><?=(($getParentCategory)?$getParentCategory->category_name:'')?> - <?=$subcategory->category_name?></option>
                        <?php } }?>
                     </select>
                  </div>
                  <hr class="mb-3">
               </div>
               <div class="col-lg-12  col-md-12 col-sm-12 mb-3">
                  <h5 class="sub-title ">
                     Attributes 
                     <!-- <i class="fa fa-question-circle px-1" aria-hidden="true"></i>  -->
                  </h5>
                  <?php if($attrs){ foreach($attrs as $attr){?>
                     <p style="font-weight: bold;"><u><?=$attr->name?></u></p>
                     <input type="hidden" name="product_attribute_id[]" value="<?=$attr->id?>">
                     <?php
                     $attrVals = AttributeValue::select('id', 'attr_value', 'attr_id')->where('status', '=', 1)->where('attr_id', '=', $attr->id)->get();
                     ?>
                     <div class="row">
                        <?php if($attrVals){ foreach($attrVals as $attrVal){?>
                           <div class="col-lg-3 col-md-4 col-sm-12">
                              <div class="form-check form-switch form-switch-lg d-flex w-100 align-items-center justify-content-between">
                                 <?php
                                 $checkAttributeAdded = ProductAttribute::where('product_id', '=', $product_id)->where('product_attribute_id', '=', $attr->id)->where('product_attribute_value_id', '=', $attrVal->id)->count();
                                 ?>
                                 <?=$attrVal->attr_value?>
                                 <input class="form-check-input" type="checkbox" role="switch" id="product_attribute_value_id" name="product_attribute_value_id<?=$attrVal->attr_id?>[]" value="<?=$attrVal->id?>" <?=(($checkAttributeAdded > 0)?'checked':'')?>>
                                 
                              </div>
                           </div>
                        <?php } }?>
                     </div>
                     <hr class="mb-3">
                  <?php } }?>
               </div>
               <div class="col-lg-12  col-md-12 col-sm-12 mb-3">
                  <h5 class="sub-title">
                     Tags  
                  </h5>
                  <p >Add up to 13 tags to help people search for your listings.</p>
               </div>
            </div>
            <div class="row align-items-center mb-3">
               <div class="col-lg-9 col-md-8 col-sm-12">
                  <input type="text" id="input-tags" class="form-control" placeholder="Shape, color, style, function, etc.">
                  <textarea class="form-control" name="tags" id="options" style="display:none;"><?=$tags?></textarea>
                  <small class="text-primary d-block my-2">Enter options with comma separated</small>
                  <div id="badge-container">
                     <?php
                     if($tags != ''){
                        $deal_keywords = explode(",", $tags);
                        if(!empty($deal_keywords)){
                        for($k=0;$k<count($deal_keywords);$k++){
                     ?>
                        <span class="badge"><?=$deal_keywords[$k]?> <span class="remove" data-tag="<?=$deal_keywords[$k]?>">&times;</span></span>
                     <?php } }
                     }
                     ?>
                  </div>
               </div>
               <!-- <div class="col-lg-3  col-md-4 col-sm-12">
                  <a class="add-btn" href="">Add</a>
               </div> -->
            </div>
            <div class="row">
               <!-- <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                  <p >13 left</p>
               </div> -->
               <div class="col-lg-12 mb-3 col-md-12 col-sm-12">
                  <h5 class="sub-title">Materials</h5>
                  <p >Buyers value transparency—tell them what’s used to make your item.</p>
               </div>
            </div>
            <div class="row align-items-center mb-3">
               <div class="col-lg-9 col-md-8 col-sm-12">
                  <select class="form-select" aria-label="Default select example" id="materials" name="materials[]" multiple>
                     <?php if($materials){ foreach($materials as $material){?>
                        <option value="<?=$material->id?>" <?=((in_array($material->id, $materialData))?'selected':'')?>><?=$material->name?></option>
                     <?php } }?>
                  </select>
               </div>
               <!-- <div class="col-lg-3  col-md-4 col-sm-12">
                  <a class="add-btn" href="">Add</a>
               </div> -->
            </div>
            <!-- <div class="row">
               <div class="col-lg-12  col-md-12 col-sm-12 mb-3">
                  <p>13 left</p>
               </div>
            </div> -->
         </div>
      </div>
      <div class="card shipping-new" id="shipping-new">
         <div class="card-body ">
            <div class="row">
               <div class="col-lg-12  col-md-12 col-sm-12 mb-3">
                  <h2 class="card-title">
                     Shipping 
                  </h2>
                  <p>Give shoppers clear expectations about delivery time and cost by making sure your shipping info is accurate, including the shipping profile and your
                     order processing schedule. You can make updates any time in <a href="" style="color: #444444;text-decoration:underline;">Shipping settings.</a>
                  </p>
               </div>
               <div class="col-lg-12  col-md-12 col-sm-12 mt-4">
                  <div class="Shipping-option border rounded mb-3 ">
                     <div class="row align-items-center justify-content-between">
                        <div class="col-lg-6  col-md-4 col-sm-12">
                           <h5 class="sub-title m-0 mb-3">
                              Shipping option<span style="color:red;">*</span>  
                           </h5>
                        </div>
                        <!-- <div class="col-lg-3  col-md-4 col-sm-12">
                           <a class="add-btn" href="">+ Create option</a>
                        </div>
                        <div class="col-lg-3  col-md-4 col-sm-12">
                           <a class="new-btn-style" href="">Select profile</a>
                        </div> -->
                        <div class="col-lg-12 mb-3  col-md-12 col-sm-12">
                           <div class="return-option border rounded mb-2" style="padding: 5px;">
                              <div class="row align-items-center justify-content-between">
                                 <div class="col-lg-9 col-md-8 col-sm-12">
                                    <h5 class="sub-title mb-0 mt-1">
                                       <input type="radio" name="shipping_type" id="shipping_type1" value="FREE" <?=(($shipping_type == 'FREE')?'checked':'')?> required>
                                       <label for="shipping_type1">FREE Shipping</label>
                                    </h5>
                                 </div>
                              </div>
                           </div>
                           <div class="return-option border rounded mb-2" style="padding: 5px;">
                              <div class="row align-items-center justify-content-between">
                                 <div class="col-lg-9 col-md-8 col-sm-12">
                                    <h5 class="sub-title mb-0 mt-1">
                                       <input type="radio" name="shipping_type" id="shipping_type2" value="FIXED" <?=(($shipping_type == 'FIXED')?'checked':'')?> required>
                                       <label for="shipping_type2">FIXED Shipping</label>
                                    </h5>
                                 </div>
                              </div>
                           </div>
                           <div class="return-option border rounded mb-2" style="padding: 5px;">
                              <div class="row align-items-center justify-content-between">
                                 <div class="col-lg-9 col-md-8 col-sm-12">
                                    <h5 class="sub-title mb-0 mt-1">
                                       <input type="radio" name="shipping_type" id="shipping_type3" value="USPS" <?=(($shipping_type == 'USPS')?'checked':'')?> required>
                                       <label for="shipping_type3">USPS Shipping</label>
                                    </h5>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-lg-12  col-md-12 col-sm-12 mt-4 mb-4">
                  <h5 class="sub-title">Shipping Info</h5>
                  <textarea class="form-control" id="ckeditor3" name="shipping_info"><?=$shipping_info?></textarea>
               </div>
            </div>
            <div class="row align-items-center mb-3">
               <div class="col-lg-8 col-md-8 col-sm-12">
                  <h5 class="sub-title">Item weight and size</h5>
               </div>
               <div class="col-lg-4 col-md-4 col-sm-12">
                  <a class="new-btn-style" href="javascript:void(0);" id="add-weight-size" <?=(($product_weight_lb != '' || $product_weight_oz != '' || $product_length != '' || $product_width != '' || $product_height != '')?'style="display:none;"':'')?>><i class="fa fa-plus"></i> Add item weight and size</a>
                  <a class="new-btn-style" href="javascript:void(0);" id="remove-weight-size" <?=(($product_weight_lb != '' || $product_weight_oz != '' || $product_length != '' || $product_width != '' || $product_height != '')?'':'style="display:none;"')?>><i class="fa fa-trash"></i> Remove item weight and size</a>
               </div>
            </div>
            <div class="row align-items-center mb-3 weight-size" <?=(($product_weight_lb != '' || $product_weight_oz != '' || $product_length != '' || $product_width != '' || $product_height != '')?'':'style="display:none;"')?>>
               <div class="col-lg-12 col-md-12 col-sm-12">
                  <h5 class="sub-title">Item weight <small>(optional)</small></h5>
               </div>
               <div class="col-lg-2 col-md-2 col-sm-12">
                  <input type="text" name="product_weight_lb" class="form-control" id="product_weight_lb" placeholder="lb" value="<?=$product_weight_lb?>">
               </div>
               <div class="col-lg-2 col-md-2 col-sm-12">
                  <input type="text" name="product_weight_oz" class="form-control" id="product_weight_oz" placeholder="oz" value="<?=$product_weight_oz?>">
               </div>
            </div>
            <div class="row align-items-center mb-3 weight-size" <?=(($product_weight_lb != '' || $product_weight_oz != '' || $product_length != '' || $product_width != '' || $product_height != '')?'':'style="display:none;"')?>>
               <div class="col-lg-12 col-md-12 col-sm-12">
                  <h5 class="sub-title">Item size (when packed) <small>(optional)</small></h5>
                  <small>Enter the size of the item after it’s been prepared for packaging but not yet packaged (for example: folded, but not boxed)</small>
               </div>
               <div class="col-lg-2 col-md-2 col-sm-12">
                  <h5 class="sub-title">Length</h5>
                  <input type="text" name="product_length" class="form-control" id="product_length" placeholder="in" value="<?=$product_length?>">
               </div>
               <div class="col-lg-2 col-md-2 col-sm-12">
                  <h5 class="sub-title">Width</h5>
                  <input type="text" name="product_width" class="form-control" id="product_width" placeholder="in" value="<?=$product_width?>">
               </div>
               <div class="col-lg-2 col-md-2 col-sm-12">
                  <h5 class="sub-title">Height</h5>
                  <input type="text" name="product_height" class="form-control" id="product_height" placeholder="in" value="<?=$product_height?>">
               </div>
            </div>
         </div>
      </div>
      <div class="card settings-new" id="settings-new">
         <div class="card-body ">
            <div class="row">
               <div class="col-lg-12  col-md-12 col-sm-12 mb-3">
                  <h2 class="card-title">
                     Settings 
                  </h2>
                  <p>Choose how this listing will display in your shop, how it will renew, and if you want it to be promoted in Etsy Ads.</p>
               </div>
               <div class="col-lg-12 mb-3  col-md-12 col-sm-12">
                  <h5 class="sub-title pt-2">
                     Returns and exchanges
                     <!-- &nbsp;<u style="font-weight:300;font-size:14px;">Pre field</u>-->
                  </h5>
                  <?php if($returnPolicies){ foreach($returnPolicies as $returnPolicy){?>
                     <div class="return-option border rounded mb-2">
                        <div class="row align-items-center justify-content-between">
                           <div class="col-lg-9 col-md-8 col-sm-12">
                              <h5 class="sub-title mb-2">
                                 <input type="radio" name="return_policy_id" value="<?=$returnPolicy->id?>" <?=(($return_policy_id == $returnPolicy->id)?'checked':'')?>>
                                 <?=$returnPolicy->name?> <i class="fa-solid fa-calendar-days"></i> <?=$returnPolicy->timeframe?> days
                              </h5>
                              <p><?=$returnPolicy->description?>
                              </p>
                           </div>
                           <!-- <div class="col-lg-3  col-md-4 col-sm-12">
                              <a class="new-btn-style bg-new-btn" href="javascript:void(0);">Change policy</a>
                           </div> -->
                        </div>
                     </div>
                  <?php } }?>
               </div>
            </div>
            <div class="row align-items-center mb-4">
               <div class="col-lg-10  col-md-9 col-sm-12">
                  <h5 class="sub-title">
                     Feature this listing
                  </h5>
                  <p >Showcase this listing at the top of your shop's homepage to make it stand out.</p>
               </div>
               <div class="col-lg-2 col-md-4 col-sm-12">
                  <div class="form-check form-switch form-switch-lg">
                     <input class="form-check-input" type="checkbox" role="switch" id="is_feature" name="is_feature" <?=(($is_feature)?'checked':'')?>>
                  </div>
               </div>
            </div>
            <div class="row align-items-center mb-4">
               <div class="col-lg-12 col-md-12 col-sm-12 mt-4 mb-4">
                  <h5 class="sub-title">Meta Title</h5>
                  <textarea class="form-control" id="ckeditor4" name="meta_title"><?=$meta_title?></textarea>
               </div>
               <div class="col-lg-12 col-md-12 col-sm-12 mt-4 mb-4">
                  <h5 class="sub-title">Meta Description</h5>
                  <textarea class="form-control" id="ckeditor5" name="meta_description"><?=$meta_description?></textarea>
               </div>
               <div class="col-lg-12 col-md-12 col-sm-12 mt-4 mb-4">
                  <h5 class="sub-title">Meta Keywords</h5>
                  <textarea class="form-control" id="ckeditor6" name="meta_keywords"><?=$meta_keywords?></textarea>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
            <ul class="footer-btn-group">
               <!-- <li><a href="">Preview </a></li> -->
               <li><button type="submit" class="bg btn btn-outline-secondary" name="mode" value="save as draft">Save As Draft </button></li>
               <li><button type="submit" class="bg btn btn-outline-success" name="mode" value="Publish">Save & Publish </button></li>
            </ul>
         </div>
      </div>
   </form>
</section>

<!-- Modal -->
<div class="modal fade" id="variationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">What type of variation is it?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <?php
            $dropdownIds   = [];
            $attrIds       = [];
            if($attrs){ foreach($attrs as $attr){
            ?>
               <div class="col-lg-12 col-md-12">
                  <label for="attrId<?=$attr->id?>"><?=$attr->name?></label>
                  <select class="form-control" id="variation" name="variation<?=$attr->id?>" multiple>
                     <?php
                     $attrVals = AttributeValue::select('id', 'attr_value')->where('status', '=', 1)->where('parent_category', '=', $main_category)->where('sub_category_id', '=', $sub_category)->where('attr_id', '=', $attr->id)->get();
                     if($attrVals){ foreach($attrVals as $attrVal){
                     ?>
                        <option value="<?=$attr->id?>/<?=$attrVal->id?>"><?=$attrVal->attr_value?></option>
                     <?php } }?>
                  </select>
               </div>
               <?php $dropdownIds[] = 'variation'.$attr->id;?>
               <?php $attrIds[]     = $attr->id;?>
            <?php } }?>
            <!-- Textbox and Button -->
            <input type="hidden" id="dropdownIds" value="<?=implode(',', $dropdownIds)?>">
            <input type="hidden" id="attrIds" value="<?=implode(',', $attrIds)?>">
         </div>
         <div class="modal-footer">
            <button type="button" class="new-btn-style" id="getValuesButton">Generate Variation</button>
         </div>
      </div>
   </div>
</div>
<script>
   $('#variationModal').modal({
      backdrop: 'static',
      keyboard: false
   })
</script>
<script>
   console.clear();
    ('use strict');
     (function () {
    
    'use strict';
    const preventDefaults = event => {
      event.preventDefault();
      event.stopPropagation();
    };
    
    const highlight = event =>
      event.target.classList.add('highlight');
    
    const unhighlight = event =>
      event.target.classList.remove('highlight');
    
    const getInputAndGalleryRefs = element => {
      const zone = element.closest('.upload_dropZone') || false;
      const gallery = zone.querySelector('.upload_gallery') || false;
      const input = zone.querySelector('input[type="file"]') || false;
      return {input: input, gallery: gallery};
    }
    
    const handleDrop = event => {
      const dataRefs = getInputAndGalleryRefs(event.target);
      dataRefs.files = event.dataTransfer.files;
      handleFiles(dataRefs);
    }
    
    
    const eventHandlers = zone => {
    
      const dataRefs = getInputAndGalleryRefs(zone);
      if (!dataRefs.input) return;
    
      // Prevent default drag behaviors
      ;['dragenter', 'dragover', 'dragleave', 'drop'].forEach(event => {
        zone.addEventListener(event, preventDefaults, false);
        document.body.addEventListener(event, preventDefaults, false);
      });
    
      // Highlighting drop area when item is dragged over it
      ;['dragenter', 'dragover'].forEach(event => {
        zone.addEventListener(event, highlight, false);
      });
      ;['dragleave', 'drop'].forEach(event => {
        zone.addEventListener(event, unhighlight, false);
      });
    
      // Handle dropped files
      zone.addEventListener('drop', handleDrop, false);
    
      // Handle browse selected files
      dataRefs.input.addEventListener('change', event => {
        dataRefs.files = event.target.files;
        handleFiles(dataRefs);
      }, false);
    
    }
    
    
    // Initialise ALL dropzones
    const dropZones = document.querySelectorAll('.upload_dropZone');
    for (const zone of dropZones) {
      eventHandlers(zone);
    }
    
    
    // No 'image/gif' or PDF or webp allowed here, but it's up to your use case.
    // Double checks the input "accept" attribute
    const isImageFile = file => 
      ['image/jpeg', 'image/png', 'image/svg+xml', 'image/avif', 'image/gif', 'image/webp'].includes(file.type);
    
    function previewFiles(dataRefs) {
      if (!dataRefs.gallery) return;
      for (const file of dataRefs.files) {
        let reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onloadend = function() {
          let img = document.createElement('img');
          img.className = 'upload_img mt-2';
          img.setAttribute('alt', file.name);
          img.src = reader.result;
          dataRefs.gallery.appendChild(img);
        }
      }
    }
    
    const imageUpload = dataRefs => {
    
      // Multiple source routes, so double check validity
      if (!dataRefs.files || !dataRefs.input) return;
    
      const url = dataRefs.input.getAttribute('data-post-url');
      if (!url) return;
    
      const name = dataRefs.input.getAttribute('data-post-name');
      if (!name) return;
    
      const formData = new FormData();
      formData.append(name, dataRefs.files);
    
      fetch(url, {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        console.log('posted: ', data);
        if (data.success === true) {
          previewFiles(dataRefs);
        } else {
          console.log('URL: ', url, '  name: ', name)
        }
      })
      .catch(error => {
        console.error('errored: ', error);
      });
    }
    
    
    // Handle both selected and dropped files
    const handleFiles = dataRefs => {
    
      let files = [...dataRefs.files];
    
      // Remove unaccepted file types
      files = files.filter(item => {
        if (!isImageFile(item)) {
          console.log('Not an image, ', item.type);
        }
        return isImageFile(item) ? item : null;
      });
    
      if (!files.length) return;
      dataRefs.files = files;
    
      previewFiles(dataRefs);
      imageUpload(dataRefs);
    }
    
    })();
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    var tagsArray = [];
    var beforeData = $('#options').val();
    if(beforeData.length > 0){
      tagsArray = beforeData.split(',');
    }
    $('#input-tags').on('input', function() {
        var input = $(this).val();
        if (input.includes(',')) {
            var tags = input.split(',');
            tags.forEach(function(tag) {
                tag = tag.trim();
                if (tag.length > 0 && !tagsArray.includes(tag)) {
                    tagsArray.push(tag);
                    $('#badge-container').append(
                        '<span class="badge">' + tag + ' <span class="remove" data-tag="' + tag + '">&times;</span></span>'
                    );
                }
            });
            $('#options').val(tagsArray);
            // console.log(tagsArray);
            $(this).val('');
        }
    });
    // console.log(tagsArray);
    $(document).on('click', '.remove', function() {
        var tag = $(this).data('tag');
        tagsArray = tagsArray.filter(function(item) {
            return item !== tag;
        });
        $(this).parent().remove();
        $('#keywords').val(tagsArray);
        // console.log(tagsArray);
    });
  });
</script>
<script type="text/javascript">
   $(document).ready(function() {
      var base_url         = '<?=url('admin//')?>';
      var dropdownValues   = '<?=$dropdownValues?>';
      var attrIds          = '<?=$attr_ids?>';
      var base_price       = $('#base_price').val();
      var price_percentage = $('#price_percentage').val();
      var discount_amount  = $('#discount_amount').val();
      var discounted_price = $('#discounted_price').val();
      var product_sku      = $('#product_sku').val();
      var product_qty      = $('#product_qty').val();
      var product_id       = '<?=$product_id?>';
      $.ajax({
         type: "POST",
         url: base_url + "/product/generate-product-variation-2",
         data: {dropdownValues : dropdownValues, "_token": "{{ csrf_token() }}", attrIds : attrIds, base_price : base_price, price_percentage : price_percentage, discount_amount : discount_amount, discounted_price : discounted_price, product_sku : product_sku, product_qty : product_qty, product_id : product_id},
         dataType: "html",
         beforeSend: function () {
            
         },
         success: function (response) {
            $('#variationTable').empty();
            $('#variationTable').html(response);
            $('#variationModal').modal('hide');
         }
      });

      $('#getValuesButton').click(function() {
         // Get the comma-separated IDs from the textbox
         var input = $('#dropdownIds').val();
         var attrIds = $('#attrIds').val();
         var base_price = $('#base_price').val();
         var price_percentage = $('#price_percentage').val();
         var discount_amount = $('#discount_amount').val();
         var discounted_price = $('#discounted_price').val();
         var product_sku = $('#product_sku').val();
         var product_qty = $('#product_qty').val();
         var product_id       = '<?=$product_id?>';
         
         // Split the IDs into an array
         var ids = input.split(',').map(function(id) {
               return id.trim(); // Remove any leading/trailing spaces
         });

         // Create an array to store the results
         var dropdownValues = {};

         // Iterate through the IDs and get the selected values
         ids.forEach(function(id) {
               var dropdown = $('select[name="'+id+'"]'); // Get the dropdown by ID
               if (dropdown.length) {
                  var selectedValues = dropdown.val(); // Get selected values
                  // console.log(`Dropdown ID: ${id}, Selected Values: ${selectedValues ? selectedValues.join(', ') : 'None'}`);
                  dropdownValues[id] = selectedValues ? selectedValues : []; // Store values, empty array if none selected
               } else {
                  // console.log(`Dropdown ID: ${id} not found.`);
                  dropdownValues[id] = ''; // If dropdown not found, set key to null
               }
         });
         // Output the array
         // console.log('Dropdown Values:', dropdownValues);
         var base_url    = '<?=url('admin//')?>';
         $.ajax({
            type: "POST",
            url: base_url + "/product/generate-product-variation",
            data: {dropdownValues : dropdownValues, "_token": "{{ csrf_token() }}", attrIds : attrIds, base_price : base_price, price_percentage : price_percentage, discount_amount : discount_amount, discounted_price : discounted_price, product_sku : product_sku, product_qty : product_qty, product_id : product_id},
            dataType: "html",
            beforeSend: function () {
               
            },
            success: function (response) {
               $('#variationTable').empty();
               $('#variationTable').html(response);
               $('#variationModal').modal('hide');
            }
         });
      });
   });
   $(document).ready(function(){
      var multipleCancelButton = new Choices('#materials', {
         removeItemButton: true,
         maxItemCount:30,
         searchResultLimit:30,
         renderChoiceLimit:30
      });
      var multipleCancelButton = new Choices('#variation', {
         removeItemButton: true,
         maxItemCount:30,
         searchResultLimit:30,
         renderChoiceLimit:30
      });
   });
   $(function(){
      $('#add-personalization').on('click', function(){
         $(this).hide();
         $('#remove-personalization').show();
         $('#personalization').show();
      });
      $('#remove-personalization').on('click', function(){
         $(this).hide();
         $('#add-personalization').show();
         $('#personalization').hide();
      });
      $('#add-sku').on('click', function(){
         $(this).hide();
         $('#sku-add').hide();
         $('#sku-row').show();
         $('#qty-row').show();
      });
      $('#add-weight-size').on('click', function(){
         $(this).hide();
         $('#remove-weight-size').show();
         $('.weight-size').show();
      });
      $('#remove-weight-size').on('click', function(){
         $(this).hide();
         $('#add-weight-size').show();
         $('.weight-size').hide();
      });

      $('#price_percentage').on('change', function(){
         var base_price       = parseFloat($('#base_price').val());
         var price_percentage = $('#price_percentage').val();
         var discount_amount  = parseFloat($('#discount_amount').val());
         if(price_percentage == 'PERCENTAGE'){
            var discounted_price     = (base_price - ((base_price * discount_amount) / 100));
         } else {
            var discounted_price     = (base_price - discount_amount);
         }
         $('#discounted_price').val(discounted_price);
      });
      $('#discount_amount, #base_price').on('input', function(){
         var base_price       = parseFloat($('#base_price').val());
         var price_percentage = $('#price_percentage').val();
         var discount_amount  = parseFloat($('#discount_amount').val());
         if(price_percentage == 'PERCENTAGE'){
            var discounted_price     = (base_price - ((base_price * discount_amount) / 100));
         } else {
            var discounted_price     = (base_price - discount_amount);
         }
         $('#discounted_price').val(discounted_price);
      });
   });
   function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
         return false;
      }
      return true;
   }
   function calculateDiscountedPrice(counter, price, type, disc_amt){
      var base_price       = parseFloat(price);
      var price_percentage = type;
      var discount_amount  = parseFloat(disc_amt);
      if(price_percentage == 'PERCENTAGE'){
         var discounted_price     = (base_price - ((base_price * discount_amount) / 100));
      } else {
         var discounted_price     = (base_price - discount_amount);
      }
      $('#discounted_price' + counter).val(discounted_price);
   }
</script>