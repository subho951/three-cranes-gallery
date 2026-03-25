<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\CancelOrderReason;
use App\Models\EmailLog;
use Auth;
use Session;
use Helper;
use Hash;
use Dompdf\Dompdf;
use Dompdf\Options;
class OrderController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Orders',
            'controller'        => 'OrderController',
            'controller_route'  => 'orders',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list($status, $is_cancel_request){
            $status                         = Helper::decoded($status);
            $is_cancel_request              = Helper::decoded($is_cancel_request);
            if($status == 1 && $is_cancel_request == 0){
                $statusName = 'New';
            } elseif($status == 2 && $is_cancel_request == 0){
                $statusName = 'Processing';
            } elseif($status == 3 && $is_cancel_request == 0){
                $statusName = 'Incomplete';
            } elseif($status == 4 && $is_cancel_request == 0){
                $statusName = 'Shipped';
            } elseif($status == 5 && $is_cancel_request == 0){
                $statusName = 'Complete';
            } elseif($status == 6 && $is_cancel_request == 0){
                $statusName = 'Rejected';
            } elseif($status == 1 && $is_cancel_request == 1){
                $statusName = 'Cancelled';
            } elseif($status == 7 && $is_cancel_request == 1){
                $statusName = 'Cancelled';
            }
            $data['module']                 = $this->data;
            $data['status']                 = $status;
            $data['statusName']             = $statusName;
            $title                          = $this->data['title'].' List : '.$statusName;
            $page_name                      = 'orders.list';
            if($is_cancel_request == 0){
                $data['rows']                   = Order::select('id', 'order_no', 'cust_fname', 'cust_lname', 'cust_phone', 'cust_email', 'order_date', 'order_time', 'checkout_type', 'net_amt', 'payment_status', 'payment_mode', 'payment_txn_no', 'payment_date_time', 'status', 'is_cancel_request', 'cancel_approve_reject_timestamp', 'cancel_request_timestamp', 'cancel_order_reason', 'cancel_order_description', 'invoice_pdf', 'tracking_number')->where('status', '=', $status)->where('is_cancel_request', '=', $is_cancel_request)->orderBy('id', 'DESC')->get();
            } else {
                $data['rows']                   = Order::select('id', 'order_no', 'cust_fname', 'cust_lname', 'cust_phone', 'cust_email', 'order_date', 'order_time', 'checkout_type', 'net_amt', 'payment_status', 'payment_mode', 'payment_txn_no', 'payment_date_time', 'status', 'is_cancel_request', 'cancel_approve_reject_timestamp', 'cancel_request_timestamp', 'cancel_order_reason', 'cancel_order_description', 'invoice_pdf', 'tracking_number')->where('status', '=', $status)->orderBy('id', 'DESC')->get();
            }
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* delete */
        public function delete(Request $request, $id){
            $id                             = Helper::decoded($id);
            $fields = [
                'status'             => 3
            ];
            Order::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id, $approveStatus){
            $id                             = Helper::decoded($id);
            $getOrder                       = Order::where('id', '=', $id)->first();
            $approveStatus                  = Helper::decoded($approveStatus);
            $model                          = Order::find($id);
            if ($approveStatus == 1)
            {
                $model->status                              = 7;
                $model->cancel_approve_reject_timestamp     = date('Y-m-d H:i:s');
                $model->is_refund                           = 1;
                $model->refund_amount                       = $getOrder->net_amt;
                $model->refund_timestamp                    = date('Y-m-d H:i:s');
                $msg                                        = 'Approved';
            } else {
                $model->status                              = 1;
                $model->cancel_approve_reject_timestamp     = date('Y-m-d H:i:s');
                $msg                                        = 'Rejected';
            }            
            $model->save();
            return redirect('admin/'.$this->data['controller_route'] . "/list/".Helper::encoded(1).'/'.Helper::encoded(1))->with('success_message', $this->data['title'].' Cancel Request '.$msg.' Successfully !!!');
        }
        public function status_update(Request $request, $id, $approveStatus){
            $id                             = Helper::decoded($id);
            $getOrder                       = Order::where('id', '=', $id)->first();
            $approveStatus                  = Helper::decoded($approveStatus);
            $status                         = $request->status;
            
            if($status < 4){
                Order::where('id', '=', $id)->update(['status' => $status]);
            } else {
                // if($request->tracking_number == ''){
                //     return redirect('admin/'.$this->data['controller_route'] . "/list/".Helper::encoded(4).'/'.Helper::encoded(0))->with('error_message', 'Please Update Tracking Number !!!');
                // } else {
                //     Order::where('id', '=', $id)->update(['status' => $status, 'tracking_number' => $request->tracking_number]);
                // }
                Order::where('id', '=', $id)->update(['status' => $status, 'tracking_number' => $request->tracking_number]);
            }
            
            if($status == 1){
                $statusName = 'New';
            } elseif($status == 2){
                $statusName = 'Processing';
            } elseif($status == 3){
                $statusName = 'Incomplete';
            } elseif($status == 4){
                $statusName = 'Shipped';
            } elseif($status == 5){
                $statusName = 'Complete';
            } elseif($status == 6){
                $statusName = 'Rejected';
            }
            $cust_email                     = $getOrder->cust_email;
            $generalSetting                 = GeneralSetting::find('1');
            /* email functionality */
                $mailData['getOrder']       = Order::where('id', '=', $id)->first();;
                $mailData['mailHeader']     = 'Order Status - Your Order with '.$generalSetting->site_name.' ['.$mailData['getOrder']->order_no.'] has been successfully updated into '.$statusName;
                $message                    = view('email-templates.order-status-update', $mailData);
                $generalSetting             = GeneralSetting::find('1');
                $subject                    = 'Order Status - Your Order with '.$generalSetting->site_name.' ['.$mailData['getOrder']->order_no.'] has been successfully updated into '.$statusName;
                $this->sendMail($cust_email, $subject, $message);
            /* email functionality */
            /* email log save */
                $postData2 = [
                    'name'                  => $getOrder->cust_fname.' '.$getOrder->cust_lname,
                    'email'                 => $cust_email,
                    'subject'               => $subject,
                    'message'               => $message
                ];
                EmailLog::insertGetId($postData2);
            /* email log save */
            return redirect('admin/'.$this->data['controller_route'] . "/list/".Helper::encoded($status).'/'.Helper::encoded(0))->with('success_message', $this->data['title'].' '.$statusName.' Status Updated Successfully !!!');
        }
    /* change status */
    public function printInvoice(Request $request, $id){
        $uId                            = session('user_id');
        $id                             = Helper::decoded($id);
        $data['getOrderDetail']         = Order::where('id', '=', $id)->first();

        /* generate inspection pdf & save it to directory */
            $enquiry_no                     = (($data['getOrderDetail'])?$data['getOrderDetail']->order_no:'');
            $data['generalSetting']         = GeneralSetting::find('1');
            $subject                        = $data['generalSetting']->site_name . ' Invoice' . $enquiry_no;
            $message                        = view('email-templates.print-invoice',$data);                        
            // echo $message;die;
            // Initialize Dompdf with options
            $options    = new Options();
            $options->set('defaultFont', 'Courier');
            $dompdf     = new Dompdf($options);
            // Load HTML content
            $html       = $message;
            // You can also load HTML content from a file or URL
            $dompdf->loadHtml($html);
            // (Optional) Set up the paper size and orientation
            $dompdf->setPaper('A4', 'portrait');
            // Render the HTML as PDF
            $dompdf->render();
            // Get the generated PDF content
            $output = $dompdf->output();
            // Output the generated PDF to browser
            // $dompdf->stream("document.pdf", array("Attachment" => false));die;

            // Define the path where the PDF will be saved
            $filename   = $enquiry_no.'.pdf';
            $pdfFilePath = 'public/uploads/orders/' . $filename;

            // Save the PDF to a file
            file_put_contents($pdfFilePath, $output);
            Order::where('id', '=', $id)->update(['invoice_pdf' => $filename]);
            // echo "PDF file has been generated and saved at: " . $pdfFilePath;die;
        /* generate inspection pdf & save it to directory */
        
        $page_name                      = 'print-invoice';
        return view('admin.maincontents.orders.'.$page_name, $data);
    }
    public function orderDetails(Request $request, $id){
        $uId                            = session('user_id');
        $id                             = Helper::decoded($id);
        $data['getOrderDetail']         = Order::where('id', '=', $id)->first();
        
        $data['module']                 = $this->data;
        $title                          = 'Details Of : '.(($data['getOrderDetail'])?$data['getOrderDetail']->order_no:'');
        $page_name                      = 'orders.order-details';
        echo $this->admin_after_login_layout($title,$page_name,$data);
    }
}
