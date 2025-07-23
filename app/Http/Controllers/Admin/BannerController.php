<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Banner;
use Auth;
use Session;
use Helper;
use Hash;
class BannerController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Banner',
            'controller'        => 'BannerController',
            'controller_route'  => 'banner',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'banner.list';
            $data['rows']                   = Banner::where('status', '!=', 3)->orderBy('id', 'DESC')->get();
            // Helper::pr($data['rows']);
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'section'                   => 'required',
                    // 'banner_link'               => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* banner image */
                        $imageFile      = $request->file('banner_image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('banner_image', $imageName, 'banner', 'image');
                            if($uploadedFile['status']){
                                $banner_image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            return redirect()->back()->with(['error_message' => 'Please Upload Banner Image !!!']);
                        }
                    /* banner image */
                    $fields = [
                        // 'heading1'              => $postData['heading1'],
                        // 'heading2'              => $postData['heading2'],
                        'section'               => $postData['section'],
                        'banner_text'           => $postData['banner_text'],
                        'banner_text2'          => $postData['banner_text2'],
                        'banner_link'           => $postData['banner_link'],
                        'banner_image'          => $banner_image
                    ];
                    Banner::insert($fields);
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'banner.add-edit';
            $data['row']                    = [];
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'banner.add-edit';
            $data['row']                    = Banner::where($this->data['primary_key'], '=', $id)->first();
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'section'                   => 'required',
                    // 'banner_link'               => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* banner image */
                        $imageFile      = $request->file('banner_image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('banner_image', $imageName, 'banner', 'image');
                            if($uploadedFile['status']){
                                $banner_image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $banner_image = $data['row']->banner_image;
                        }
                    /* banner image */
                    $fields = [
                        // 'heading1'              => $postData['heading1'],
                        // 'heading2'              => $postData['heading2'],
                        'section'               => $postData['section'],
                        'banner_text'           => $postData['banner_text'],
                        'banner_text2'          => $postData['banner_text2'],
                        'banner_link'           => $postData['banner_link'],
                        'banner_image'          => $banner_image
                    ];
                    Banner::where($this->data['primary_key'], '=', $id)->update($fields);
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
            Banner::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Banner::find($id);
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
