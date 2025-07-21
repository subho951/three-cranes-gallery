<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Models\UserLocation;
use App\Models\Order;
use App\Models\UserWishlist;
use App\Models\UserReview;
use App\Models\Product;
use App\Models\EmailLog;
use Auth;
use Session;
use Helper;
use Hash;

class ReviewController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Reviws',
            'controller'        => 'ReviewController',
            'controller_route'  => 'reviews',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'review.list';
            $data['rows']                   = UserReview::orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* delete */
        public function delete(Request $request, $id){
            $id                             = Helper::decoded($id);
            $fields = [
                'status'             => 3
            ];
            UserReview::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id, $status){
            $id                                 = Helper::decoded($id);
            $status                             = Helper::decoded($status);
            $model                              = UserReview::find($id);
            $model->status                      = $status;
            $model->approve_reject_timestamp    = date('Y-m-d H:i:s');
            if ($status == 1)
            {
                $msg            = 'Approved';
            } else {
                $msg            = 'Rejected';
            }            
            $model->save();

            $uId                                = $model->user_id;
            $getUser                            = User::where('id', '=', $uId)->first();
            $product_id                         = $model->product_id;
            $getProduct                         = Product::where('id', '=', $product_id)->first();
            /* email functionality */
                $mailData['getProduct']     = $getProduct;
                $mailData['getReview']      = $model;
                $mailData['status']         = $status;
                if ($status == 1){
                    $mailData['mailHeader']     = 'Review successfully approved on '.$mailData['getProduct']->name;
                } else {
                    $mailData['mailHeader']     = 'Review successfully rejected on '.$mailData['getProduct']->name;
                }
                
                $message                    = view('email-templates.review-action', $mailData);
                $generalSetting             = GeneralSetting::find('1');
                $subject                    = $generalSetting->site_name.' :: Review successfully '.(($status == 1)?'approved':'rejected').' on '.$mailData['getProduct']->name;
                // $this->sendMail($getUser->email, $subject, $message);
                $this->sendMail($getUser->email, $subject, $message);
            /* email functionality */
            /* email log save */
                $postData2 = [
                    'name'                  => $getUser->first_name.' '.$getUser->last_name,
                    'email'                 => $getUser->email,
                    'subject'               => $subject,
                    'message'               => $message
                ];
                EmailLog::insertGetId($postData2);
            /* email log save */

            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' '.$msg.' Successfully !!!');
        }
    /* change status */
}
