<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Product;
use App\Models\Order;
use Auth;
use Session;
use Helper;
use Hash;

class ReportController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Reports',
            'controller'        => 'ReportController',
            'controller_route'  => 'unit',
            'primary_key'       => 'id',
        );
    }
    /* sales report */
        public function salesReport(Request $request){
            $data['module']                 = $this->data;
            $title                          = 'Sales ' . $this->data['title'];
            $data['products']               = Product::select('id', 'name')->where('status', '=', 1)->orderBy('name', 'ASC')->get();
            if($request->isMethod('post')){
                $postData                       = $request->all();
                $from_date                      = $postData['from_date'];
                $to_date                        = $postData['to_date'];
                $product_id                     = $postData['product_id'];
                if($product_id == 'all'){
                    $data['is_search']              = 1;
                    $data['fdate']                  = $from_date;
                    $data['tdate']                  = $to_date;
                    $data['product_id']             = $product_id;
                    $data['rows']                   = Order::select('id', 'order_no', 'cust_fname', 'cust_lname', 'cust_phone', 'cust_email', 'order_date', 'order_time', 'checkout_type', 'net_amt', 'payment_status', 'payment_mode', 'payment_txn_no', 'payment_date_time', 'status', 'is_cancel_request', 'cancel_approve_reject_timestamp', 'cancel_request_timestamp', 'cancel_order_reason', 'cancel_order_description')->where('order_date', '>=', $from_date)->where('order_date', '<=', $to_date)->orderBy('id', 'DESC')->get();
                } else {
                    $data['is_search']              = 1;
                    $data['fdate']                  = $from_date;
                    $data['tdate']                  = $to_date;
                    $data['product_id']             = $product_id;
                    $data['rows']                   = Order::select('orders.*')->join('order_details', 'order_details.order_id', '=', 'orders.id')->where('orders.order_date', '>=', $from_date)->where('orders.order_date', '<=', $to_date)->where('order_details.product_id', '=', $product_id)->orderBy('orders.id', 'DESC')->get();
                }
            } else{
                $currentDate                    = date('Y-m-d');
                $data['is_search']              = 0;
                $data['fdate']                  = $currentDate;
                $data['tdate']                  = $currentDate;
                $data['product_id']             = 'all';
                $data['rows']                   = Order::select('id', 'order_no', 'cust_fname', 'cust_lname', 'cust_phone', 'cust_email', 'order_date', 'order_time', 'checkout_type', 'net_amt', 'payment_status', 'payment_mode', 'payment_txn_no', 'payment_date_time', 'status', 'is_cancel_request', 'cancel_approve_reject_timestamp', 'cancel_request_timestamp', 'cancel_order_reason', 'cancel_order_description')->where('order_date', '>=', $currentDate)->where('order_date', '<=', $currentDate)->orderBy('id', 'DESC')->get();
            }
            
            $page_name                      = 'reports.sales-report';
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* sales report */
    
}
