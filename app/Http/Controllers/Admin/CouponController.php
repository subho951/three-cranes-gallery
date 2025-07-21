<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Coupon;
use App\Models\Category;
use Auth;
use Session;
use Helper;
use Hash;

class CouponController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Coupon',
            'controller'        => 'CouponController',
            'controller_route'  => 'coupon',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'coupon.list';
            $data['rows']                   = Coupon::where('status', '!=', 3)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'title'                     => 'required',
                    'coupon_code'               => 'required',
                    'discount_type'             => 'required',
                    'discount_amount'           => 'required',
                    'start_date'                => 'required',
                    'end_date'                  => 'required',
                    'minimum_amount'            => 'required',
                ];
                if($this->validate($request, $rules)){
                    $fields = [
                        'title'                     => $postData['title'],
                        'coupon_code'               => $postData['coupon_code'],
                        'discount_type'             => $postData['discount_type'],
                        'discount_amount'           => $postData['discount_amount'],
                        'start_date'                => $postData['start_date'],
                        'end_date'                  => $postData['end_date'],
                        'minimum_amount'            => $postData['minimum_amount'],
                        'category'                  => (($postData['category'] != '')?$postData['category']:0),
                    ];
                    Coupon::insert($fields);
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'coupon.add-edit';
            $data['row']                    = [];
            $data['child_cats']             = Category::select('id', 'category_name', 'parent_id')->where('status', '=', 1)->where('parent_id', '!=', 0)->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'coupon.add-edit';
            $data['row']                    = Coupon::where($this->data['primary_key'], '=', $id)->first();
            $data['child_cats']             = Category::select('id', 'category_name', 'parent_id')->where('status', '=', 1)->where('parent_id', '!=', 0)->get();
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'title'                     => 'required',
                    'coupon_code'               => 'required',
                    'discount_type'             => 'required',
                    'discount_amount'           => 'required',
                    'start_date'                => 'required',
                    'end_date'                  => 'required',
                    'minimum_amount'            => 'required',
                ];
                if($this->validate($request, $rules)){
                    $fields = [
                        'title'                     => $postData['title'],
                        'coupon_code'               => $postData['coupon_code'],
                        'discount_type'             => $postData['discount_type'],
                        'discount_amount'           => $postData['discount_amount'],
                        'start_date'                => $postData['start_date'],
                        'end_date'                  => $postData['end_date'],
                        'minimum_amount'            => $postData['minimum_amount'],
                        'category'                  => (($postData['category'] != '')?$postData['category']:0),
                        'updated_at'                => date('Y-m-d H:i:s')
                    ];
                    Coupon::where($this->data['primary_key'], '=', $id)->update($fields);
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
            Coupon::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Coupon::find($id);
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
}
