<?php
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
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
</style>
<link href="<?=env('ADMIN_ASSETS_URL')?>assets/typeahead.css"  rel="stylesheet" />
<link href="<?=env('ADMIN_ASSETS_URL')?>assets/bootstrap-tagsinput.css" rel="stylesheet">
<style>
  .twitter-typeahead { display:initial !important; }
  .bootstrap-tagsinput {line-height:40px;display:block !important;}
  .bootstrap-tagsinput .tag {background:#d81636;padding:5px;border-radius:4px;}
  .tt-hint {top:2px !important;}
  .tt-input{vertical-align:baseline !important;}
  .typeahead { border: 1px solid #CCCCCC;border-radius: 4px;padding: 8px 12px;width: 300px;font-size:1.5em;}
  .tt-menu { width:300px; }
  span.twitter-typeahead .tt-suggestion {padding: 10px 20px;  border-bottom:#CCC 1px solid;cursor:pointer;}
  span.twitter-typeahead .tt-suggestion:last-child { border-bottom:0px; }
  .demo-label {font-size:1.5em;color: #686868;font-weight: 500;}
  .bgcolor {max-width: 440px;height: 200px;background-color: #c3e8cb;padding: 40px 70px;border-radius:4px;margin:20px 0px;}
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
      $name                  = $row->name;
    } else {
      $name                  = '';
    }
    $parent_id                  = $row->parent_id;
    $parentCategory             = Category::select('category_name')->where('id', '=', $parent_id)->first();
    $child_id                   = $row->id;
    $childCategory              = Category::select('category_name')->where('id', '=', $child_id)->first();
    ?>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          <div class="row mb-3">
            <label for="parent_id" class="col-md-2 col-lg-2 col-form-label">Parent Category</label>
            <div class="col-md-10 col-lg-10">
              <input type="text" class="form-control" value="<?=(($parentCategory)?$parentCategory->category_name:'')?>" readonly>
            </div>
          </div>
          <div class="row mb-3">
            <label for="category_name" class="col-md-2 col-lg-2 col-form-label">Sub Category</label>
            <div class="col-md-10 col-lg-10">
              <input type="text" class="form-control" value="<?=(($childCategory)?$childCategory->category_name:'')?>" readonly>
            </div>
          </div>

          <!-- edit -->
            <?php
            $checkAttrs = Attribute::where('parent_category', '=', $parent_id)->where('sub_category_id', '=', $child_id)->where('status', '!=', 3)->get();
            if($checkAttrs){ $sl=101;foreach($checkAttrs as $checkAttr){
              $getAttrVals = AttributeValue::where('attr_id', '=', $checkAttr->id)->where('status', '=', 1)->get();
              $getAttrValCount = AttributeValue::where('attr_id', '=', $checkAttr->id)->where('status', '=', 1)->count();
            ?>
              <form method="POST" action="" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="parent_category" value="<?=$parent_id?>">
                <input type="hidden" name="sub_category_id" value="<?=$child_id?>">
                <input type="hidden" name="attr_id" value="<?=$checkAttr->id?>">

                <div class="row mt-3 mb-3" style="border:3px solid green; border-radius: 10px; padding: 15px;">
                  <div class="col-md-9">
                    <label class="col-form-label">Attribute Name</label>
                    <input type="text" class="form-control" name="name[]" value="<?=$checkAttr->name?>">
                  </div>
                  <div class="col-md-3">
                    <label class="col-form-label">Is Price Effect ?</label>
                    <select class="form-control" name="is_price_effect[]" required>
                      <option value="" selected>Select</option>
                      <option value="1" <?=(($checkAttr->is_price_effect == 1)?'selected':'')?>>Yes</option>
                      <option value="0" <?=(($checkAttr->is_price_effect == 0)?'selected':'')?>>No</option>
                    </select>
                  </div>

                  <div class="field_wrapper<?=$checkAttr->id?> mt-3 mb-3">
                    <div class="row">
                      <?php if($getAttrVals){ foreach($getAttrVals as $getAttrVal){?>
                        <!-- attribute values -->
                          <input type="hidden" name="attr_val_id[]" value="<?=$getAttrVal->id?>">
                          <div class="row mt-3" id="attr-value-<?=$getAttrVal->id?>" style="border: 3px solid #f9b922;padding: 10px;border-radius: 10px;">
                            <div class="col-md-4">
                              <label for="name" class="col-form-label">Attribute Value Name</label>
                              <input type="text" class="form-control" name="attr_value[]" value="<?=$getAttrVal->attr_value?>" required>
                            </div>
                            <div class="col-md-1">
                              <label for="name" class="col-form-label">Price Type</label>
                              <select class="form-control" name="price_type[]">
                                <option value="" selected>Select</option>
                                <option value="PERCENT" <?=(($getAttrVal->price_type == 'PERCENT')?'selected':'')?>>PERCENT</option>
                                <option value="FLAT" <?=(($getAttrVal->price_type == 'FLAT')?'selected':'')?>>FLAT</option>
                              </select>
                            </div>
                            <div class="col-md-1">
                              <label for="name" class="col-form-label">Price Value</label>
                              <input type="text" class="form-control" name="price_val[]" value="<?=$getAttrVal->price_val?>">
                            </div>
                            <div class="col-md-3">
                              <label for="name" class="col-form-label">Image</label>
                              <input type="file" class="form-control" name="attr_value_image[]" value="">
                              <?php if($getAttrVal->attr_value_image != ''){?>
                                <input type="hidden" name="attr_value_prev_image[]" value="<?=$getAttrVal->attr_value_image?>">
                                <img src="<?=env('UPLOADS_URL').'product/'.$getAttrVal->attr_value_image?>" class="img-thumbnail" style="width: 75px; height: 75px;">
                              <?php }?>
                            </div>
                            <div class="col-md-2">
                              <label for="name" class="col-form-label">Reference Value</label>
                              <input type="text" class="form-control" name="ref_val[]" value="<?=$getAttrVal->ref_val?>">
                            </div>
                            <div class="col-md-1">
                              <label for="name" class="col-form-label">Action</label><br>
                              <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm" title="Remove Attribute Value" onclick="removeAttributeValue2(<?=$getAttrVal->id?>);"><i class="fa fa-minus-circle"></i></a>
                            </div>
                          </div>
                        <!-- attribute values -->
                      <?php } }?>
                      <div class="col-md-12 text-center">
                        <a href="javascript:void(0);" class="btn btn-outline-success btn-sm" title="Add Attribute Value" onclick="addAttributeValue(<?=$checkAttr->id?>);" style="margin-top: 39px;"><i class="fa fa-plus-circle"></i> Add Attribute Value</a>
                        <input type="hidden" id="counter" value="1000">
                      </div>
                    </div>
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn btn-info btn-sm">Update Attribute</button>
                  </div>
                </div>
              </form>
            <?php } }?>
          <!-- edit -->

          <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="parent_category" value="<?=$parent_id?>">
            <input type="hidden" name="sub_category_id" value="<?=$child_id?>">
            <input type="hidden" name="attr_id" value="0">

            <div class="row mt-3 mb-3" style="border:1px solid #dfcdaf; border-radius: 10px; padding: 15px;">
              <div class="col-md-9">
                <label class="col-form-label">Attribute Name</label>
                <input type="text" class="form-control" name="name[]">
              </div>
              <div class="col-md-3">
                <label class="col-form-label">Is Price Effect ?</label>
                <select class="form-control" name="is_price_effect[]" required>
                  <option value="" selected>Select</option>
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>

              <div class="field_wrapper mt-3 mb-3">
                <div class="row">
                  <div class="col-md-12 text-center">
                    <a href="javascript:void(0);" class="btn btn-outline-success btn-sm add_button" title="Add Attribute Value" style="margin-top: 39px;"><i class="fa fa-plus-circle"></i> Add Attribute Value</a>
                  </div>
                </div>
              </div>

              <div class="text-center">
                <button type="submit" class="btn btn-primary btn-sm">Add Attribute</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</section>
<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  $(document).ready(function(){
      var maxField = 10; //Input fields increment limitation
      var addButton = $('.add_button'); //Add button selector
      var wrapper = $('.field_wrapper'); //Input field wrapper
      var x = 1; //Initial field counter is 1
      
      // Once add button is clicked
      $(addButton).click(function(){
          //Check maximum number of input fields
          if(x < maxField){
              var fieldHTML = '<div class="row mt-3" style="border: 1px solid #f9b922;padding: 10px;border-radius: 10px;">\
                                <input type="hidden" name="attr_val_id[]" value="">\
                                <div class="col-md-4">\
                                  <label for="name" class="col-form-label">Attribute Value Name</label>\
                                  <input type="text" class="form-control" name="attr_value[]" required>\
                                </div>\
                                <div class="col-md-1">\
                                  <label for="name" class="col-form-label">Price Type</label>\
                                  <select class="form-control" name="price_type[]">\
                                    <option value="" selected>Select</option>\
                                    <option value="PERCENT">PERCENT</option>\
                                    <option value="FLAT">FLAT</option>\
                                  </select>\
                                </div>\
                                <div class="col-md-1">\
                                  <label for="name" class="col-form-label">Price Value</label>\
                                  <input type="text" class="form-control" name="price_val[]">\
                                </div>\
                                <div class="col-md-3">\
                                  <label for="name" class="col-form-label">Image</label>\
                                  <input type="file" class="form-control" name="attr_value_image[]">\
                                </div>\
                                <div class="col-md-2">\
                                  <label for="name" class="col-form-label">Reference Value</label>\
                                  <input type="text" class="form-control" name="ref_val[]">\
                                </div>\
                                <div class="col-md-1">\
                                  <label for="name" class="col-form-label">Action</label><br>\
                                  <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm remove_button" title="Remove Attribute Value"><i class="fa fa-minus-circle"></i></a>\
                                </div>\
                              </div>'; //New input field html
              x++; //Increase field counter 
              $(wrapper).append(fieldHTML); //Add field html
          }else{
              alert('A maximum of '+maxField+' fields are allowed to be added. ');
          }
      });
      
      // Once remove button is clicked
      $(wrapper).on('click', '.remove_button', function(e){
          e.preventDefault();
          $(this).parent('div').parent('div').remove(); //Remove field html
          x--; //Decrease field counter
      });
  });

  function addAttributeValue(wrapperId){
    var counter = parseInt($("#counter").val());
    var incremenetedCounter = counter + 1;
    $("#counter").val(incremenetedCounter);

    var wrapper = $('.field_wrapper' + wrapperId);
    var fieldHTML = '<div class="row mt-3" id="attr-value-' + incremenetedCounter + '" style="border: 1px solid #f9b922;padding: 10px;border-radius: 10px;">\
                      <input type="hidden" name="attr_val_id[]" value="">\
                      <div class="col-md-4">\
                        <label for="name" class="col-form-label">Attribute Value Name</label>\
                        <input type="text" class="form-control" name="attr_value[]" required>\
                      </div>\
                      <div class="col-md-1">\
                        <label for="name" class="col-form-label">Price Type</label>\
                        <select class="form-control" name="price_type[]">\
                          <option value="" selected>Select</option>\
                          <option value="PERCENT">PERCENT</option>\
                          <option value="FLAT">FLAT</option>\
                        </select>\
                      </div>\
                      <div class="col-md-1">\
                        <label for="name" class="col-form-label">Price Value</label>\
                        <input type="text" class="form-control" name="price_val[]">\
                      </div>\
                      <div class="col-md-3">\
                        <label for="name" class="col-form-label">Image</label>\
                        <input type="file" class="form-control" name="attr_value_image[]">\
                      </div>\
                      <div class="col-md-2">\
                        <label for="name" class="col-form-label">Reference Value</label>\
                        <input type="text" class="form-control" name="ref_val[]">\
                      </div>\
                      <div class="col-md-1">\
                        <label for="name" class="col-form-label">Action</label><br>\
                        <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm" title="Remove Attribute Value" onclick="removeAttributeValue1(' + incremenetedCounter + ');"><i class="fa fa-minus-circle"></i></a>\
                      </div>\
                    </div>';
    $(wrapper).append(fieldHTML);
  }
  function removeAttributeValue1(id){
    var counter = parseInt($("#counter").val());
    var incremenetedCounter = counter - 1;
    $("#counter").val(incremenetedCounter);
    $('#attr-value-' + id).remove();
  }
  function removeAttributeValue2(id){
    $('#attr-value-' + id).remove();
  }
</script>