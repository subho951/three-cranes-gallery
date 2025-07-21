<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductAttribute;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Auth;
use Session;
use Helper;
use Hash;
class ProductController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Product',
            'controller'        => 'ProductController',
            'controller_route'  => 'product',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'product.list';
            $data['rows']                   = Product::where('status', '!=', 3)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $postData = $request->all();
                // Helper::pr($postData);
                $rules = [
                    'main_category'                 => 'required',
                    'sub_category'                  => 'required',
                    'name'                          => 'required',
                    'base_price'                    => 'required',
                    'cover_image'                   => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* cover image */
                        $imageFile      = $request->file('cover_image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('cover_image', $imageName, 'product', 'image');
                            if($uploadedFile['status']){
                                $cover_image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            return redirect()->back()->with(['error_message' => 'Please Upload Cover Image !!!']);
                        }
                    /* cover image */
                    if($postData['product_video'] != ''){
                        $product_video      = $postData['product_video'];
                        $video_array = explode("watch?v=", $product_video);
                        $product_video_code = $video_array[1];
                    } else {
                        $product_video_code = '';
                        $product_video      = '';
                    }
                    $fields = [
                        'main_category'             => $postData['main_category'],
                        'sub_category'              => $postData['sub_category'],
                        'name'                      => $postData['name'],
                        'base_price'                => $postData['base_price'],
                        'markup_price'              => $postData['markup_price'],
                        'external_product_link'     => $postData['external_product_link'],
                        'slug'                      => Helper::clean($postData['name']),
                        'cover_image'               => $cover_image,
                        'short_description'         => $postData['short_description'],
                        'long_description'          => $postData['long_description'],
                        'product_sku'               => $postData['product_sku'],
                        'product_weight'            => $postData['product_weight'],
                        'product_weight_unit'       => $postData['product_weight_unit'],
                        'related_products'          => ((array_key_exists("related_products",$postData))?json_encode($postData['related_products']):json_encode([])),
                        'is_feature'                => $postData['is_feature'],
                        'manufacturer'              => $postData['manufacturer'],
                        'features'                  => $postData['features'],
                        'size'                      => $postData['size'],
                        'space_needed'              => $postData['space_needed'],
                        'mulch'                     => $postData['mulch'],
                        'border'                    => $postData['border'],
                        'product_video_code'        => $product_video_code,
                        'product_video'             => $product_video,
                        'delivery_cost_lead_time'   => $postData['delivery_cost_lead_time'],
                        'overview'                  => $postData['overview'],
                        'additional_info'           => $postData['additional_info'],
                        'shipping'                  => $postData['shipping'],
                        'site_prep'                 => $postData['site_prep'],
                        'dimension'                 => $postData['dimension'],
                        'age_range'                 => $postData['age_range'],
                        'capacity'                  => $postData['capacity'],
                        'base_build_code'           => $postData['base_build_code'],
                        'meta_title'                => $postData['meta_title'],
                        'meta_description'          => $postData['meta_description'],
                        'meta_keywords'             => $postData['meta_keywords'],
                    ];
                    // Helper::pr($fields);
                    $product_id = Product::insertGetId($fields);
                    $id = $product_id;
                    /* attribute */
                        ProductAttribute::where('product_id', '=', $id)->delete();
                        if(array_key_exists("attr_id",$postData)){
                            if(array_key_exists("attr_value_id",$postData)){
                                $attr_id        = $postData['attr_id'];
                                $attr_value_id  = $postData['attr_value_id'];
                                $attr_price     = $postData['attr_price'];
                                if(!empty($attr_value_id)){
                                    for($p=0;$p<count($attr_value_id);$p++){
                                        $attr_val_array = explode("/", $attr_value_id[$p]);
                                        $parent_id  = $attr_val_array[0];
                                        $child_id   = $attr_val_array[1];
                                        if(in_array($parent_id, $attr_id)){
                                            $attrData = [
                                                'product_id'                    => $id,
                                                'product_attribute_id'          => $parent_id,
                                                'product_attribute_value_id'    => $child_id,
                                                'unit_price'                    => (($attr_price[$p] != '')?$attr_price[$p]:0.00),
                                            ];
                                            ProductAttribute::insert($attrData);
                                        }
                                    }
                                }
                            }
                        }
                    /* attribute */
                    // other images
                        if(array_key_exists("other_images",$postData)){
                            $other_images                       = $postData['other_images'];
                            $images                             = [];
                            $image_array                        = $request->file('other_images');
                            if(!empty($image_array)){
                                $uploadedFile       = $this->commonFileArrayUpload('public/uploads/product/', $image_array, 'image');
                                if(!empty($uploadedFile)){
                                    $images    = $uploadedFile;
                                } else {
                                    $images    = [];
                                }
                            }
                            if(!empty($images)){
                                for($i=0;$i<count($images);$i++){
                                    $fields2 = [
                                        'product_id'            => $product_id,
                                        'image'                 => $images[$i]
                                    ];
                                    ProductImage::insert($fields2);
                                }
                            }
                        }
                    // other images                    
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'product.add-edit';
            $data['row']                    = [];
            $data['parent_cats']            = Category::select('id', 'category_name', 'parent_id')->where('status', '=', 1)->where('parent_id', '=', 0)->get();
            $data['child_cats']             = Category::select('id', 'category_name', 'parent_id')->where('status', '=', 1)->where('parent_id', '!=', 0)->get();
            $data['units']                  = Unit::select('id', 'name')->where('status', '=', 1)->get();
            $data['otherProducts']          = Product::select('id', 'name')->where('status', '=', 1)->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'product.add-edit';
            $data['row']                    = Product::where($this->data['primary_key'], '=', $id)->first();
            $data['parent_cats']            = Category::select('id', 'category_name', 'parent_id')->where('status', '=', 1)->where('parent_id', '=', 0)->get();
            $data['child_cats']             = Category::select('id', 'category_name', 'parent_id')->where('status', '=', 1)->where('parent_id', '!=', 0)->get();
            $data['units']                  = Unit::select('id', 'name')->where('status', '=', 1)->get();
            $data['otherProducts']          = Product::select('id', 'name')->where('status', '=', 1)->where('id', '!=', $id)->get();
            if($request->isMethod('post')){
                $postData = $request->all();
                // Helper::pr($postData);
                $rules = [
                    'main_category'                 => 'required',
                    'sub_category'                  => 'required',
                    'name'                          => 'required',
                    'base_price'                    => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* cover image */
                        $imageFile      = $request->file('cover_image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('cover_image', $imageName, 'product', 'image');
                            if($uploadedFile['status']){
                                $cover_image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $cover_image = $data['row']->cover_image;
                        }
                    /* cover image */
                    if($postData['product_video'] != ''){
                        $product_video      = $postData['product_video'];
                        $video_array = explode("watch?v=", $product_video);
                        $product_video_code = $video_array[1];
                    } else {
                        $product_video_code = '';
                        $product_video      = '';
                    }
                    $fields = [
                        'main_category'             => $postData['main_category'],
                        'sub_category'              => $postData['sub_category'],
                        'name'                      => $postData['name'],
                        'base_price'                => $postData['base_price'],
                        'markup_price'              => $postData['markup_price'],
                        'external_product_link'     => $postData['external_product_link'],
                        'slug'                      => Helper::clean($postData['name']),
                        'cover_image'               => $cover_image,
                        'short_description'         => $postData['short_description'],
                        'long_description'          => $postData['long_description'],
                        'product_sku'               => $postData['product_sku'],
                        'product_weight'            => $postData['product_weight'],
                        'product_weight_unit'       => $postData['product_weight_unit'],
                        'related_products'          => ((array_key_exists("related_products",$postData))?json_encode($postData['related_products']):json_encode([])),
                        'is_feature'                => $postData['is_feature'],
                        'manufacturer'              => $postData['manufacturer'],
                        'features'                  => $postData['features'],
                        'size'                      => $postData['size'],
                        'space_needed'              => $postData['space_needed'],
                        'mulch'                     => $postData['mulch'],
                        'border'                    => $postData['border'],
                        'product_video_code'        => $product_video_code,
                        'product_video'             => $product_video,
                        'delivery_cost_lead_time'   => $postData['delivery_cost_lead_time'],
                        'overview'                  => $postData['overview'],
                        'additional_info'           => $postData['additional_info'],
                        'shipping'                  => $postData['shipping'],
                        'site_prep'                 => $postData['site_prep'],
                        'dimension'                 => $postData['dimension'],
                        'age_range'                 => $postData['age_range'],
                        'capacity'                  => $postData['capacity'],
                        'base_build_code'           => $postData['base_build_code'],
                        'meta_title'                => $postData['meta_title'],
                        'meta_description'          => $postData['meta_description'],
                        'meta_keywords'             => $postData['meta_keywords'],
                    ];
                    // Helper::pr($fields);
                    Product::where($this->data['primary_key'], '=', $id)->update($fields);
                    $product_id = $id;

                    /* attribute */
                        // Helper::pr($postData);
                        ProductAttribute::where('product_id', '=', $id)->delete();
                        if(array_key_exists("attr_id",$postData)){
                            // if(array_key_exists("attr_value_id",$postData)){
                                $attr_id        = $postData['attr_id'];
                                // Helper::pr($attr_id);
                                if(!empty($attr_id)){
                                    for($layer1=0;$layer1<count($attr_id);$layer1++){
                                        $singleAttrId   = $attr_id[$layer1]; // 29
                                        $attr_value_id  = $postData['attr_value_id'.$singleAttrId]; // 29/164
                                        $attr_price     = $postData['attr_price'.$singleAttrId]; // [164] => 4700
                                        if(!empty($attr_value_id)){
                                            for($p=0;$p<count($attr_value_id);$p++){
                                                $attr_val_array = explode("/", $attr_value_id[$p]);
                                                $parent_id          = $attr_val_array[0];
                                                $child_id           = $attr_val_array[1];
                                                $singleAttrPrice    = $attr_price[$child_id];
                                                if(in_array($parent_id, $attr_id)){
                                                    $attrData = [
                                                        'product_id'                    => $id,
                                                        'product_attribute_id'          => $parent_id,
                                                        'product_attribute_value_id'    => $child_id,
                                                        'unit_price'                    => (($singleAttrPrice != '')?$singleAttrPrice:0.00),
                                                    ];
                                                    ProductAttribute::insert($attrData);
                                                }
                                            }
                                        }
                                    }
                                }
                                // $attr_value_id  = $postData['attr_value_id'];
                                // $attr_price     = $postData['attr_price'];
                                // if(!empty($attr_value_id)){
                                //     for($p=0;$p<count($attr_value_id);$p++){
                                //         $attr_val_array = explode("/", $attr_value_id[$p]);
                                //         $parent_id  = $attr_val_array[0];
                                //         $child_id   = $attr_val_array[1];
                                //         if(in_array($parent_id, $attr_id)){
                                //             $attrData = [
                                //                 'product_id'                    => $id,
                                //                 'product_attribute_id'          => $parent_id,
                                //                 'product_attribute_value_id'    => $child_id,
                                //                 'unit_price'                    => (($attr_price[$p] != '')?$attr_price[$p]:0.00),
                                //             ];
                                //             ProductAttribute::insert($attrData);
                                //         }
                                //     }
                                // }
                            // }
                        }
                    /* attribute */
                    // other images
                        if(array_key_exists("other_images",$postData)){
                            $other_images                       = $postData['other_images'];
                            $images                             = [];
                            $image_array                        = $request->file('other_images');
                            if(!empty($image_array)){
                                $uploadedFile       = $this->commonFileArrayUpload('public/uploads/product/', $image_array, 'image');
                                if(!empty($uploadedFile)){
                                    $images    = $uploadedFile;
                                } else {
                                    $images    = [];
                                }
                            }
                            if(!empty($images)){
                                for($i=0;$i<count($images);$i++){
                                    $fields2 = [
                                        'product_id'            => $product_id,
                                        'image'                 => $images[$i]
                                    ];
                                    ProductImage::insert($fields2);
                                }
                            }
                        }
                    // other images
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Updated Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* edit */
    /* delete */
        public function delete(Request $request, $id){
            $id                             = Helper::decoded($id);
            $fields = [
                'status'             => 3
            ];
            Product::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
        public function deleteSingleImage(Request $request, $id, $product_id){
            $id                             = Helper::decoded($id);
            $product_id                     = Helper::decoded($product_id);
            ProductImage::where('id', '=', $id)->delete();
            return redirect('admin/'.$this->data['controller_route'] . "/edit/".Helper::encoded($product_id))->with('success_message', $this->data['title'].' Images Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Product::find($id);
            if ($model->status == 1)
            {
                $model->status  = 0;
                $msg            = 'Deactivated';
            } else {
                $model->status  = 1;
                $msg            = 'Activated';
            }            
            $model->save();
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' '.$msg.' Successfully !!!');
        }
    /* change status */
    /* change feature */
        public function change_feature(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Product::find($id);
            if ($model->is_feature == 1)
            {
                $model->is_feature  = 0;
                $msg            = 'Non-featured';
            } else {
                $model->is_feature  = 1;
                $msg            = 'Featured';
            }            
            $model->save();
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Marked As '.$msg.' Successfully !!!');
        }
    /* change feature */
    public function getProductAttribute(Request $request){
        $apiStatus          = TRUE;
        $apiMessage         = '';
        $apiResponse        = [];
        $apiExtraField      = '';
        $apiExtraData       = '';

        $data               = [];
        $postData           = $request->all();
        $subcat             = $postData['subcat'];
        $product_id         = $postData['product_id'];
        $getAttrs           = Attribute::select('id', 'name', 'is_price_effect')->where('sub_category_id', '=', $subcat)->where('status', '=', 1)->get();
        if($getAttrs){
            foreach($getAttrs as $getAttr){
                $attr_values            = [];
                $getAttrVals            = AttributeValue::select('id', 'attr_id', 'attr_value', 'price_type', 'price_val', 'ref_val', 'attr_value_image')->where('sub_category_id', '=', $subcat)->where('attr_id', '=', $getAttr->id)->where('status', '=', 1)->get();
                $parentChecked          = 0;
                if($getAttrVals){
                    foreach($getAttrVals as $getAttrVal){
                        $checkAlreadySavedCount = ProductAttribute::where('product_id', '=', $product_id)->where('product_attribute_value_id', '=', $getAttrVal->id)->count();
                        $checkAlreadySaved = ProductAttribute::where('product_id', '=', $product_id)->where('product_attribute_value_id', '=', $getAttrVal->id)->first();
                        // Helper::pr($checkAlreadySaved);
                        if($checkAlreadySavedCount > 0){
                            $parentChecked++;
                        }
                        $attr_val_image = '';
                        if(isset($getAttrVal->attr_value_image) && $getAttrVal->attr_value_image != ''){
                            $attr_val_image = env('UPLOADS_URL').'product/'.$getAttrVal->attr_value_image;
                        }
                        $attr_values[]            = [
                            'attr_val_id'           => $getAttrVal->id,
                            'attr_val_attr_id'      => $getAttrVal->attr_id,
                            'attr_val_name'         => $getAttrVal->attr_value,
                            'attr_val_price_type'   => $getAttrVal->price_type,
                            'attr_val_price_val'    => $getAttrVal->price_val,
                            'attr_val_ref_val'      => $getAttrVal->ref_val,
                            'attr_val_unit_price'   => (($checkAlreadySaved)?$checkAlreadySaved->unit_price:''),
                            'child_checked'         => (($checkAlreadySavedCount > 0)?1:0),
                            'attr_val_image'        => $attr_val_image
                        ];
                    }
                }

                $data[]                 = [
                    'attr_id'               => $getAttr->id,
                    'attr_name'             => $getAttr->name,
                    'attr_is_price_effect'  => $getAttr->is_price_effect,
                    'attr_values'           => $attr_values,
                    'checked'               => (($parentChecked > 0)?1:0),
                ];
            }
        }
        $apiResponse        = $data;
        $apiStatus          = TRUE;
        $apiMessage         = 'Data Available !!!';
        $apiExtraField      = 'response_code';
        $apiExtraData       = http_response_code();
        $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
    }
}
