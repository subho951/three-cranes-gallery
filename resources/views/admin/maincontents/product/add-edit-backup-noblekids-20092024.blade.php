<?php
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductAttribute;
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
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
</style>
<div class="pagetitle">
  <h1><?=$page_header?></h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?=url('dashboard')?>">Home</a></li>
      <li class="breadcrumb-item active"><a href="<?=url('' . $controllerRoute . '/list/')?>"><?=$module['title']?> List</a></li>
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
      $product_id                 = $row->id;
      $main_category              = $row->main_category;
      $sub_category               = $row->sub_category;
      $name                       = $row->name;
      $base_price                 = $row->base_price;
      $markup_price               = $row->markup_price;
      $external_product_link      = $row->external_product_link;
      $cover_image                = $row->cover_image;
      $short_description          = $row->short_description;
      $long_description           = $row->long_description;
      $product_sku                = $row->product_sku;
      $product_weight             = $row->product_weight;
      $product_weight_unit        = $row->product_weight_unit;
      $related_products           = json_decode($row->related_products);
      $is_feature                 = $row->is_feature;
      $manufacturer               = $row->manufacturer;
      $features                   = $row->features;
      $size                       = $row->size;
      $space_needed               = $row->space_needed;
      $mulch                      = $row->mulch;
      $border                     = $row->border;
      $product_video_code         = $row->product_video_code;
      $product_video              = $row->product_video;
      $delivery_cost_lead_time    = $row->delivery_cost_lead_time;
      $overview                   = $row->overview;
      $additional_info            = $row->additional_info;
      $shipping                   = $row->shipping;
      $site_prep                  = $row->site_prep;
      $dimension                  = $row->dimension;
      $age_range                  = $row->age_range;
      $capacity                   = $row->capacity;
      $base_build_code            = $row->base_build_code;
      $meta_title                 = $row->meta_title;
      $meta_description           = $row->meta_description;
      $meta_keywords              = $row->meta_keywords;
    } else {
      $product_id                 = 0;
      $main_category              = '';
      $sub_category               = '';
      $name                       = '';
      $base_price                 = '';
      $markup_price               = '';
      $external_product_link      = '';
      $cover_image                = '';
      $short_description          = '';
      $long_description           = '';
      $product_sku                = '';
      $product_weight             = '';
      $product_weight_unit        = '';
      $related_products           = [];
      $is_feature                 = 0;
      $manufacturer               = '';
      $features                   = '';
      $size                       = '';
      $space_needed               = '';
      $mulch                      = '';
      $border                     = '';
      $product_video_code         = '';
      $product_video              = '';
      $delivery_cost_lead_time    = '';
      $overview                   = '';
      $additional_info            = '';
      $shipping                   = '';
      $site_prep                  = '';
      $dimension                  = '';
      $age_range                  = '';
      $capacity                   = '';
      $base_build_code            = '';
      $meta_title                 = '';
      $meta_description           = '';
      $meta_keywords              = '';
    }
    ?>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          <small class="text-danger">Star (*) marks fields are mandatory</small>
          <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
              <label for="main_category" class="col-md-2 col-lg-2 col-form-label">Main Category <span class="text-danger">*</span></label>
              <div class="col-md-10 col-lg-10">
                  <select name="main_category" class="form-control" id="main_category" required>
                    <option value="" selected>Select Main Category</option>
                    <?php if($parent_cats){ foreach($parent_cats as $row){?>
                    <option value="<?=$row->id?>" <?=(($row->id == $main_category)?'selected':'')?>><?=$row->category_name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="sub_category" class="col-md-2 col-lg-2 col-form-label">Sub Category <span class="text-danger">*</span></label>
              <div class="col-md-10 col-lg-10">
                  <select name="sub_category" class="form-control" id="sub_category" required>
                    <option value="" selected>Select Sub Category</option>
                    <?php if($child_cats){ foreach($child_cats as $row){?>
                    <option class="category subcat<?=$row->parent_id?>" value="<?=$row->id?>" <?=(($row->id == $sub_category)?'selected':'')?>><?=$row->category_name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="name" class="col-md-2 col-lg-2 col-form-label">Name <span class="text-danger">*</span></label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="name" class="form-control" id="name" value="<?=$name?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="base_price" class="col-md-2 col-lg-2 col-form-label">Base Price <span class="text-danger">*</span></label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="base_price" class="form-control" id="base_price" value="<?=$base_price?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="markup_price" class="col-md-2 col-lg-2 col-form-label">Mark-up Price <span class="text-danger">*</span></label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="markup_price" class="form-control" id="markup_price" value="<?=$markup_price?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="external_product_link" class="col-md-2 col-lg-2 col-form-label">External Product Link</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="external_product_link" class="form-control" id="external_product_link" value="<?=$external_product_link?>">
              </div>
            </div>
            <div class="row mb-3">
              <label for="short_description" class="col-md-2 col-lg-2 col-form-label">Short Description</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="short_description" class="form-control" id="short_description" rows="5"><?=$short_description?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="long_description" class="col-md-2 col-lg-2 col-form-label">Long Description</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="long_description" class="form-control" id="ckeditor1" rows="5"><?=$long_description?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="product_sku" class="col-md-2 col-lg-2 col-form-label">SKU</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="product_sku" class="form-control" id="product_sku" value="<?=$product_sku?>">
              </div>
            </div>
            <div class="row mb-3">
              <label for="product_weight" class="col-md-2 col-lg-2 col-form-label">Weight</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="product_weight" class="form-control" id="product_weight" value="<?=$product_weight?>">
              </div>
            </div>
            <div class="row mb-3">
              <label for="product_weight_unit" class="col-md-2 col-lg-2 col-form-label">Weight Unit</label>
              <div class="col-md-10 col-lg-10">
                  <select name="product_weight_unit" class="form-control" id="product_weight_unit">
                    <option value="" selected>Select Weight Unit</option>
                    <?php if($units){ foreach($units as $row){?>
                    <option value="<?=$row->id?>" <?=(($row->id == $product_weight_unit)?'selected':'')?>><?=$row->name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="choices-multiple-remove-button" class="col-md-2 col-lg-2 col-form-label">Related Products</label>
              <div class="col-md-10 col-lg-10">
                  <select name="related_products[]" class="form-control" id="choices-multiple-remove-button" multiple>
                    <?php if($otherProducts){ foreach($otherProducts as $row){?>
                    <option value="<?=$row->id?>" <?=((in_array($row->id, $related_products))?'selected':'')?>><?=$row->name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="is_feature" class="col-md-2 col-lg-2 col-form-label">Is Featured</label>
              <div class="col-md-10 col-lg-10">
                  <select name="is_feature" class="form-control" id="is_feature">
                    <option value="" selected>Select Is Featured</option>
                    <option value="1" <?=(($is_feature == 1)?'selected':'')?>>YES</option>
                    <option value="0" <?=(($is_feature == 0)?'selected':'')?>>NO</option>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="cover_image" class="col-md-2 col-lg-2 col-form-label">Cover Image <span class="text-danger">*</span></label>
              <div class="col-md-10 col-lg-10">
                <input type="file" name="cover_image" class="form-control" id="cover_image">
                <small class="text-info">* Only JPG, JPEG, ICO, SVG, PNG files are allowed</small><br>
                <?php if($cover_image != ''){?>
                  <img src="<?=env('UPLOADS_URL').'product/'.$cover_image?>" alt="<?=$name?>" style="width: 150px; height: 150px; margin-top: 10px;border-radius: 50%;">
                <?php } else {?>
                  <img src="<?=env('NO_IMAGE')?>" alt="<?=$name?>" class="img-thumbnail" style="width: 150px; height: 150px; margin-top: 10px;">
                <?php }?>
              </div>
            </div>
            <div class="row mb-3">
              <label for="other_images" class="col-md-2 col-lg-2 col-form-label">Other Images</label>
              <div class="col-md-10 col-lg-10">
                <input type="file" name="other_images[]" class="form-control" id="other_images" multiple>
                <small class="text-info">* Only JPG, JPEG, ICO, SVG, PNG files are allowed</small><br>
                <small class="text-info">* CTRL + select images to multiple images</small><br>
                <div class="row">
                  <?php if($product_id > 0){?>
                    <?php
                    $product_other_images = ProductImage::where('product_id', '=', $product_id)->where('status', '=', 1)->get();
                    if($product_other_images){ foreach($product_other_images as $product_other_image){
                    ?>
                      <div class="col-md-2">
                        <img src="<?=env('UPLOADS_URL').'product/'.$product_other_image->image?>" alt="<?=$name?>" class="img-thumbnail" style="width: 150px; height: 150px; margin-top: 10px;"><br>
                        <a href="<?=url('' . $controllerRoute . '/delete-single-image/'.Helper::encoded($product_other_image->id).'/'.Helper::encoded($product_id))?>" class="btn btn-danger btn-sm" onclick="return confirm('Do You Want To Delete This Image ?')"><i class="fa fa-trash"></i> Delete</a>
                      </div>
                    <?php } } else {?>
                      <img src="<?=env('NO_IMAGE')?>" alt="<?=$name?>" class="img-thumbnail" style="width: 150px; height: 150px; margin-top: 10px;">
                    <?php }?>
                  <?php }?>
                </div>
              </div>
            </div>

            <div class="row mb-3">
              <label for="manufacturer" class="col-md-2 col-lg-2 col-form-label">Manufacturer</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="manufacturer" class="form-control" id="manufacturer" value="<?=$manufacturer?>">
              </div>
            </div>
            <div class="row mb-3">
              <label for="features" class="col-md-2 col-lg-2 col-form-label">Features</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="features" class="form-control" id="ckeditor2" rows="5"><?=$features?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="size" class="col-md-2 col-lg-2 col-form-label">Size</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="size" class="form-control" id="ckeditor3" rows="5"><?=$size?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="space_needed" class="col-md-2 col-lg-2 col-form-label">Space Needed</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="space_needed" class="form-control" id="ckeditor4" rows="5"><?=$space_needed?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="mulch" class="col-md-2 col-lg-2 col-form-label">Mulch</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="mulch" class="form-control" id="ckeditor5" rows="5"><?=$mulch?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="border" class="col-md-2 col-lg-2 col-form-label">Border</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="border" class="form-control" id="ckeditor6" rows="5"><?=$border?></textarea>
              </div>
            </div>

            <div class="row mb-3">
              <label for="product_video" class="col-md-2 col-lg-2 col-form-label">Product Video</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="product_video" class="form-control" id="product_video" value="<?=$product_video?>">
                <?php if($product_video != ''){?>
                  <p class="mt-3">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/<?=$product_video_code?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                  </p>
                <?php }?>
              </div>
            </div>

            <div class="row mb-3">
              <label for="delivery_cost_lead_time" class="col-md-2 col-lg-2 col-form-label">Delivery Cost & Lead Time</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="delivery_cost_lead_time" class="form-control" id="ckeditor7" rows="5"><?=$delivery_cost_lead_time?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="overview" class="col-md-2 col-lg-2 col-form-label">Overview</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="overview" class="form-control" id="ckeditor8" rows="5"><?=$overview?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="additional_info" class="col-md-2 col-lg-2 col-form-label">Additional Info</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="additional_info" class="form-control" id="ckeditor9" rows="5"><?=$additional_info?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="shipping" class="col-md-2 col-lg-2 col-form-label">Shipping</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="shipping" class="form-control" id="ckeditor10" rows="5"><?=$shipping?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="site_prep" class="col-md-2 col-lg-2 col-form-label">Site Prep</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="site_prep" class="form-control" id="ckeditor11" rows="5"><?=$site_prep?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="dimension" class="col-md-2 col-lg-2 col-form-label">Dimension</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="dimension" class="form-control" id="ckeditor12" rows="5"><?=$dimension?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="age_range" class="col-md-2 col-lg-2 col-form-label">Age Range</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="age_range" class="form-control" id="ckeditor13" rows="5"><?=$age_range?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="capacity" class="col-md-2 col-lg-2 col-form-label">Capacity</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="capacity" class="form-control" id="ckeditor14" rows="5"><?=$capacity?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="base_build_code" class="col-md-2 col-lg-2 col-form-label">Base Build Code</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="base_build_code" class="form-control" id="ckeditor15" rows="5"><?=$base_build_code?></textarea>
              </div>
            </div>

            <div class="row mb-3">
              <label for="meta_title" class="col-md-2 col-lg-2 col-form-label">Meta Title</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="meta_title" class="form-control" id="meta_title" rows="5"><?=$meta_title?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="meta_description" class="col-md-2 col-lg-2 col-form-label">Meta Description</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="meta_description" class="form-control" id="meta_description" rows="5"><?=$meta_description?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="meta_keywords" class="col-md-2 col-lg-2 col-form-label">Meta Keywords</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="meta_keywords" class="form-control" id="meta_keywords" rows="5"><?=$meta_keywords?></textarea>
              </div>
            </div>

            <div class="row mb-3">
              <label for="meta_keywords" class="col-md-2 col-lg-2 col-form-label">Attributes</label>
              <div class="col-md-10 col-lg-10" id="attribute">

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){    
    var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
        removeItemButton: true,
        maxItemCount:30,
        searchResultLimit:30,
        renderChoiceLimit:30
    });     
  });
  $(function(){
    /* onload sub category wise attributes */
      var base_url    = '<?=url('/')?>';
      var subcat      = $('#sub_category').val();
      var product_id  = '<?=$product_id?>';
      $.ajax({
          type: "POST",
          url: base_url + "/admin/product/get-product-attribute",
          data: {product_id : product_id, subcat : subcat, "_token": "{{ csrf_token() }}"},
          dataType: "JSON",
          beforeSend: function () {
              
          },
          success: function (response) {
              if(response.status){
                $('#attribute').empty();
                var attributeHTML    = '';
                var selectValues    = response.data;
                $.each(selectValues, function(key, value) {
                    var attributeValueHTML   = '';
                    var childs          = value.attr_values;
                    $.each(childs, function(key2, value2) {
                        if(value2.child_checked == 1){
                          var childChecked  = 'checked';
                          var priceBoxStyle = '';
                        } else {
                          var childChecked  = '';
                          var priceBoxStyle = 'display:none;';
                        }
                        var attr_val_image = '';
                        if(value2.attr_val_image != ''){
                          attr_val_image = '<img src="' + value2.attr_val_image + '" class="img-thumbnail" style="width: 50px; height: 50px;">';
                        }
                        attributeValueHTML   += '<div class="col-md-6 mb-3">\
                                                    <input type="checkbox" name="attr_value_id' + value.attr_id + '[]" class="childAttr' + value2.attr_val_attr_id + '" id="childAttrId' + value2.attr_val_id + '" value="' + value.attr_id + '/' + value2.attr_val_id + '" ' + childChecked + ' onclick="price_box_show_hide(' + value2.attr_val_id + ');">\
                                                    ' + attr_val_image + '\
                                                    <label for="childAttrId' + value2.attr_val_id + '"><b>' + value2.attr_val_name + '</b> (<small class="very-small">' + value2.attr_val_ref_val + '</small>)</label>\
                                                    <input type="textbox" name="attr_price' + value.attr_id + '[' + value2.attr_val_id + ']" class="childAttrPrice' + value2.attr_val_attr_id + '" value="' + value2.attr_val_unit_price + '" id="childAttrPriceId' + value2.attr_val_id + '" style="' + priceBoxStyle + '" />\
                                                  </div>';
                    });
                    
                    if(value.checked){
                      var parentChecked = 'checked';
                    } else {
                      var parentChecked = '';
                    }
                    if(value.attr_is_price_effect){
                      var priceEffect = '<span class="badge bg-success">Price Effect : ON</span>';
                    } else {
                      var priceEffect = '<span class="badge bg-danger">Price Effect : OFF</span>';
                    }
                    attributeHTML += '<div class="row mb-3" style="border: 2px solid #000;padding: 15px;border-radius: 10px;">\
                                        <div class="col-md-12">\
                                          <input type="checkbox" name="attr_id[]" id="attrId' + value.attr_id + '" value="' + value.attr_id + '" ' + parentChecked + ' onclick="checkUncheckAll(' + value.attr_id + ');">\
                                          <label for="attrId' + value.attr_id + '">' + value.attr_name + '</label>\
                                          ' + priceEffect + '\
                                          <div class="row mt-3" style="border:1px solid #f9b922; padding: 15px; border-radius: 10px;">\
                                            ' + attributeValueHTML + '\
                                          </div>\
                                        </div>\
                                      </div>';
                });
                $('#attribute').html(attributeHTML);
              }
          }
      });
    /* onload sub category wise attributes */
    /* onchange sub category wise attributes */
      $('#sub_category').on('change', function(){
        var base_url    = '<?=url('/')?>';
        var subcat      = $('#sub_category').val();
        var product_id  = '<?=$product_id?>';
        $.ajax({
            type: "POST",
            url: base_url + "/admin/product/get-product-attribute",
            data: {product_id : product_id, subcat : subcat, "_token": "{{ csrf_token() }}"},
            dataType: "JSON",
            beforeSend: function () {
                
            },
            success: function (response) {
              if(response.status){
                $('#attribute').empty();
                var attributeHTML    = '';
                var selectValues    = response.data;
                $.each(selectValues, function(key, value) {
                    var attributeValueHTML   = '';
                    var childs          = value.attr_values;
                    $.each(childs, function(key2, value2) {
                        if(value2.child_checked == 1){
                          var childChecked  = 'checked';
                          var priceBoxStyle = '';
                        } else {
                          var childChecked  = '';
                          var priceBoxStyle = 'display:none;';
                        }
                        attributeValueHTML   += '<div class="col-md-4 mb-3">\
                                                    <input type="checkbox" name="attr_value_id[]" class="childAttr' + value2.attr_val_attr_id + '" id="childAttrId' + value2.attr_val_id + '" value="' + value.attr_id + '/' + value2.attr_val_id + '" ' + childChecked + ' onclick="price_box_show_hide(' + value2.attr_val_id + ');">\
                                                    <label for="childAttrId' + value2.attr_val_id + '"><b>' + value2.attr_val_name + '</b> (<small class="very-small">' + value2.attr_val_ref_val + '</small>)</label>\
                                                    <input type="textbox" name="attr_price[]" class="childAttrPrice' + value2.attr_val_attr_id + '" value="' + value2.attr_val_unit_price + '" id="childAttrPriceId' + value2.attr_val_id + '" style="' + priceBoxStyle + '" />\
                                                  </div>';
                    });
                    
                    if(value.checked){
                      var parentChecked = 'checked';
                    } else {
                      var parentChecked = '';
                    }
                    if(value.attr_is_price_effect){
                      var priceEffect = '<span class="badge bg-success">Price Effect : ON</span>';
                    } else {
                      var priceEffect = '<span class="badge bg-danger">Price Effect : OFF</span>';
                    }
                    attributeHTML += '<div class="row mb-3" style="border: 2px solid #000;padding: 15px;border-radius: 10px;">\
                                        <div class="col-md-12">\
                                          <input type="checkbox" name="attr_id[]" id="attrId' + value.attr_id + '" value="' + value.attr_id + '" ' + parentChecked + ' onclick="checkUncheckAll(' + value.attr_id + ');">\
                                          <label for="attrId' + value.attr_id + '">' + value.attr_name + '</label>\
                                          ' + priceEffect + '\
                                          <div class="row mt-3" style="border:1px solid #f9b922; padding: 15px; border-radius: 10px;">\
                                            ' + attributeValueHTML + '\
                                          </div>\
                                        </div>\
                                      </div>';
                });
                $('#attribute').html(attributeHTML);
              }
            }
        });
      });
    /* onchange sub category wise attributes */
    $('#main_category').on('change', function(){
      let main_category = $('#main_category').val();
      $('#sub_category .category').hide();
      $('#sub_category .subcat' + main_category).show();
    });
  })

  function checkUncheckAll(attrId){
    var checked = $('#attrId' + attrId).prop('checked');
    $('.childAttr' + attrId).prop('checked', checked);
    if(checked){
      $('.childAttrPrice' + attrId).show();
    } else {
      $('.childAttrPrice' + attrId).hide();
    }
  }
  function price_box_show_hide(attr_val_id){
    if($("#childAttrId" + attr_val_id).prop('checked') == true){
      $('#childAttrPriceId' + attr_val_id).show();
    } else {
      $('#childAttrPriceId' + attr_val_id).hide();
    }
  }
</script>