<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\HomePage2Section;
use Auth;
use Session;
use Helper;
use Hash;
class HomePageSection2Controller extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Home Page Third & Fifth Section',
            'controller'        => 'HomePageSection2Controller',
            'controller_route'  => 'home-page-section2',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'home-page-section2.list';
            $sessionType                    = Session::get('type');
            $sessionHotelId                 = Session::get('hotel_id');
            $data['rows']                   = HomePage2Section::where('status', '!=', 3)->orderBy('id', 'DESC')->get();
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
                    'section'                   => 'required',
                    'size'                      => 'required',
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
                        'name'                  => $postData['name'],
                        'icon'                  => $icon,
                        'short_description'     => $postData['short_description'],
                        'section2_link'         => $postData['section2_link'],
                        'section'               => $postData['section'],
                        'size'                  => $postData['size'],
                    ];
                    HomePage2Section::insert($fields);
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'home-page-section2.add-edit';
            $data['row']                    = [];
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'home-page-section2.add-edit';
            $data['row']                    = HomePage2Section::where($this->data['primary_key'], '=', $id)->first();
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'                      => 'required',
                    'section'                   => 'required',
                    'size'                      => 'required',
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
                        'name'                  => $postData['name'],
                        'icon'                  => $icon,
                        'short_description'     => $postData['short_description'],
                        'section2_link'         => $postData['section2_link'],
                        'section'               => $postData['section'],
                        'size'                  => $postData['size'],
                    ];
                    HomePage2Section::where($this->data['primary_key'], '=', $id)->update($fields);
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
            HomePage2Section::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = HomePage2Section::find($id);
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
