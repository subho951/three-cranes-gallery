<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Services\OpenAiAuth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Http\Request;
use PHPExperts\RESTSpeaker\RESTSpeaker;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Country;
use App\Models\GeneralSetting;
use App\Models\Category;
use App\Models\EmailLog;
use App\Models\Page;
use App\Models\Testimonial;
use App\Models\Banner;
use App\Models\HomePage;
use App\Models\HomePage2Section;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Enquiry;
use App\Models\UserActivity;
use App\Models\UserLocation;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Coupon;
use App\Models\CancelOrderReason;
use App\Models\UserWishlist;
use App\Models\UserReview;

use Auth;
use Session;
use Helper;
use Hash;
use stripe;
use DB;
use Dompdf\Dompdf;
use Dompdf\Options;

class PayPalController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view('paypal');
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function payment(Request $request, $order_id)
    {
        $id             = Helper::decoded($order_id);
        $getOrder       = Order::where('id', '=', $id)->first();
        $provider       = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken    = $provider->getAccessToken();
  
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => url('paypal/payment/success/'.Helper::encoded($id)),
                "cancel_url" => url('paypal/payment/cancel/'.Helper::encoded($id)),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => round($getOrder->net_amt)
                    ]
                ]
            ]
        ]);
        
        if (isset($response['id']) && $response['id'] != null) {
  
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
  
            return redirect(url('paypal/payment/success/'.Helper::encoded($id)))
                ->with('error', 'Something went wrong.');
  
        } else {
            return redirect(url('paypal/payment/success/'.Helper::encoded($id)))
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }    
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function paymentCancel($order_id)
    {
        $id             = Helper::decoded($order_id);
        $getOrder       = Order::where('id', '=', $id)->first();
        // return redirect()
        //       ->route('paypal')
        //       ->with('error', $response['message'] ?? 'You have canceled the transaction.');
        return redirect(url('order-failure/'.Helper::encoded($id)))->with('error_message', 'Payment Failed !!!');
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function paymentSuccess(Request $request, $order_id)
    {
        $id             = Helper::decoded($order_id);
        $getOrder       = Order::where('id', '=', $id)->first();

        $provider       = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response       = $provider->capturePaymentOrder($request['token']);
        
        // Helper::pr($response,0);
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {

            $userSubscriptionData = [
                'payment_status'                => 1,
                'payment_txn_no'                => $response['purchase_units'][0]['payments']['captures'][0]['id'],
                'payment_date_time'             => date('Y-m-d H:i:s'),
                'payment_gateway_id'            => $response['id'],
                'customer_id'                   => $response['payment_source']['paypal']['account_id'],
                'customer_card_id'              => '',
                'currency'                      => $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'],
                'particulars'                   => 'Payment '.$getOrder->net_amt.' for order place on '.date('Y-m-d H:i:s').' by paypal',
                'card_last_4_digits'            => '',
                'expiry_month'                  => '',
                'expiry_year'                   => '',
            ];
            // Helper::pr($userSubscriptionData);
            Order::where('id', '=', $id)->update($userSubscriptionData);
            OrderDetail::where('order_id', '=', $id)->update(['is_cart' => 0]);
            $order_id = $id;
            $getOrder   = DB::table('orders')
                // ->join('users', 'orders.cust_id', '=', 'users.id')
                ->select('orders.*')
                ->where('orders.id', '=', $order_id)
                ->first();
            /* generate inspection pdf & save it to directory */
                $enquiry_no                     = (($getOrder)?$getOrder->order_no:'');
                $data['generalSetting']         = GeneralSetting::find('1');
                $data['getOrderDetail']         = $getOrder;
                $subject                        = $data['generalSetting']->site_name . ' Invoice' . $enquiry_no;
                $message                        = view('email-templates.print-invoice',$data);
                $options    = new Options();
                $options->set('defaultFont', 'Courier');
                $dompdf     = new Dompdf($options);
                $html       = $message;
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $output = $dompdf->output();
                // $dompdf->stream("document.pdf", array("Attachment" => false));die;
                $filename   = $enquiry_no.'.pdf';
                $pdfFilePath = 'public/uploads/orders/' . $filename;
                file_put_contents($pdfFilePath, $output);
                Order::where('id', '=', $order_id)->update(['invoice_pdf' => $filename]);
            /* generate inspection pdf & save it to directory */

            /* email functionality */
                $mailData['getOrder']       = Order::where('id', '=', $id)->first();
                $message                    = view('email-templates.order-place', $mailData);                    
                $generalSetting             = GeneralSetting::find('1');
                $subject                    = 'Order Confirmation - Your Order with '.$generalSetting->site_name.' ['.$mailData['getOrder']->order_no.'] has been successfully placed!';
                $this->sendMail($generalSetting->system_email, $subject, $message);
                $this->sendMail($mailData['getOrder']->b_email, $subject, $message);
            /* email functionality */
            /* email log save */
                $postData2 = [
                    'name'                  => $mailData['getOrder']->b_fname.' '.$mailData['getOrder']->b_lname,
                    'email'                 => $mailData['getOrder']->b_email,
                    'subject'               => $subject,
                    'message'               => $message
                ];
                EmailLog::insertGetId($postData2);
            /* email log save */

            return redirect(url('order-success/'.Helper::encoded($id)))->with('success_message', 'Order Placed & Payment Completed Successfully !!!');

            // return redirect()
            //     ->route('paypal')
            //     ->with('success', 'Transaction complete.');
        } else {
            return redirect(url('order-failure/'.Helper::encoded($id)))->with('error_message', 'Payment Failed !!!');
            // return redirect()
            //     ->route('paypal')
            //     ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }
}
