<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;

use Auth;
use Session;
use Helper;
use Hash;

class AttributeController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Attribute',
            'controller'        => 'AttributeController',
            'controller_route'  => 'attribute',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'attribute.list';
            $data['rows']                   = Category::where('status', '!=', 3)->where('parent_id', '!=', 0)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'parent_category'             => 'required',
                    'sub_category_id'             => 'required',
                ];
                if($this->validate($request, $rules)){
                    $name       = $postData['name'];
                    $attr_value = $postData['attr_value'];
                    if(!empty($name)){
                        for($a=0;$a<count($name);$a++){
                            $fields = [
                                'parent_category'               => $postData['parent_category'],
                                'sub_category_id'               => $postData['sub_category_id'],
                                'name'                          => $name[$a],
                                'slug'                          => Helper::clean($name[$a]),
                            ];
                            $attr_id = Attribute::insertGetId($fields);

                            /* attribute values */
                                if(!empty($attr_value)){
                                    for($a=0;$a<count($attr_value);$a++){
                                        $fields = [
                                            'parent_category'               => $postData['parent_category'],
                                            'sub_category_id'               => $postData['sub_category_id'],
                                            'attr_id'                       => $attr_id,
                                            'attr_value'                    => $attr_value[$a],
                                        ];
                                        AttributeValue::insertGetId($fields);
                                    }
                                }
                            /* attribute values */
                        }
                    }
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'attribute.add-edit';
            $data['row']                    = [];
            $data['cats']                   = Category::select('id', 'category_name')->where('status', '=', 1)->where('parent_id', '=', 0)->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'attribute.add-edit';
            $data['row']                    = Category::where($this->data['primary_key'], '=', $id)->first();
            $data['cats']                   = Category::select('id', 'category_name')->where('status', '=', 1)->where('parent_id', '=', 0)->get();

            if($request->isMethod('post')){
                $postData = $request->all();
                // Helper::pr($postData,0);
                $rules = [
                    'parent_category'             => 'required',
                    'sub_category_id'             => 'required',
                ];
                if($this->validate($request, $rules)){
                    $attr_id                = $postData['attr_id'];
                    if(array_key_exists("attr_val_id",$postData)){
                        $attr_val_id       = $postData['attr_val_id'];
                    } else {
                        $attr_val_id       = [];
                    }
                    if(array_key_exists("attr_value_prev_image",$postData)){
                        $attr_value_prev_image       = $postData['attr_value_prev_image'];
                    } else {
                        $attr_value_prev_image       = [];
                    }
                    $name                   = $postData['name'];
                    $is_price_effect        = $postData['is_price_effect'];
                    $attr_value             = $postData['attr_value'];
                    $price_type             = $postData['price_type'];
                    $price_val              = $postData['price_val'];
                    if(array_key_exists("attr_value_image",$postData)){
                        $attr_value_image       = array_filter(array_merge($postData['attr_value_image']));
                    } else {
                        $attr_value_image       = [];
                    }
                    $ref_val                = $postData['ref_val'];

                    
                    $images                 = [];
                    if(!empty($attr_value_image)){
                        $image_array                        = array_filter(array_merge($request->file('attr_value_image')));
                        if(!empty($image_array)){
                            $uploadedFile       = $this->commonFileArrayUpload('public/uploads/product/', $image_array, 'image');
                            if(!empty($uploadedFile)){
                                $images    = $uploadedFile;
                            } else {
                                $images    = [];
                            }
                        }
                    }
                    $images = array_merge($attr_value_prev_image,$images);
                    // Helper::pr($images);
                    /* old code */
                        // if(!empty($name)){
                        //     Attribute::where('parent_category', '=', $postData['parent_category'])->where('sub_category_id', '=', $postData['sub_category_id'])->update(['status' => 3]);
                        //     AttributeValue::where('parent_category', '=', $postData['parent_category'])->where('sub_category_id', '=', $postData['sub_category_id'])->delete();
                        //     $i=0;$j=0;
                        //     for($a=0;$a<count($name);$a++){
                        //         $checkExistAttr = Attribute::where('parent_category', '=', $postData['parent_category'])->where('sub_category_id', '=', $postData['sub_category_id'])->where('name', '=', $name[$a])->first();
                        //         if($checkExistAttr){ //update
                        //             $fields1 = [
                        //                 'status'               => 1
                        //             ];
                        //             Attribute::where('id', '=', $checkExistAttr->id)->update($fields1);
                        //             $attr_id = $checkExistAttr->id;
                        //         } else { //insert
                        //             $fields1 = [
                        //                 'parent_category'               => $postData['parent_category'],
                        //                 'sub_category_id'               => $postData['sub_category_id'],
                        //                 'name'                          => $name[$a],
                        //                 'is_price_effect'               => $is_price_effect[$a],
                        //                 'slug'                          => Helper::clean($name[$a]),
                        //             ];
                        //             $attr_id = Attribute::insertGetId($fields1);
                        //         }

                        //         /* attribute values */
                        //             if(!empty($counter[$a])){
                        //                 if($a == 0){
                        //                     for($c=$j;$c<$counter[$a];$c++){
                        //                             $fields2 = [
                        //                             'parent_category'               => $postData['parent_category'],
                        //                             'sub_category_id'               => $postData['sub_category_id'],
                        //                             'attr_id'                       => $attr_id,
                        //                             'attr_value'                    => $attr_value[$c],
                        //                             'price_type'                    => $price_type[$c],
                        //                             'price_val'                     => $price_val[$c],
                        //                             'ref_val'                       => $ref_val[$c],
                        //                         ];
                        //                         AttributeValue::insertGetId($fields2);
                        //                         $i++;
                        //                         // echo $i.'<br>'.$j.'||';
                        //                     }
                        //                     $j=$i;
                        //                 } else {
                        //                     for($c=$j;$c<count($attr_value);$c++){
                        //                         $fields2 = [
                        //                             'parent_category'               => $postData['parent_category'],
                        //                             'sub_category_id'               => $postData['sub_category_id'],
                        //                             'attr_id'                       => $attr_id,
                        //                             'attr_value'                    => $attr_value[$c],
                        //                             'price_type'                    => $price_type[$c],
                        //                             'price_val'                     => $price_val[$c],
                        //                             'ref_val'                       => $ref_val[$c],
                        //                         ];
                        //                         AttributeValue::insertGetId($fields2);
                        //                         $i++;
                        //                         // echo $i.'<br>'.$j.'||';
                        //                     }
                        //                     $j=$i;
                        //                 }
                                        
                                            
                        //                 // echo '<br>'.$j.'||';
                        //             }
                        //         /* attribute values */
                        //     }
                        //     // Helper::pr($postData);
                        // }
                    /* old code */
                    if(!empty($name)){
                        // Attribute::where('parent_category', '=', $postData['parent_category'])->where('sub_category_id', '=', $postData['sub_category_id'])->update(['status' => 3]);
                        // AttributeValue::where('parent_category', '=', $postData['parent_category'])->where('sub_category_id', '=', $postData['sub_category_id'])->delete();
                        for($a=0;$a<count($name);$a++){
                            // $checkExistAttr = Attribute::where('parent_category', '=', $postData['parent_category'])->where('sub_category_id', '=', $postData['sub_category_id'])->where('name', '=', $name[$a])->first();
                            if($attr_id > 0){ //update
                                $fields = [
                                    'status'               => 1
                                ];
                                Attribute::where('id', '=', $attr_id)->update($fields);
                                // $attr_id = $checkExistAttr->id;
                            } else { //insert
                                $fields = [
                                    'parent_category'               => $postData['parent_category'],
                                    'sub_category_id'               => $postData['sub_category_id'],
                                    'name'                          => $name[$a],
                                    'is_price_effect'               => $is_price_effect[$a],
                                    'slug'                          => Helper::clean($name[$a]),
                                ];
                                $attr_id = Attribute::insertGetId($fields);
                            }

                            /* attribute values */
                                if(!empty($attr_value)){
                                    for($c=0;$c<count($attr_value);$c++){
                                        if(array_key_exists("attr_val_id",$postData)){
                                            if($attr_val_id[$c] != ''){
                                                if(array_key_exists("attr_value_image",$postData)){
                                                    $fields = [
                                                        'parent_category'               => $postData['parent_category'],
                                                        'sub_category_id'               => $postData['sub_category_id'],
                                                        'attr_id'                       => $attr_id,
                                                        'attr_value'                    => $attr_value[$c],
                                                        'price_type'                    => $price_type[$c],
                                                        'price_val'                     => $price_val[$c],
                                                        'attr_value_image'              => $images[$c],
                                                        'ref_val'                       => $ref_val[$c],
                                                    ];
                                                } else {
                                                    $fields = [
                                                        'parent_category'               => $postData['parent_category'],
                                                        'sub_category_id'               => $postData['sub_category_id'],
                                                        'attr_id'                       => $attr_id,
                                                        'attr_value'                    => $attr_value[$c],
                                                        'price_type'                    => $price_type[$c],
                                                        'price_val'                     => $price_val[$c],
                                                        'ref_val'                       => $ref_val[$c],
                                                    ];
                                                }
                                                AttributeValue::where('id', '=', $attr_val_id[$c])->update($fields);
                                            } else {
                                                if(array_key_exists("attr_value_image",$postData)){
                                                    $fields = [
                                                        'parent_category'               => $postData['parent_category'],
                                                        'sub_category_id'               => $postData['sub_category_id'],
                                                        'attr_id'                       => $attr_id,
                                                        'attr_value'                    => $attr_value[$c],
                                                        'price_type'                    => $price_type[$c],
                                                        'price_val'                     => $price_val[$c],
                                                        'attr_value_image'              => $images[$c],
                                                        'ref_val'                       => $ref_val[$c],
                                                    ];
                                                } else {
                                                    $fields = [
                                                        'parent_category'               => $postData['parent_category'],
                                                        'sub_category_id'               => $postData['sub_category_id'],
                                                        'attr_id'                       => $attr_id,
                                                        'attr_value'                    => $attr_value[$c],
                                                        'price_type'                    => $price_type[$c],
                                                        'price_val'                     => $price_val[$c],
                                                        'ref_val'                       => $ref_val[$c],
                                                    ];
                                                }
                                                AttributeValue::insertGetId($fields);
                                            }
                                        } else {
                                            if(array_key_exists("attr_value_image",$postData)){
                                                $fields = [
                                                    'parent_category'               => $postData['parent_category'],
                                                    'sub_category_id'               => $postData['sub_category_id'],
                                                    'attr_id'                       => $attr_id,
                                                    'attr_value'                    => $attr_value[$c],
                                                    'price_type'                    => $price_type[$c],
                                                    'price_val'                     => $price_val[$c],
                                                    'attr_value_image'              => $images[$c],
                                                    'ref_val'                       => $ref_val[$c],
                                                ];
                                            } else {
                                                $fields = [
                                                    'parent_category'               => $postData['parent_category'],
                                                    'sub_category_id'               => $postData['sub_category_id'],
                                                    'attr_id'                       => $attr_id,
                                                    'attr_value'                    => $attr_value[$c],
                                                    'price_type'                    => $price_type[$c],
                                                    'price_val'                     => $price_val[$c],
                                                    'ref_val'                       => $ref_val[$c],
                                                ];
                                            }
                                            AttributeValue::insertGetId($fields);
                                        }
                                    }
                                }
                            /* attribute values */
                        }
                    }

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
            Category::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Category::find($id);
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
    public function getAttrValues(){
        $rows = AttributeValue::select('attr_value')->where('status', '!=', 3)->get();
        $attrValues = [];
        if($rows){
            foreach($rows as $row){
                $attrValues[] = $row->attr_value;
            }
        }
        echo json_encode($attrValues);
    }
}
