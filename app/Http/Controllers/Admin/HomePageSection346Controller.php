<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\HomePage;
use Auth;
use Session;
use Helper;
use Hash;
class HomePageSection346Controller extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Home Page Sections',
            'controller'        => 'HomePageSection346Controller',
            'controller_route'  => 'home-page-section346',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(Request $request){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'home-page-section346.list';
            $data['row']                   = HomePage::where('id', '=', 1)->first();
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    // 'sec2_title'                      => 'required',
                    // 'sec3_title'                      => 'required',
                    'sec4_title'                      => 'required',
                    // 'sec5_title'                      => 'required',
                    // 'sec6_title'                      => 'required',
                    // 'sec7_title'                      => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* section 3 image */
                        $imageFile      = $request->file('sec3_image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('sec3_image', $imageName, 'home_page', 'image');
                            if($uploadedFile['status']){
                                $sec3_image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $sec3_image = '';
                        }
                    /* section 3 image */
                    $fields = [
                        'sec2_title'                    => $postData['sec2_title'],
                        'sec2_description'              => $postData['sec2_description'],
                        'sec3_title'                    => $postData['sec3_title'],
                        'sec3_description'              => $postData['sec3_description'],
                        'sec3_image'                    => $sec3_image,
                        'sec4_title'                    => $postData['sec4_title'],
                        'sec4_description'              => $postData['sec4_description'],
                        'sec5_title'                    => $postData['sec5_title'],
                        'sec5_description'              => $postData['sec5_description'],
                        'sec6_title'                    => $postData['sec6_title'],
                        'sec6_description'              => $postData['sec6_description'],
                        'sec7_title'                    => $postData['sec7_title'],
                        'sec7_description'              => $postData['sec7_description'],
                    ];
                    HomePage::where($this->data['primary_key'], '=', 1)->update($fields);
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Updated Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
}
