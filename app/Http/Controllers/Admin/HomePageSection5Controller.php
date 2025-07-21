<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\HomePage5Section;
use Auth;
use Session;
use Helper;
use Hash;
class HomePageSection5Controller extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Home Page Fifth Section',
            'controller'        => 'HomePageSection5Controller',
            'controller_route'  => 'home-page-section5',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'home-page-section5.list';
            $sessionType                    = Session::get('type');
            $sessionHotelId                 = Session::get('hotel_id');
            $data['rows']                   = HomePage5Section::where('status', '!=', 3)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'                      => 'required',
                    'icon'                      => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* hotel image */
                        $imageFile      = $request->file('icon');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('icon', $imageName, 'home_page', 'image');
                            if($uploadedFile['status']){
                                $icon = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            return redirect()->back()->with(['error_message' => 'Please Upload Hotel Cover Image !!!']);
                        }
                    /* hotel image */
                    $fields = [
                        'name'                  => strtoupper($postData['name']),
                        'short_description'     => $postData['short_description'],
                        'long_description'      => $postData['long_description'],
                        'icon'                  => $icon,
                    ];
                    HomePage5Section::insert($fields);
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'home-page-section5.add-edit';
            $data['row']                    = [];
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'home-page-section5.add-edit';
            $data['row']                    = HomePage5Section::where($this->data['primary_key'], '=', $id)->first();
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'                      => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* hotel image */
                        $imageFile      = $request->file('icon');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('icon', $imageName, 'home_page', 'image');
                            if($uploadedFile['status']){
                                $icon = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $icon = $data['row']->icon;
                        }
                    /* hotel image */
                    $fields = [
                        'name'                  => strtoupper($postData['name']),
                        'short_description'     => $postData['short_description'],
                        'long_description'      => $postData['long_description'],
                        'icon'                  => $icon,
                    ];
                    HomePage5Section::where($this->data['primary_key'], '=', $id)->update($fields);
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
            HomePage5Section::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = HomePage5Section::find($id);
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
