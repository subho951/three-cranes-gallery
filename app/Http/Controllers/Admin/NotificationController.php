<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Models\Notification;
use App\Models\Subscriber;
use Auth;
use Session;
use Helper;
use Hash;
class NotificationController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Notification',
            'controller'        => 'NotificationController',
            'controller_route'  => 'notification',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'notification.list';
            $data['rows']                   = Notification::where('status', '!=', 3)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $rules = [
                    'title'                 => 'required',
                    'description'           => 'required',
                    'to_users'              => 'required',
                ];
                if($this->validate($request, $rules)){
                    $postData = [
                        'title'                     => $request->title,
                        'description'               => $request->description,
                        'to_users'                  => $request->to_users,
                        'users'                     => json_encode($request->users),
                    ];
                    Notification::insert($postData);
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'notification.add-edit';
            $data['row']                    = [];
            $data['allUsers']               = User::select('id', 'first_name', 'last_name')->where('status', '=', 1)->get();
            $data['landlords']              = User::select('id', 'first_name', 'last_name')->where('status', '=', 1)->where('type', '=', 1)->get();
            $data['tenants']                = User::select('id', 'first_name', 'last_name')->where('status', '=', 1)->where('type', '=', 2)->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'notification.add-edit';
            $data['row']                    = Notification::where($this->data['primary_key'], '=', $id)->first();
            $data['allUsers']               = User::select('id', 'first_name', 'last_name')->where('status', '=', 1)->get();
            $data['landlords']              = User::select('id', 'first_name', 'last_name')->where('status', '=', 1)->where('type', '=', 1)->get();
            $data['tenants']                = User::select('id', 'first_name', 'last_name')->where('status', '=', 1)->where('type', '=', 2)->get();
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'title'                 => 'required',
                    'description'           => 'required',
                    'to_users'              => 'required',
                ];
                if($this->validate($request, $rules)){
                    $postData = [
                        'title'                     => $request->title,
                        'description'               => $request->description,
                        'to_users'                  => $request->to_users,
                        'users'                     => json_encode($request->users),
                    ];
                    $update = Notification::where($this->data['primary_key'],'=',$id)->update($postData);
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
            Notification::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Notification::find($id);
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
    public function send(Request $request, $id)
    {
        $id             = Helper::decoded($id);
        $model          = Notification::find($id);
        $model->is_send = 1;
        $model->save();
        return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Send Successfully !!!');
    }
    public function getUser(Request $request)
    {
        $apiStatus          = TRUE;
        $apiMessage         = 'Data Available !!!';
        $apiResponse        = [];
        $apiExtraField      = '';
        $apiExtraData       = '';
        $postData           = $request->all();
        $user_type          = $postData['user_type'];
        if($user_type == 0){
            $users                  = User::select('id', 'first_name', 'last_name')->where('status', '=', 1)->get();
        } elseif($user_type == 1){
            $users                  = User::select('id', 'first_name', 'last_name')->where('status', '=', 1)->where('type', '=', 1)->get();
        } elseif($user_type == 2){
            $users                  = Subscriber::select('id', 'email')->where('status', '=', 1)->get();
        }
        /* industry segment dropdown */
            $user_selects                    = [];
            if($users){
                foreach($users as $user){
                    if($user_type != 2){
                        $user_selects[]          = [
                            'id'    => $user->id,
                            'label' => $user->first_name.' '.$user->last_name,
                        ];
                    } else {
                        $user_selects[]          = [
                            'id'    => $user->id,
                            'label' => $user->email,
                        ];
                    }
                }
            }
        /* industry segment dropdown */
        $apiResponse = [
            'user_selects'           => $user_selects
        ];
        http_response_code(200);
        $apiExtraField      = 'response_code';
        $apiExtraData       = http_response_code();
        $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
    }
}
