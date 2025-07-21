<?php
namespace App\Http\Controllers\Admin;

use App\Helpers\Helper as HelpersHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductAttribute;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ShopProduceItem;
use App\Models\ToolsUsed;
use App\Models\Material;
use App\Models\ReturnPolicy;
use App\Models\ProductVariation;
use App\Models\VariationAttribute;

use Auth;
use Session;
use Helper;
use Hash;
use DB;
class ProductController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Product',
            'controller'        => 'ProductController',
            'controller_route'  => 'product',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(Request $request){
            $data['module']                 = $this->data;
            $title                          = 'All ' . $this->data['title'].' List';
            $page_name                      = 'product.list';
            $data['rows']                   = DB::table('products')
                                                    ->join('categories', 'products.sub_category', '=', 'categories.id')
                                                    ->select('products.*', 'categories.category_name as sub_category_name')
                                                    ->where('products.status', '!=', 3)
                                                    ->orderBy('products.id', 'DESC')
                                                    ->paginate(8);
                                                    // ->get();
            $data['total_products']        = DB::table('products')->where('products.status', '!=', 3)->count();

            $generalSetting                 = GeneralSetting::find('1');
            $data['view_type']              = $generalSetting->product_view;
            $data['shop_produce_items']     = ShopProduceItem::select('id', 'name', 'description')->where('status', '=', 1)->get();
            $data['tools_useds']            = ToolsUsed::select('id', 'name', 'description')->where('status', '=', 1)->get();
            $data['subcategories']          = Category::select('id', 'category_name', 'parent_id')->where('status', '=', 1)->where('parent_id', '>', 0)->get();
            $data['all_products']           = DB::table('products')->where('products.status', '!=', 3)->count();
            $data['active_products']        = Product::where('status', '=', 1)->count();
            $data['deactive_products']      = Product::where('status', '=', 0)->count();
            $data['draft_products']         = Product::where('status', '=', 2)->count();
            Session::forget('product_session_data');
            $data['filter_by']              = '';
            $data['filter']                 = -1;
            $data['categoryVal']            = '';

            $categories                     = [];
            $parentCats                     = Category::select('id', 'category_name')->where('status', '=', 1)->where('parent_id', '=', 0)->get();
            if($parentCats){
                foreach($parentCats as $parentCat){
                    $childCats                     = Category::select('id', 'category_name')->where('status', '=', 1)->where('parent_id', '=', $parentCat->id)->get();
                    if($childCats){
                        foreach($childCats as $childCat){
                            $product_count = Product::where('main_category', '=', $parentCat->id)->where('sub_category', '=', $childCat->id)->where('status', '=', 1)->count();
                            $categories[] = [
                                'category_id'       => $childCat->id,
                                'category_name'     => $parentCat->category_name . ' - ' . $childCat->category_name,
                                'product_count'     => $product_count,
                            ];
                        }
                    }
                }
            }
            $data['categories']                 = $categories;

            if($request->isMethod('post')){
                $postData = $request->all();
                // Helper::pr($postData);
                if($postData['mode'] == 'Active'){
                    $product_id = $postData['product_id'];
                    if(!empty($product_id)){
                        for($p=0;$p<count($product_id);$p++){
                            Product::where('id', '=', $product_id[$p])->update(['status' => 1]);
                        }
                    }
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Activated Successfully !!!');
                }
                if($postData['mode'] == 'Deactive'){
                    $product_id = $postData['product_id'];
                    if(!empty($product_id)){
                        for($p=0;$p<count($product_id);$p++){
                            Product::where('id', '=', $product_id[$p])->update(['status' => 0]);
                        }
                    }
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deactivated Successfully !!!');
                }
                if($postData['mode'] == 'Delete'){
                    $product_id = $postData['product_id'];
                    if(!empty($product_id)){
                        for($p=0;$p<count($product_id);$p++){
                            Product::where('id', '=', $product_id[$p])->update(['status' => 3]);
                        }
                    }
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
                }
                if($postData['mode'] == 'Continue'){
                    $product_session_data = [
                        'who_made_it'       => $postData['who_made_it'],
                        'what_is_it'        => $postData['what_is_it'],
                        'manufacture_year'  => $postData['manufacture_year'],
                        'shop_produce_item' => $postData['shop_produce_item'],
                        'tools_used'        => json_encode($postData['tools_used']),
                        'sub_category'      => $postData['sub_category'],
                    ];
                    $request->session()->put('product_session_data', $product_session_data);
                    $sessionData = Session::all();
                    return redirect('admin/'.$this->data['controller_route'] . "/add");
                }
            }
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
        public function productSorting(Request $request){
            $data['module']                 = $this->data;
            $title                          = 'All ' . $this->data['title'].' List';
            $page_name                      = 'product.list';
            $data['rows']                   = DB::table('products')
                                                    ->join('categories', 'products.sub_category', '=', 'categories.id')
                                                    ->select('products.*', 'categories.category_name as sub_category_name')
                                                    ->where('products.status', '!=', 3)
                                                    ->orderBy('products.id', 'DESC')
                                                    ->paginate(8);
                                                    // ->get();
            $generalSetting                 = GeneralSetting::find('1');
            $data['view_type']              = $generalSetting->product_view;
            $data['shop_produce_items']     = ShopProduceItem::select('id', 'name', 'description')->where('status', '=', 1)->get();
            $data['tools_useds']            = ToolsUsed::select('id', 'name', 'description')->where('status', '=', 1)->get();
            $data['subcategories']          = Category::select('id', 'category_name', 'parent_id')->where('status', '=', 1)->where('parent_id', '>', 0)->get();
            Session::forget('product_session_data');

            $data['all_products']           = DB::table('products')->where('products.status', '!=', 3)->count();
            $data['active_products']        = Product::where('status', '=', 1)->count();
            $data['deactive_products']      = Product::where('status', '=', 0)->count();
            $data['draft_products']         = Product::where('status', '=', 2)->count();
            $data['filter_by']              = '';
            $data['filter']                 = -1;
            $data['total_products']        = DB::table('products')->where('products.status', '!=', 3)->count();

            $postData                       = $request->all();
            $filter_by                      = $request->filter_by;

            $data['categoryVal']            = '';

            $categories                     = [];
            $parentCats                     = Category::select('id', 'category_name')->where('status', '=', 1)->where('parent_id', '=', 0)->get();
            if($parentCats){
                foreach($parentCats as $parentCat){
                    $childCats                     = Category::select('id', 'category_name')->where('status', '=', 1)->where('parent_id', '=', $parentCat->id)->get();
                    if($childCats){
                        foreach($childCats as $childCat){
                            $product_count = Product::where('main_category', '=', $parentCat->id)->where('sub_category', '=', $childCat->id)->where('status', '=', 1)->count();
                            $categories[] = [
                                'category_id'       => $childCat->id,
                                'category_name'     => $parentCat->category_name . ' - ' . $childCat->category_name,
                                'product_count'     => $product_count,
                            ];
                        }
                    }
                }
            }
            $data['categories']                 = $categories;

            if($postData['mode'] == 'filter'){
                if($filter_by == ''){
                    return redirect(url('product/list'));
                }
                $filter_by_array                = explode('-', $filter_by);
                $sort_field                     = $filter_by_array[0];
                if($sort_field == 'price'){
                    $orderField = 'base_price';
                } else {
                    $orderField = 'name';
                }
                $orderType                      = $filter_by_array[1];
                $data['rows']                   = DB::table('products')
                                                    ->join('categories', 'products.sub_category', '=', 'categories.id')
                                                    ->select('products.*', 'categories.category_name as sub_category_name')
                                                    ->where('products.status', '!=', 3)
                                                    ->orderBy('products.' . $orderField, $orderType)
                                                    ->paginate(8);
                                                    // ->get();
                $data['filter_by']              = $filter_by;
                $data['filter']                 = -1;
                $data['total_products']         = DB::table('products')->where('products.status', '!=', 3)->count();
            }

            if($request->isMethod('post')){
                $postData = $request->all();
                if($postData['mode'] == 'Active'){
                    $product_id = $postData['product_id'];
                    if(!empty($product_id)){
                        for($p=0;$p<count($product_id);$p++){
                            Product::where('id', '=', $product_id[$p])->update(['status' => 1]);
                        }
                    }
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Activated Successfully !!!');
                }
                if($postData['mode'] == 'Deactive'){
                    $product_id = $postData['product_id'];
                    if(!empty($product_id)){
                        for($p=0;$p<count($product_id);$p++){
                            Product::where('id', '=', $product_id[$p])->update(['status' => 0]);
                        }
                    }
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deactivated Successfully !!!');
                }
                if($postData['mode'] == 'Delete'){
                    $product_id = $postData['product_id'];
                    if(!empty($product_id)){
                        for($p=0;$p<count($product_id);$p++){
                            Product::where('id', '=', $product_id[$p])->update(['status' => 3]);
                        }
                    }
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
                }
                if($postData['mode'] == 'Continue'){
                    $product_session_data = [
                        'who_made_it'       => $postData['who_made_it'],
                        'what_is_it'        => $postData['what_is_it'],
                        'manufacture_year'  => $postData['manufacture_year'],
                        'shop_produce_item' => $postData['shop_produce_item'],
                        'tools_used'        => json_encode($postData['tools_used']),
                        'sub_category'      => $postData['sub_category'],
                    ];
                    $request->session()->put('product_session_data', $product_session_data);
                    $sessionData = Session::all();
                    return redirect('admin/'.$this->data['controller_route'] . "/add");
                }
            }
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
        public function productFilter(Request $request){
            $data['module']                 = $this->data;
            $title                          = 'All ' . $this->data['title'].' List';
            $page_name                      = 'product.list';
            $data['rows']                   = DB::table('products')
                                                    ->join('categories', 'products.sub_category', '=', 'categories.id')
                                                    ->select('products.*', 'categories.category_name as sub_category_name')
                                                    ->where('products.status', '!=', 3)
                                                    ->orderBy('products.id', 'DESC')
                                                    ->paginate(8);
                                                    // ->get();
            $data['total_products']         = DB::table('products')->where('products.status', '!=', 3)->count();

            $generalSetting                 = GeneralSetting::find('1');
            $data['view_type']              = $generalSetting->product_view;
            $data['shop_produce_items']     = ShopProduceItem::select('id', 'name', 'description')->where('status', '=', 1)->get();
            $data['tools_useds']            = ToolsUsed::select('id', 'name', 'description')->where('status', '=', 1)->get();
            $data['subcategories']          = Category::select('id', 'category_name', 'parent_id')->where('status', '=', 1)->where('parent_id', '>', 0)->get();
            Session::forget('product_session_data');

            $data['all_products']           = DB::table('products')->where('products.status', '!=', 3)->count();
            $data['active_products']        = Product::where('status', '=', 1)->count();
            $data['deactive_products']      = Product::where('status', '=', 0)->count();
            $data['draft_products']         = Product::where('status', '=', 2)->count();
            $data['filter_by']              = '';
            $data['filter']                 = -1;

            $data['categoryVal']            = '';

            $categories                     = [];
            $parentCats                     = Category::select('id', 'category_name')->where('status', '=', 1)->where('parent_id', '=', 0)->get();
            if($parentCats){
                foreach($parentCats as $parentCat){
                    $childCats                     = Category::select('id', 'category_name')->where('status', '=', 1)->where('parent_id', '=', $parentCat->id)->get();
                    if($childCats){
                        foreach($childCats as $childCat){
                            $product_count = Product::where('main_category', '=', $parentCat->id)->where('sub_category', '=', $childCat->id)->where('status', '=', 1)->count();
                            $categories[] = [
                                'category_id'       => $childCat->id,
                                'category_name'     => $parentCat->category_name . ' - ' . $childCat->category_name,
                                'product_count'     => $product_count,
                            ];
                        }
                    }
                }
            }
            $data['categories']                 = $categories;

            $postData                       = $request->all();
            $listing_status                      = $request->listing_status;
            if($postData['mode'] == 'filter'){
                if($listing_status == -1){
                    return redirect(url('product/list'));
                }
                $data['rows']                   = DB::table('products')
                                                    ->join('categories', 'products.sub_category', '=', 'categories.id')
                                                    ->select('products.*', 'categories.category_name as sub_category_name')
                                                    ->where('products.status', '=', $listing_status)
                                                    ->orderBy('products.id', 'DESC')
                                                    ->paginate(8);
                                                    // ->get();
                $data['filter']                 = $listing_status;
                $data['filter_by']              = '';
                $data['total_products']         = DB::table('products')->where('products.status', '=', $listing_status)->count();
                if($listing_status == 0){
                    $statusName = 'Deactive';
                } elseif($listing_status == 1){
                    $statusName = 'Active';
                } elseif($listing_status == 2){
                    $statusName = 'Draft';
                }
                $title                          = $statusName . ' ' . $this->data['title'].' List';
            }

            if($request->isMethod('post')){
                $postData = $request->all();
                if($postData['mode'] == 'Active'){
                    $product_id = $postData['product_id'];
                    if(!empty($product_id)){
                        for($p=0;$p<count($product_id);$p++){
                            Product::where('id', '=', $product_id[$p])->update(['status' => 1]);
                        }
                    }
                    $title                          = 'Active ' . $this->data['title'].' List';
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Activated Successfully !!!');
                }
                if($postData['mode'] == 'Deactive'){
                    $product_id = $postData['product_id'];
                    if(!empty($product_id)){
                        for($p=0;$p<count($product_id);$p++){
                            Product::where('id', '=', $product_id[$p])->update(['status' => 0]);
                        }
                    }
                    $title                          = 'Deactive ' . $this->data['title'].' List';
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deactivated Successfully !!!');
                }
                if($postData['mode'] == 'Delete'){
                    $product_id = $postData['product_id'];
                    if(!empty($product_id)){
                        for($p=0;$p<count($product_id);$p++){
                            Product::where('id', '=', $product_id[$p])->update(['status' => 3]);
                        }
                    }
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
                }
                if($postData['mode'] == 'Continue'){
                    $product_session_data = [
                        'who_made_it'       => $postData['who_made_it'],
                        'what_is_it'        => $postData['what_is_it'],
                        'manufacture_year'  => $postData['manufacture_year'],
                        'shop_produce_item' => $postData['shop_produce_item'],
                        'tools_used'        => json_encode($postData['tools_used']),
                        'sub_category'      => $postData['sub_category'],
                    ];
                    $request->session()->put('product_session_data', $product_session_data);
                    $sessionData = Session::all();
                    return redirect('admin/'.$this->data['controller_route'] . "/add");
                }
            }
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
        public function productCategory(Request $request){
            $data['module']                 = $this->data;
            $title                          = 'All ' . $this->data['title'].' List';
            $page_name                      = 'product.list';
            $data['rows']                   = DB::table('products')
                                                    ->join('categories', 'products.sub_category', '=', 'categories.id')
                                                    ->select('products.*', 'categories.category_name as sub_category_name')
                                                    ->where('products.status', '!=', 3)
                                                    ->orderBy('products.id', 'DESC')
                                                    ->paginate(8);
                                                    // ->get();
            $data['total_products']         = DB::table('products')->where('products.status', '!=', 3)->count();

            $generalSetting                 = GeneralSetting::find('1');
            $data['view_type']              = $generalSetting->product_view;
            $data['shop_produce_items']     = ShopProduceItem::select('id', 'name', 'description')->where('status', '=', 1)->get();
            $data['tools_useds']            = ToolsUsed::select('id', 'name', 'description')->where('status', '=', 1)->get();
            $data['subcategories']          = Category::select('id', 'category_name', 'parent_id')->where('status', '=', 1)->where('parent_id', '>', 0)->get();
            Session::forget('product_session_data');

            $data['all_products']           = DB::table('products')->where('products.status', '!=', 3)->count();
            $data['active_products']        = Product::where('status', '=', 1)->count();
            $data['deactive_products']      = Product::where('status', '=', 0)->count();
            $data['draft_products']         = Product::where('status', '=', 2)->count();
            $data['filter_by']              = '';
            $data['filter']                 = -1;

            $data['categoryVal']            = '';

            $categories                     = [];
            $parentCats                     = Category::select('id', 'category_name')->where('status', '=', 1)->where('parent_id', '=', 0)->get();
            if($parentCats){
                foreach($parentCats as $parentCat){
                    $childCats                     = Category::select('id', 'category_name')->where('status', '=', 1)->where('parent_id', '=', $parentCat->id)->get();
                    if($childCats){
                        foreach($childCats as $childCat){
                            $product_count = Product::where('main_category', '=', $parentCat->id)->where('sub_category', '=', $childCat->id)->where('status', '=', 1)->count();
                            $categories[] = [
                                'category_id'       => $childCat->id,
                                'category_name'     => $parentCat->category_name . ' - ' . $childCat->category_name,
                                'product_count'     => $product_count,
                            ];
                        }
                    }
                }
            }
            $data['categories']                 = $categories;

            $postData                           = $request->all();
            $listing_category                   = $request->listing_category;
            $category_name                      = $request->category_name;
            $final_category_name                = $category_name[$listing_category][0];
            // Helper::pr($category_name,0);
            // Helper::pr($postData);

            if($postData['mode'] == 'category'){
                $data['rows']                   = DB::table('products')
                                                    ->join('categories', 'products.sub_category', '=', 'categories.id')
                                                    ->select('products.*', 'categories.category_name as sub_category_name')
                                                    ->where('products.status', '!=', 3)
                                                    ->where('products.sub_category', '=', $listing_category)
                                                    ->orderBy('products.id', 'DESC')
                                                    ->paginate(8);
                $data['filter']                 = $listing_category;
                $data['filter_by']              = '';
                $data['total_products']         = DB::table('products')->where('products.status', '!=', 3)->where('products.sub_category', '=', $listing_category)->count();
                $statusName                     = $final_category_name;
                $title                          = $statusName . ' ' . $this->data['title'].' List';
                $data['categoryVal']            = $listing_category;
            }

            if($request->isMethod('post')){
                $postData = $request->all();
                if($postData['mode'] == 'Active'){
                    $product_id = $postData['product_id'];
                    if(!empty($product_id)){
                        for($p=0;$p<count($product_id);$p++){
                            Product::where('id', '=', $product_id[$p])->update(['status' => 1]);
                        }
                    }
                    $title                          = 'Active ' . $this->data['title'].' List';
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Activated Successfully !!!');
                }
                if($postData['mode'] == 'Deactive'){
                    $product_id = $postData['product_id'];
                    if(!empty($product_id)){
                        for($p=0;$p<count($product_id);$p++){
                            Product::where('id', '=', $product_id[$p])->update(['status' => 0]);
                        }
                    }
                    $title                          = 'Deactive ' . $this->data['title'].' List';
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deactivated Successfully !!!');
                }
                if($postData['mode'] == 'Delete'){
                    $product_id = $postData['product_id'];
                    if(!empty($product_id)){
                        for($p=0;$p<count($product_id);$p++){
                            Product::where('id', '=', $product_id[$p])->update(['status' => 3]);
                        }
                    }
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
                }
                if($postData['mode'] == 'Continue'){
                    $product_session_data = [
                        'who_made_it'       => $postData['who_made_it'],
                        'what_is_it'        => $postData['what_is_it'],
                        'manufacture_year'  => $postData['manufacture_year'],
                        'shop_produce_item' => $postData['shop_produce_item'],
                        'tools_used'        => json_encode($postData['tools_used']),
                        'sub_category'      => $postData['sub_category'],
                    ];
                    $request->session()->put('product_session_data', $product_session_data);
                    $sessionData = Session::all();
                    return redirect('admin/'.$this->data['controller_route'] . "/add");
                }
            }
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $postData = $request->all();
                // Helper::pr($postData,0);
                // Helper::pr(session()->all());
                $rules = [
                    'name'                 => 'required',
                    'base_price'           => 'required',
                    'product_sku'          => 'required',
                    'product_qty'          => 'required',
                ];
                if($this->validate($request, $rules)){
                    $product_session_data = session('product_session_data');
                    $sub_category = $product_session_data['sub_category'];
                    $getParentCategory = Category::select('id', 'category_name', 'parent_id')->where('id', '=', $sub_category)->first();
                    if($postData['product_video'] != ''){
                        $product_video      = $postData['product_video'];
                        $video_array = explode("watch?v=", $product_video);
                        $product_video_code = $video_array[1];
                    } else {
                        $product_video_code = '';
                        $product_video      = '';
                    }
                    $generalSetting = GeneralSetting::find(1);
                    if($postData['shipping_type'] == 'FREE'){
                        $shipping_rate = 0;
                    } elseif($postData['shipping_type'] == 'FIXED'){
                        $shipping_rate = $generalSetting->shipping_charge;
                    } elseif($postData['shipping_type'] == 'USPS'){
                        $shipping_rate = 0;
                    }
                    $fields = [
                        'product_nature'                => 'Physical',
                        'who_made_it'                   => $product_session_data['who_made_it'],
                        'what_is_it'                    => $product_session_data['what_is_it'],
                        'manufacture_year'              => $product_session_data['manufacture_year'],
                        'shop_produce_item'             => $product_session_data['shop_produce_item'],
                        'tools_used'                    => $product_session_data['tools_used'],
                        'main_category'                 => (($getParentCategory)?$getParentCategory->parent_id:0),
                        'sub_category'                  => $sub_category,
                        'name'                          => $postData['name'],
                        'base_price'                    => $postData['base_price'],
                        'price_percentage'              => $postData['price_percentage'],
                        'markup_price'                  => $postData['base_price'],
                        'discount_amount'               => $postData['discount_amount'],
                        'discounted_price'              => $postData['discounted_price'],
                        'slug'                          => Helper::clean($postData['name']),
                        'short_description'             => $postData['short_description'],
                        'long_description'              => $postData['long_description'],
                        'is_personalization'            => (($postData['personalization_instruction'] != '')?1:0),
                        'personalization_instruction'   => $postData['personalization_instruction'],
                        'product_sku'                   => $postData['product_sku'],
                        'product_qty'                   => $postData['product_qty'],
                        'product_weight_lb'             => $postData['product_weight_lb'],
                        'product_weight_oz'             => $postData['product_weight_oz'],
                        'product_length'                => $postData['product_length'],
                        'product_width'                 => $postData['product_width'],
                        'product_height'                => $postData['product_height'],
                        'is_feature'                    => ((array_key_exists('is_feature', $postData))?1:0),
                        'product_video_code'            => $product_video_code,
                        'product_video'                 => $product_video,
                        'tags'                          => $postData['tags'],
                        'materials'                     => ((array_key_exists('materials', $postData))?json_encode($postData['materials']):[]),
                        'shipping_policy_id'            => 0,
                        'shipping_info'                 => $postData['shipping_info'],
                        'shipping_type'                 => $postData['shipping_type'],
                        'shipping_rate'                 => $shipping_rate,
                        'return_policy_id'              => $postData['return_policy_id'],
                        'meta_title'                    => ((array_key_exists('meta_title', $postData))?$postData['meta_title']:''),
                        'meta_description'              => ((array_key_exists('meta_description', $postData))?$postData['meta_description']:''),
                        'meta_keywords'                 => ((array_key_exists('meta_keywords', $postData))?$postData['meta_keywords']:''),
                        'is_new'                        => 1,
                        'status'                        => (($postData['mode'] == 'save as draft')?2:1),
                        'created_by'                    => session('user_id'),
                        'updated_by'                    => session('user_id'),
                    ];
                    // Helper::pr($fields);
                    $product_id = Product::insertGetId($fields);
                    $id         = $product_id;
                    /* attribute */
                        if(array_key_exists("product_attribute_id",$postData)){
                            ProductAttribute::where('product_id', '=', $product_id)->delete();
                            $product_attribute_id = $postData['product_attribute_id'];
                            if(!empty($product_attribute_id)){
                                for($i=0;$i<count($product_attribute_id);$i++){
                                    if(array_key_exists("product_attribute_value_id" . $product_attribute_id[$i],$postData)){
                                        $product_attribute_value_id = $postData['product_attribute_value_id' . $product_attribute_id[$i]];
                                        if(!empty($product_attribute_value_id)){
                                            for($k=0;$k<count($product_attribute_value_id);$k++){
                                                $attrField = [
                                                    'product_id'                    => $product_id,
                                                    'product_attribute_id'          => $product_attribute_id[$i],
                                                    'product_attribute_value_id'    => $product_attribute_value_id[$k],
                                                ];
                                                ProductAttribute::insert($attrField);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    /* attribute */
                    /* variation */
                        $totQTY = 0;
                        if(array_key_exists("attr_count",$postData)){
                            // Helper::pr($postData);
                            // ProductVariation::where('product_id', '=', $id)->delete();
                            // VariationAttribute::where('product_id', '=', $id)->delete();
                            $attr_count         = $postData['attr_count'];
                            if($attr_count > 0){
                                $variationSKU       = $postData['variationSKU'];
                                $variationQTY       = $postData['variationQTY'];
                                $variationPrice     = $postData['variationPrice'];
                                $variationDiscountedPrice     = $postData['variationDiscountedPrice'];
                                if(!empty($variationPrice)){
                                    for($v=0; $v<count($variationPrice); $v++){
                                        $fields11 = [
                                            'product_id'    => $product_id,
                                            'price'         => $variationPrice[$v],
                                            'discounted_price'         => $variationDiscountedPrice[$v],
                                            'sku'           => $variationSKU[$v],
                                            'qty'           => $variationQTY[$v],
                                            'status'        => ((array_key_exists("is_visible" . $v,$postData))?1:0),
                                        ];
                                        // Helper::pr($fields11,0);
                                        $product_variation_id = ProductVariation::insertGetId($fields11);
                                        $totQTY += $variationQTY[$v];
                                        for($va=1; $va<=$attr_count; $va++){
                                            $attribute_id   = $postData['attribute_id' . $va][$v];
                                            $value_id       = $postData['value_id' . $va][$v];
                                            $getAttrId      = AttributeValue::select('attr_id')->where('status', '=', 1)->where('id', '=', $attribute_id)->first();
                                            $fields12       = [
                                                'product_variation_id'      => $product_variation_id,
                                                'product_id'                => $product_id,
                                                'parent_attr_id'            => (($getAttrId)?$getAttrId->attr_id:0),
                                                'attribute_id'              => $attribute_id,
                                                'value'                     => $value_id,
                                                'status'                    => ((array_key_exists("is_visible" . $v,$postData))?1:0),
                                            ];
                                            // Helper::pr($fields12,0);
                                            VariationAttribute::insert($fields12);
                                        }
                                    }
                                    Product::where('id', '=', $id)->update(['product_qty' => $totQTY]);
                                }
                            }
                        }
                        
                    /* variation */
                    // other images
                        if(array_key_exists("product_image",$postData)){
                            $other_images                       = $postData['product_image'];
                            $images                             = [];
                            $image_array                        = $request->file('product_image');
                            if(!empty($image_array)){
                                $uploadedFile       = $this->commonFileArrayUpload('public/uploads/product/', $image_array, 'image');
                                if(!empty($uploadedFile)){
                                    $images    = $uploadedFile;
                                } else {
                                    $images    = [];
                                }
                            }
                            // Helper::pr($images);
                            if(!empty($images)){
                                for($i=0;$i<count($images);$i++){
                                    $fields2 = [
                                        'product_id'            => $product_id,
                                        'image'                 => $images[$i]
                                    ];
                                    ProductImage::insert($fields2);
                                    if($i == 0){
                                        $fields3 = [
                                            'cover_image'                 => $images[$i]
                                        ];
                                        Product::where($this->data['primary_key'], '=', $id)->update($fields3);
                                    }
                                }
                            }
                        }
                    // other images
                    
                    return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'product.add-edit';
            $data['row']                    = [];
            $data['attr_ids']               = json_encode(array());
            $data['dropdownValues']         = json_encode(array());
            $data['parent_cats']            = Category::select('id', 'category_name', 'parent_id')->where('status', '=', 1)->where('parent_id', '=', 0)->get();
            $data['child_cats']             = Category::select('id', 'category_name', 'parent_id')->where('status', '=', 1)->where('parent_id', '!=', 0)->get();
            $data['units']                  = Unit::select('id', 'name')->where('status', '=', 1)->get();
            $data['otherProducts']          = Product::select('id', 'name')->where('status', '=', 1)->get();
            $data['shop_produce_items']     = ShopProduceItem::select('id', 'name', 'description')->where('status', '=', 1)->get();
            $data['tools_useds']            = ToolsUsed::select('id', 'name', 'description')->where('status', '=', 1)->get();
            $data['subcategories']          = Category::select('id', 'category_name', 'parent_id')->where('status', '=', 1)->where('parent_id', '>', 0)->get();
            $data['materials']              = Material::select('id', 'name')->where('status', '=', 1)->get();
            $data['returnPolicies']         = ReturnPolicy::select('id', 'name', 'type', 'timeframe', 'description')->where('status', '=', 1)->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'product.add-edit';
            $data['row']                    = Product::where($this->data['primary_key'], '=', $id)->first();
            $data['parent_cats']            = Category::select('id', 'category_name', 'parent_id')->where('status', '=', 1)->where('parent_id', '=', 0)->get();
            $data['child_cats']             = Category::select('id', 'category_name', 'parent_id')->where('status', '=', 1)->where('parent_id', '!=', 0)->get();
            $data['units']                  = Unit::select('id', 'name')->where('status', '=', 1)->get();
            $data['otherProducts']          = Product::select('id', 'name')->where('status', '=', 1)->get();
            $data['shop_produce_items']     = ShopProduceItem::select('id', 'name', 'description')->where('status', '=', 1)->get();
            $data['tools_useds']            = ToolsUsed::select('id', 'name', 'description')->where('status', '=', 1)->get();
            $data['subcategories']          = Category::select('id', 'category_name', 'parent_id')->where('status', '=', 1)->where('parent_id', '>', 0)->get();
            $data['materials']              = Material::select('id', 'name')->where('status', '=', 1)->get();
            $data['returnPolicies']         = ReturnPolicy::select('id', 'name', 'type', 'timeframe', 'description')->where('status', '=', 1)->get();

            $product_session_data = [
                'who_made_it'       => $data['row']->who_made_it,
                'what_is_it'        => $data['row']->what_is_it,
                'manufacture_year'  => $data['row']->manufacture_year,
                'shop_produce_item' => $data['row']->shop_produce_item,
                'tools_used'        => $data['row']->tools_used,
                'sub_category'      => $data['row']->sub_category,
            ];
            $request->session()->put('product_session_data', $product_session_data);
            $sessionData = Session::all();

            $parent_category = $data['row']->main_category;
            $sub_category_id = $data['row']->sub_category;
            $getAttrIds  = Attribute::select('id')->where('status', '=', 1)->where('parent_category', '=', $parent_category)->where('sub_category_id', '=', $sub_category_id)->get();
            $attrIds = [];
            if($getAttrIds){
                foreach($getAttrIds as $getAttrId){
                    $attrIds[] = $getAttrId->id;
                }
            }
            $data['attr_ids'] = json_encode($attrIds);

            $dropdownValues = [];
            $getProductparentAttrs = VariationAttribute::select('parent_attr_id')->where('status', '=', 1)->where('product_id', '=', $id)->groupBy('parent_attr_id')->get();
            if($getProductparentAttrs){
                foreach($getProductparentAttrs as $getProductparentAttr){
                    $parent_attr_id = $getProductparentAttr->parent_attr_id;
                    $getProductparentAttrVals = VariationAttribute::select('attribute_id')->where('status', '=', 1)->where('product_id', '=', $id)->where('parent_attr_id', '=', $parent_attr_id)->get();
                    if($getProductparentAttrVals){
                        foreach($getProductparentAttrVals as $getProductparentAttrVal){
                            $dropdownValues['variation' . $parent_attr_id][] = $parent_attr_id . '/' . $getProductparentAttrVal->attribute_id;
                        }
                    }
                }
            }
            // Remove duplicate values from each array
            foreach ($dropdownValues as $key => $values) {
                $dropdownValues[$key] = array_unique($values);
            }
            // Helper::pr($dropdownValues);
            $data['dropdownValues'] = json_encode($dropdownValues);

            if($request->isMethod('post')){
                $postData = $request->all();
                // Helper::pr($postData);
                $rules = [
                    'name'                 => 'required',
                    'base_price'           => 'required',
                    'product_sku'          => 'required',
                    'product_qty'          => 'required',
                ];
                if($this->validate($request, $rules)){
                    $product_session_data   = session('product_session_data');
                    $sub_category           = $product_session_data['sub_category'];
                    $getParentCategory      = Category::select('id', 'category_name', 'parent_id')->where('id', '=', $sub_category)->first();
                    if($postData['product_video'] != ''){
                        $product_video      = $postData['product_video'];
                        $video_array = explode("watch?v=", $product_video);
                        $product_video_code = $video_array[1];
                    } else {
                        $product_video_code = '';
                        $product_video      = '';
                    }
                    $generalSetting = GeneralSetting::find(1);
                    if($postData['shipping_type'] == 'FREE'){
                        $shipping_rate = 0;
                    } elseif($postData['shipping_type'] == 'FIXED'){
                        $shipping_rate = $generalSetting->shipping_charge;
                    } elseif($postData['shipping_type'] == 'USPS'){
                        $shipping_rate = 0;
                    }
                    $fields = [
                        'product_nature'                => 'Physical',
                        'who_made_it'                   => $product_session_data['who_made_it'],
                        'what_is_it'                    => $product_session_data['what_is_it'],
                        'manufacture_year'              => $product_session_data['manufacture_year'],
                        'shop_produce_item'             => $product_session_data['shop_produce_item'],
                        'tools_used'                    => $product_session_data['tools_used'],
                        'main_category'                 => (($getParentCategory)?$getParentCategory->parent_id:0),
                        'sub_category'                  => $sub_category,
                        'name'                          => $postData['name'],
                        'base_price'                    => $postData['base_price'],
                        'price_percentage'              => $postData['price_percentage'],
                        'markup_price'                  => $postData['base_price'],
                        'discount_amount'               => $postData['discount_amount'],
                        'discounted_price'              => $postData['discounted_price'],
                        'slug'                          => Helper::clean($postData['name']),
                        'short_description'             => $postData['short_description'],
                        'long_description'              => $postData['long_description'],
                        'is_personalization'            => (($postData['personalization_instruction'] != '')?1:0),
                        'personalization_instruction'   => $postData['personalization_instruction'],
                        'product_sku'                   => $postData['product_sku'],
                        'product_qty'                   => $postData['product_qty'],
                        'product_weight_lb'             => $postData['product_weight_lb'],
                        'product_weight_oz'             => $postData['product_weight_oz'],
                        'product_length'                => $postData['product_length'],
                        'product_width'                 => $postData['product_width'],
                        'product_height'                => $postData['product_height'],
                        'is_feature'                    => ((array_key_exists('is_feature', $postData))?1:0),
                        'product_video_code'            => $product_video_code,
                        'product_video'                 => $product_video,
                        'tags'                          => $postData['tags'],
                        'materials'                     => ((array_key_exists('materials', $postData))?json_encode($postData['materials']):[]),
                        'shipping_policy_id'            => 0,
                        'shipping_info'                 => $postData['shipping_info'],
                        'shipping_type'                 => $postData['shipping_type'],
                        'shipping_rate'                 => $shipping_rate,
                        'return_policy_id'              => $postData['return_policy_id'],
                        'meta_title'                    => ((array_key_exists('meta_title', $postData))?$postData['meta_title']:''),
                        'meta_description'              => ((array_key_exists('meta_description', $postData))?$postData['meta_description']:''),
                        'meta_keywords'                 => ((array_key_exists('meta_keywords', $postData))?$postData['meta_keywords']:''),
                        'is_new'                        => 1,
                        'status'                        => (($postData['mode'] == 'save as draft')?2:1),
                        'created_by'                    => session('user_id'),
                        'updated_by'                    => session('user_id'),
                    ];
                    // Helper::pr($fields);
                    Product::where($this->data['primary_key'], '=', $id)->update($fields);
                    $product_id = $id;
                    /* attribute */
                        if(array_key_exists("product_attribute_id",$postData)){
                            ProductAttribute::where('product_id', '=', $product_id)->delete();
                            $product_attribute_id = $postData['product_attribute_id'];
                            if(!empty($product_attribute_id)){
                                for($i=0;$i<count($product_attribute_id);$i++){
                                    if(array_key_exists("product_attribute_value_id" . $product_attribute_id[$i],$postData)){
                                        $product_attribute_value_id = $postData['product_attribute_value_id' . $product_attribute_id[$i]];
                                        if(!empty($product_attribute_value_id)){
                                            for($k=0;$k<count($product_attribute_value_id);$k++){
                                                $attrField = [
                                                    'product_id'                    => $product_id,
                                                    'product_attribute_id'          => $product_attribute_id[$i],
                                                    'product_attribute_value_id'    => $product_attribute_value_id[$k],
                                                ];
                                                ProductAttribute::insert($attrField);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    /* attribute */
                    /* variation */
                        $totQTY = 0;
                        // Helper::pr($postData);
                        if(array_key_exists("attr_count",$postData)){
                            ProductVariation::where('product_id', '=', $id)->delete();
                            VariationAttribute::where('product_id', '=', $id)->delete();
                            $attr_count         = $postData['attr_count'];
                            if($attr_count > 0){
                                $variationSKU       = $postData['variationSKU'];
                                $variationQTY       = $postData['variationQTY'];
                                $variationPrice     = $postData['variationPrice'];
                                $variationDiscountedPrice     = $postData['variationDiscountedPrice'];
                                if(!empty($variationPrice)){
                                    for($v=0; $v<count($variationPrice); $v++){
                                        $fields11 = [
                                            'product_id'    => $product_id,
                                            'price'         => $variationPrice[$v],
                                            'discounted_price'         => $variationDiscountedPrice[$v],
                                            'sku'           => $variationSKU[$v],
                                            'qty'           => $variationQTY[$v],
                                            'status'        => ((array_key_exists("is_visible" . $v,$postData))?1:0),
                                        ];
                                        // Helper::pr($fields11,0);
                                        $product_variation_id = ProductVariation::insertGetId($fields11);
                                        $totQTY += $variationQTY[$v];
                                        for($va=1; $va<=$attr_count; $va++){
                                            $attribute_id   = $postData['attribute_id' . $va][$v];
                                            $value_id       = $postData['value_id' . $va][$v];
                                            $getAttrId      = AttributeValue::select('attr_id')->where('status', '=', 1)->where('id', '=', $attribute_id)->first();
                                            $fields12       = [
                                                'product_variation_id'      => $product_variation_id,
                                                'product_id'                => $product_id,
                                                'parent_attr_id'            => (($getAttrId)?$getAttrId->attr_id:0),
                                                'attribute_id'              => $attribute_id,
                                                'value'                     => $value_id,
                                                'status'                    => ((array_key_exists("is_visible" . $v,$postData))?1:0),
                                            ];
                                            // Helper::pr($fields12,0);
                                            VariationAttribute::insert($fields12);
                                        }
                                    }
                                    Product::where('id', '=', $id)->update(['product_qty' => $totQTY]);
                                }
                            }
                        }
                    /* variation */
                    // other images
                        if(array_key_exists("product_image",$postData)){
                            $other_images                       = $postData['product_image'];
                            $images                             = [];
                            $image_array                        = $request->file('product_image');
                            if(!empty($image_array)){
                                $uploadedFile       = $this->commonFileArrayUpload('public/uploads/product/', $image_array, 'image');
                                if(!empty($uploadedFile)){
                                    $images    = $uploadedFile;
                                } else {
                                    $images    = [];
                                }
                            }
                            // Helper::pr($images);
                            if(!empty($images)){
                                for($i=0;$i<count($images);$i++){
                                    $fields2 = [
                                        'product_id'            => $product_id,
                                        'image'                 => $images[$i]
                                    ];
                                    ProductImage::insert($fields2);
                                    // if($i == 0){
                                    //     $fields3 = [
                                    //         'cover_image'                 => $images[$i]
                                    //     ];
                                    //     Product::where($this->data['primary_key'], '=', $id)->update($fields3);
                                    // }
                                }
                            }
                        }
                    // other images
                    if(array_key_exists("is_cover_image",$postData)){
                        $is_cover_image = $postData['is_cover_image'];
                        $getImageName   = ProductImage::where('id', '=', $is_cover_image)->first();
                        if($getImageName){
                            $fields3 = [
                                'cover_image'                 => $getImageName->image
                            ];
                            Product::where('id', '=', $id)->update($fields3);
                            ProductImage::where('product_id', '=', $id)->update(['is_cover_image' => 0]);
                            ProductImage::where('id', '=', $is_cover_image)->update(['is_cover_image' => 1]);
                        }
                    }
                    
                    Session::forget('product_session_data');
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
            Product::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
        public function deleteSingleImage(Request $request, $id, $product_id){
            $id                             = Helper::decoded($id);
            $product_id                     = Helper::decoded($product_id);
            ProductImage::where('id', '=', $id)->delete();
            return redirect('admin/'.$this->data['controller_route'] . "/edit/".Helper::encoded($product_id))->with('success_message', $this->data['title'].' Images Deleted Successfully !!!');
        }
    /* delete */
    /* copy */
        public function copy(Request $request, $id){
            $id                             = Helper::decoded($id);
            $this->duplicateRow($id);
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Copied Successfully !!!');
        }
        public function duplicateRow($id)
        {
            // Find the row by ID
            $row = Product::findOrFail($id);
            // Remove the ID and other non-copyable fields
            $newRow = $row->replicate();
            $newRow->status = 0; // Example modification
            $newRow->save();
            return $newRow; // Return the newly created row
        }
    /* copy */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Product::find($id);
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
    /* change feature */
        public function change_feature(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Product::find($id);
            if ($model->is_feature == 1)
            {
                $model->is_feature  = 0;
                $msg            = 'Non-featured';
            } else {
                $model->is_feature  = 1;
                $msg            = 'Featured';
            }            
            $model->save();
            return redirect('admin/'.$this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Marked As '.$msg.' Successfully !!!');
        }
    /* change feature */
    public function getProductAttribute(Request $request){
        $apiStatus          = TRUE;
        $apiMessage         = '';
        $apiResponse        = [];
        $apiExtraField      = '';
        $apiExtraData       = '';

        $data               = [];
        $postData           = $request->all();
        $subcat             = $postData['subcat'];
        $product_id         = $postData['product_id'];
        $getAttrs           = Attribute::select('id', 'name', 'is_price_effect')->where('sub_category_id', '=', $subcat)->where('status', '=', 1)->get();
        if($getAttrs){
            foreach($getAttrs as $getAttr){
                $attr_values            = [];
                $getAttrVals            = AttributeValue::select('id', 'attr_id', 'attr_value', 'price_type', 'price_val', 'ref_val', 'attr_value_image')->where('sub_category_id', '=', $subcat)->where('attr_id', '=', $getAttr->id)->where('status', '=', 1)->get();
                $parentChecked          = 0;
                if($getAttrVals){
                    foreach($getAttrVals as $getAttrVal){
                        $checkAlreadySavedCount = ProductAttribute::where('product_id', '=', $product_id)->where('product_attribute_value_id', '=', $getAttrVal->id)->count();
                        $checkAlreadySaved = ProductAttribute::where('product_id', '=', $product_id)->where('product_attribute_value_id', '=', $getAttrVal->id)->first();
                        // Helper::pr($checkAlreadySaved);
                        if($checkAlreadySavedCount > 0){
                            $parentChecked++;
                        }
                        $attr_val_image = '';
                        if(isset($getAttrVal->attr_value_image) && $getAttrVal->attr_value_image != ''){
                            $attr_val_image = env('UPLOADS_URL').'product/'.$getAttrVal->attr_value_image;
                        }
                        $attr_values[]            = [
                            'attr_val_id'           => $getAttrVal->id,
                            'attr_val_attr_id'      => $getAttrVal->attr_id,
                            'attr_val_name'         => $getAttrVal->attr_value,
                            'attr_val_price_type'   => $getAttrVal->price_type,
                            'attr_val_price_val'    => $getAttrVal->price_val,
                            'attr_val_ref_val'      => $getAttrVal->ref_val,
                            'attr_val_unit_price'   => (($checkAlreadySaved)?$checkAlreadySaved->unit_price:''),
                            'child_checked'         => (($checkAlreadySavedCount > 0)?1:0),
                            'attr_val_image'        => $attr_val_image
                        ];
                    }
                }

                $data[]                 = [
                    'attr_id'               => $getAttr->id,
                    'attr_name'             => $getAttr->name,
                    'attr_is_price_effect'  => $getAttr->is_price_effect,
                    'attr_values'           => $attr_values,
                    'checked'               => (($parentChecked > 0)?1:0),
                ];
            }
        }
        $apiResponse        = $data;
        $apiStatus          = TRUE;
        $apiMessage         = 'Data Available !!!';
        $apiExtraField      = 'response_code';
        $apiExtraData       = http_response_code();
        $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
    }
    public function updateProductView(Request $request){
        $apiStatus          = TRUE;
        $apiMessage         = '';
        $apiResponse        = [];
        $apiExtraField      = '';
        $apiExtraData       = '';

        $data               = [];
        $postData           = $request->all();
        $viewType           = $postData['viewType'];
        GeneralSetting::where('id', '=', 1)->update(['product_view' => $viewType]);
        
        $apiResponse        = $data;
        $apiStatus          = TRUE;
        $apiMessage         = 'Data Available !!!';
        $apiExtraField      = 'response_code';
        $apiExtraData       = http_response_code();
        $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
    }
    public function generateProductVariation(Request $request){
        $apiStatus          = TRUE;
        $apiMessage         = '';
        $apiResponse        = [];
        $apiExtraField      = '';
        $apiExtraData       = '';

        $data               = [];
        $postData           = $request->all();
        $attrIds            = explode(",", $postData['attrIds']);
        $dropdownValues     = $postData['dropdownValues'];
        $base_price         = $postData['base_price'];
        $price_percentage   = $postData['price_percentage'];
        $discount_amount    = $postData['discount_amount'];
        $discounted_price   = $postData['discounted_price'];
        $product_sku        = $postData['product_sku'];
        $product_qty        = $postData['product_qty'];
        $product_id         = $postData['product_id'];
        $attributes         = [];
        if(!empty($attrIds)){
            $m=1;
            for($k=0;$k<count($attrIds);$k++){
                if(isset($dropdownValues['variation' . $attrIds[$k]])){
                    $attributes['attribute' . $m]         = $dropdownValues['variation' . $attrIds[$k]];
                    $m++;
                }
            }
        }
        // Helper::pr($attributes,0);
        // Generate all variations
        $variations = $this->generateVariations($attributes);
        // Helper::pr($variations);
        $data['attributes']             = $attributes;
        $data['variations']             = $variations;
        $data['base_price']             = $base_price;
        $data['price_percentage']       = $price_percentage;
        $data['discount_amount']        = $discount_amount;
        $data['discounted_price']       = $discounted_price;
        $data['product_sku']            = $product_sku;
        $data['product_qty']            = $product_qty;
        $data['product_id']             = $product_id;
        $html                           = view('admin.maincontents.product.variation', $data);
        echo $html;die;
        // echo $apiResponse                    = $html;

        // $apiStatus                      = TRUE;
        // $apiMessage                     = 'Data Available !!!';
        // $apiExtraField                  = 'response_code';
        // $apiExtraData                   = http_response_code();
        // $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
    }
    public function generateProductVariation2(Request $request){
        $apiStatus          = TRUE;
        $apiMessage         = '';
        $apiResponse        = [];
        $apiExtraField      = '';
        $apiExtraData       = '';

        $data               = [];
        $postData           = $request->all();
        $attrIds            = json_decode($postData['attrIds']);
        $dropdownValues     = json_decode($postData['dropdownValues'], true);
        $base_price         = $postData['base_price'];
        $price_percentage   = $postData['price_percentage'];
        $discount_amount    = $postData['discount_amount'];
        $discounted_price   = $postData['discounted_price'];
        $product_sku        = $postData['product_sku'];
        $product_qty        = $postData['product_qty'];
        $product_id         = $postData['product_id'];
        $attributes         = [];
        if(!empty($attributes)){
            if(!empty($attrIds)){
                $m=1;
                for($k=0;$k<count($attrIds);$k++){
                    if(isset($dropdownValues['variation' . $attrIds[$k]])){
                        $attributes['attribute' . $m]         = $dropdownValues['variation' . $attrIds[$k]];
                        $m++;
                    }
                }
            }
            // Helper::pr($attributes);
            // Generate all variations
            $variations = $this->generateVariations($attributes);
        } else {
            $variations = [];
            $attributes         = [];
            $getProVariations = ProductVariation::select('id')->where('status', '=', 1)->where('product_id', '=', $product_id)->get();
            if($getProVariations){
                foreach($getProVariations as $getProVariation){
                    $getVariationAttrs = VariationAttribute::select('parent_attr_id', 'attribute_id')->where('status', '=', 1)->where('product_id', '=', $product_id)->where('product_variation_id', '=', $getProVariation->id)->get();
                    $m=1;
                    if($getVariationAttrs){
                        foreach($getVariationAttrs as $getVariationAttr){
                            $attributes['attribute' . $m]         = $getVariationAttr->parent_attr_id . '/' . $getVariationAttr->attribute_id;
                            $m++;
                        }
                    }
                    $variations[] = $attributes;
                }
            }
            // Helper::pr($variations);
        }
        $data['attributes']             = $attributes;
        $data['variations']             = $variations;
        $data['base_price']             = $base_price;
        $data['price_percentage']       = $price_percentage;
        $data['discount_amount']        = $discount_amount;
        $data['discounted_price']       = $discounted_price;
        $data['product_sku']            = $product_sku;
        $data['product_qty']            = $product_qty;
        $data['product_id']             = $product_id;
        $html                           = view('admin.maincontents.product.variation', $data);
        echo $html;die;
    }
    // Function to calculate Cartesian product dynamically
    public function generateVariations($arrays) {
        $result = [[]];
        foreach ($arrays as $key => $values) {
            $temp = [];
            foreach ($result as $prefix) {
                foreach ($values as $value) {
                    $temp[] = array_merge($prefix, [$key => $value]);
                }
            }
            $result = $temp;
        }
        return $result;
    }
    public function updateCoverImage(){
        $proImages = ProductImage::select('product_id', 'image')->where('is_cover_image', '=', 1)->where('status', '=', 1)->orderBy('product_id', 'ASC')->get();
        // Helper::pr($proImages);
        if($proImages){
            foreach($proImages as $proImage){
                Product::where('id', '=', $proImage->product_id)->update(['cover_image' => $proImage->image]);
            }
        }
        echo "Product cover image updated successfully !!!";
    }
}
