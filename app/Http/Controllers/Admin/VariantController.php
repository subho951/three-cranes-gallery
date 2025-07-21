<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
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

class VariantController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Variant',
            'controller'        => 'VariantController',
            'controller_route'  => 'variant',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list($product_id){
            $product_id                     = Helper::decoded($product_id);
            $data['product_id']             = $product_id;
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'variant.list';
            $data['rows']                   = ProductAttribute::where('status', '!=', 3)->where('product_id', '=', $product_id)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request, $product_id){
            $product_id               = Helper::decoded($product_id);
            $data['product_id']       = $product_id;
            $product                  = Product::where('id', '=', $product_id)->first();
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'markup_price'         => 'required',
                    'actual_price'         => 'required',
                ];
                if($this->validate($request, $rules)){
                    $fields = [
                        'product_id'                    => $product_id,
                        'product_attribute_id'          => implode("-", $postData['product_attribute_id']),
                        'product_attribute_value_id'    => implode("-", $postData['product_attribute_value_id']),
                        'markup_price'                  => $postData['markup_price'],
                        'actual_price'                  => $postData['actual_price'],
                    ];
                    // Helper::pr($fields);
                    ProductAttribute::insert($fields);
                    return redirect('admin/'.$this->data['controller_route'] . "/list/".Helper::encoded($product_id))->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'variant.add-edit';
            $data['row']                    = [];
            $data['attrs']                  = Attribute::select('id', 'name')->where('parent_category', '=', $product->main_category)->where('sub_category_id', '=', $product->sub_category)->where('status', '=', 1)->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $product_id, $id){
            $product_id                     = Helper::decoded($product_id);
            $data['module']                 = $this->data;
            $data['product_id']             = $product_id;
            $product                        = Product::where('id', '=', $product_id)->first();
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'variant.add-edit';
            $data['row']                    = ProductAttribute::where($this->data['primary_key'], '=', $id)->first();
            $data['attrs']                  = Attribute::select('id', 'name')->where('parent_category', '=', $product->main_category)->where('sub_category_id', '=', $product->sub_category)->where('status', '=', 1)->get();

            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'markup_price'         => 'required',
                    'actual_price'         => 'required',
                ];
                if($this->validate($request, $rules)){
                    $fields = [
                        'product_id'                    => $product_id,
                        'product_attribute_id'          => implode("-", $postData['product_attribute_id']),
                        'product_attribute_value_id'    => implode("-", $postData['product_attribute_value_id']),
                        'markup_price'                  => $postData['markup_price'],
                        'actual_price'                  => $postData['actual_price'],
                        'updated_at'                    => date('Y-m-d H:i:s')
                    ];
                    ProductAttribute::where($this->data['primary_key'], '=', $id)->update($fields);
                    return redirect('admin/'.$this->data['controller_route'] . "/list/".Helper::encoded($product_id))->with('success_message', $this->data['title'].' Updated Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* edit */
    /* delete */
        public function delete(Request $request, $product_id, $id){
            $id                             = Helper::decoded($id);
            $fields = [
                'status'             => 3
            ];
            ProductAttribute::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect('admin/'.$this->data['controller_route'] . "/list/".Helper::encoded($product_id))->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $product_id, $id){
            $id                             = Helper::decoded($id);
            $model                          = ProductAttribute::find($id);
            if ($model->status == 1)
            {
                $model->status  = 0;
                $msg            = 'Deactivated';
            } else {
                $model->status  = 1;
                $msg            = 'Activated';
            }            
            $model->save();
            return redirect('admin/'.$this->data['controller_route'] . "/list/".Helper::encoded($product_id))->with('success_message', $this->data['title'].' '.$msg.' Successfully !!!');
        }
    /* change status */
}
