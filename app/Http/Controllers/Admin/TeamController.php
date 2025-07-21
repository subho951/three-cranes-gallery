<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Team;
use Auth;
use Session;
use Helper;
use Hash;

class TeamController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Team',
            'controller'        => 'TeamController',
            'controller_route'  => 'team',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'team.list';
            $data['rows']                   = Team::where('status', '!=', 3)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'                  => 'required',
                    'image'                 => 'required',
                    'designation'           => 'required',
                    'qualification'         => 'required',
                    'experience'            => 'required',
                    'bio'                   => 'required',
                    'thought'               => 'required',
                    'is_owner'              => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* image */
                        $imageFile      = $request->file('image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('image', $imageName, 'team', 'image');
                            if($uploadedFile['status']){
                                $image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            return redirect()->back()->with(['error_message' => 'Please Upload Image !!!']);
                        }
                    /* image */
                    Team::where('status', '!=', 3)->update(['is_owner' => 0]);
                    $fields = [
                        'name'              => $postData['name'],
                        'designation'       => $postData['designation'],
                        'qualification'     => $postData['qualification'],
                        'experience'        => $postData['experience'],
                        'bio'               => $postData['bio'],
                        'thought'           => $postData['thought'],
                        'is_owner'          => $postData['is_owner'],
                        'image'             => $image,
                    ];
                    Team::insert($fields);
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'team.add-edit';
            $data['row']                    = [];
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'team.add-edit';
            $data['row']                    = Team::where($this->data['primary_key'], '=', $id)->first();

            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'                  => 'required',
                    'designation'           => 'required',
                    'qualification'         => 'required',
                    'experience'            => 'required',
                    'bio'                   => 'required',
                    'thought'               => 'required',
                    'is_owner'              => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* image */
                        $imageFile      = $request->file('image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('image', $imageName, 'team', 'image');
                            if($uploadedFile['status']){
                                $image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $image = $data['row']->image;
                        }
                    /* image */
                    Team::where('status', '!=', 3)->update(['is_owner' => 0]);
                    $fields = [
                        'name'              => $postData['name'],
                        'designation'       => $postData['designation'],
                        'qualification'     => $postData['qualification'],
                        'experience'        => $postData['experience'],
                        'bio'               => $postData['bio'],
                        'thought'           => $postData['thought'],
                        'is_owner'          => $postData['is_owner'],
                        'image'             => $image,
                        'updated_at'        => date('Y-m-d H:i:s')
                    ];
                    Team::where($this->data['primary_key'], '=', $id)->update($fields);
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
            Team::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Team::find($id);
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
