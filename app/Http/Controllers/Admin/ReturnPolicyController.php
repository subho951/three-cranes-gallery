<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\ReturnPolicy;
use Auth;
use Session;
use Helper;
use Hash;

class ReturnPolicyController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Return Policy',
            'controller'        => 'ReturnPolicyController',
            'controller_route'  => 'return-policy',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'return-policy.list';
            $data['rows']                   = ReturnPolicy::where('status', '!=', 3)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'type'                  => 'required',
                    'timeframe'             => 'required',
                    'description'           => 'required',
                ];
                if($this->validate($request, $rules)){
                    $type = $postData['type'];
                    if(count($type) <= 0){
                        return redirect()->back()->with('error_message', 'Minimum One Type Needs To Be Select !!!');
                    } else {
                        if(count($type) == 2){
                            $name = 'Returns and exchanges';
                        } else {
                            if(in_array("Return", $type)){
                                $name = 'Returns only';
                            } elseif(in_array("Exchange", $type)){
                                $name = 'Exchanges only';
                            }
                        }
                    }
                    $fields = [
                        'name'                  => $name,
                        'type'                  => json_encode($postData['type']),
                        'timeframe'             => $postData['timeframe'],
                        'description'           => $postData['description'],
                    ];
                    ReturnPolicy::insert($fields);
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'return-policy.add-edit';
            $data['row']                    = [];
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'return-policy.add-edit';
            $data['row']                    = ReturnPolicy::where($this->data['primary_key'], '=', $id)->first();

            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'type'                  => 'required',
                    'timeframe'             => 'required',
                    'description'           => 'required',
                ];
                if($this->validate($request, $rules)){
                    $type = $postData['type'];
                    if(count($type) <= 0){
                        return redirect()->back()->with('error_message', 'Minimum One Type Needs To Be Select !!!');
                    } else {
                        if(count($type) == 2){
                            $name = 'Returns and exchanges';
                        } else {
                            if(in_array("Return", $type)){
                                $name = 'Returns only';
                            } elseif(in_array("Exchange", $type)){
                                $name = 'Exchanges only';
                            }
                        }
                    }
                    $fields = [
                        'name'                  => $name,
                        'type'                  => json_encode($postData['type']),
                        'timeframe'             => $postData['timeframe'],
                        'description'           => $postData['description'],
                        'updated_at'            => date('Y-m-d H:i:s')
                    ];
                    ReturnPolicy::where($this->data['primary_key'], '=', $id)->update($fields);
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
            ReturnPolicy::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = ReturnPolicy::find($id);
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
