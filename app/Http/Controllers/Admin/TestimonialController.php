<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Testimonial;
use Auth;
use Session;
use Helper;
use Hash;
class TestimonialController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Testimonial',
            'controller'        => 'TestimonialController',
            'controller_route'  => 'testimonial',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'testimonial.list';
            $data['rows']                   = Testimonial::where('status', '!=', 3)->orderBy('id', 'DESC')->get();
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
                    'name'             => 'required',
                    'review'           => 'required',
                    'rate'             => 'required',
                    'company_name'     => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* user image */
                        $imageFile      = $request->file('image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('image', $imageName, 'testimonial', 'image');
                            if($uploadedFile['status']){
                                $image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            return redirect()->back()->with(['error_message' => 'Please Upload User Image !!!']);
                        }
                    /* user image */
                    /* company logo */
                        $imageFile      = $request->file('company_logo');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('company_logo', $imageName, 'testimonial', 'image');
                            if($uploadedFile['status']){
                                $company_logo = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            return redirect()->back()->with(['error_message' => 'Please Upload Company logo !!!']);
                        }
                    /* company logo */
                    $fields = [
                        'name'                  => $postData['name'],
                        'review'                => $postData['review'],
                        'rate'                  => $postData['rate'],
                        'image'                 => $image,
                        'company_name'          => $postData['company_name'],
                        'company_logo'          => $company_logo,
                        'designation'           => $postData['designation'],
                    ];
                    Testimonial::insert($fields);
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'testimonial.add-edit';
            $data['row']                    = [];
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'testimonial.add-edit';
            $data['row']                    = Testimonial::where($this->data['primary_key'], '=', $id)->first();
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'             => 'required',
                    'review'           => 'required',
                    'rate'             => 'required',
                    'company_name'     => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* user image */
                        $imageFile      = $request->file('image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('image', $imageName, 'testimonial', 'image');
                            if($uploadedFile['status']){
                                $image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $image = $data['row']->image;
                        }
                    /* user image */
                    /* company logo */
                        $imageFile      = $request->file('company_logo');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('company_logo', $imageName, 'testimonial', 'image');
                            if($uploadedFile['status']){
                                $company_logo = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $company_logo = $data['row']->company_logo;
                        }
                    /* company logo */
                    $fields = [
                        'name'                  => $postData['name'],
                        'review'                => $postData['review'],
                        'rate'                  => $postData['rate'],
                        'image'                 => $image,
                        'company_name'          => $postData['company_name'],
                        'company_logo'          => $company_logo,
                        'designation'           => $postData['designation'],
                    ];
                    Testimonial::where($this->data['primary_key'], '=', $id)->update($fields);
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
            Testimonial::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Testimonial::find($id);
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
