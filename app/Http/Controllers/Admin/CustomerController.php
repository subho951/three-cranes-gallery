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
use Auth;
use Session;
use Helper;
use Hash;

class CustomerController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Customers',
            'controller'        => 'CustomerController',
            'controller_route'  => 'customer',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'customer.list';
            $data['rows']                   = User::where('status', '!=', 3)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
        public function viewBillingAddress($id){
            $id                             = Helper::decoded($id);
            $getCustomer                    = User::where('id', '=', $id)->first();
            $data['module']                 = $this->data;
            $title                          = (( $getCustomer)? $getCustomer->display_name:'').' : Billing Address';
            $page_name                      = 'customer.billing-address';
            $data['rows']                   = UserLocation::where('user_id', '=', $id)->where('status', '=', 1)->where('type', '=', 'BILLING')->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
        public function viewShippingAddress($id){
            $id                             = Helper::decoded($id);
            $getCustomer                    = User::where('id', '=', $id)->first();
            $data['module']                 = $this->data;
            $title                          = (( $getCustomer)? $getCustomer->display_name:'').' : Shipping Address';
            $page_name                      = 'customer.shipping-address';
            $data['rows']                   = UserLocation::where('user_id', '=', $id)->where('status', '=', 1)->where('type', '=', 'SHIPPING')->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
        public function viewWishlist($id){
            $id                             = Helper::decoded($id);
            $getCustomer                    = User::where('id', '=', $id)->first();
            $data['module']                 = $this->data;
            $title                          = (( $getCustomer)? $getCustomer->display_name:'').' : Wishlists';
            $page_name                      = 'customer.wishlist';
            $data['rows']                   = UserWishlist::where('user_id', '=', $id)->where('status', '=', 1)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
        public function viewOrders($id){
            $id                             = Helper::decoded($id);
            $getCustomer                    = User::where('id', '=', $id)->first();
            $data['module']                 = $this->data;
            $title                          = (( $getCustomer)? $getCustomer->display_name:'').' : Orders';
            $page_name                      = 'customer.orders';
            $data['getCustOrders1']         = Order::where('cust_id', '=', $id)->where('status', '=', 1)->where('is_cancel_request', '=', 0)->orderBy('id', 'DESC')->get();
            $data['getCustOrders2']         = Order::where('cust_id', '=', $id)->where('status', '=', 2)->where('is_cancel_request', '=', 0)->orderBy('id', 'DESC')->get();
            $data['getCustOrders3']         = Order::where('cust_id', '=', $id)->where('status', '=', 3)->where('is_cancel_request', '=', 0)->orderBy('id', 'DESC')->get();
            $data['getCustOrders4']         = Order::where('cust_id', '=', $id)->where('status', '=', 4)->where('is_cancel_request', '=', 0)->orderBy('id', 'DESC')->get();
            $data['getCustOrders5']         = Order::where('cust_id', '=', $id)->where('status', '=', 5)->where('is_cancel_request', '=', 0)->orderBy('id', 'DESC')->get();
            $data['getCustOrders6']         = Order::where('cust_id', '=', $id)->where('status', '=', 6)->where('is_cancel_request', '=', 0)->orderBy('id', 'DESC')->get();
            $data['getCustOrders7']         = Order::where('cust_id', '=', $id)->where('is_cancel_request', '=', 1)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
        public function viewReviews($id){
            $id                             = Helper::decoded($id);
            $getCustomer                    = User::where('id', '=', $id)->first();
            $data['module']                 = $this->data;
            $title                          = (( $getCustomer)? $getCustomer->display_name:'').' : Reviews';
            $page_name                      = 'customer.reviews';
            $data['rows']                   = UserReview::where('user_id', '=', $id)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* delete */
        public function delete(Request $request, $id){
            $id                             = Helper::decoded($id);
            $fields = [
                'status'             => 3
            ];
            User::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = User::find($id);
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
