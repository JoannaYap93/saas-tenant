<?php

namespace App\Http\Controllers;

use App\Model\Collect;
use App\Model\SyncDeliveryOrder;
use App\Model\SyncDeliveryOrderItems;
use App\Model\SyncCustomer;
use App\Model\SyncDaily;
use App\Model\DeliveryOrderType;
use App\Model\Product;
use App\Model\SettingSize;
use App\Model\CompanyLand;
use App\Model\Company;
use App\Model\Customer;
use App\Model\CustomerPIC;
use App\Model\CompanyLandTree;
use App\Model\CompanyPnlSubItem;
use App\Model\DeliveryOrder;
use App\Model\DeliveryOrderItem;
use App\Model\DeliveryOrderLog;
use App\Model\Media;
use App\Model\ProductStockTransfer;
use App\Model\ProductStockWarehouse;
use App\Model\Setting;
use App\Model\User;
use App\Model\SettingRawMaterial;
use App\Model\SettingExpense;
use App\Model\SettingExpenseCategory;
use App\Model\Sync;
use App\Model\SyncCollect;
use App\Model\SyncCompanyExpense;
use App\Model\SyncDeliveryOrderLog;
use App\Model\SyncFormulaUsage;
use App\Model\SyncFormulaUsageItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use ZipArchive;

class SyncListController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function listing(Request $request)
    {
        $search = array();

        if($request->isMethod('post')){
            $submit_type = $request->input('submit');

            switch ($submit_type) {
                case 'search':
                    session(['sync_listing' => [
                        'freetext' =>  $request->input('freetext'),
                        'company_land_id' => $request->input('company_land_id'),
                        'company_id' => $request->input('company_id'),
                        'sync_from' => $request->input('sync_from'),
                        'sync_to' => $request->input('sync_to'),
                        'user_id' => $request->input('user_id'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('sync_listing');
                    break;
            }
        }

        $search = session('sync_listing') ?? $search;

        return view('sync_list.listing', [
            'search' => $search,
            'records' => Sync::get_records($search),
            'sync_details' => Sync::get_sync_details($search),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company_sel' => Company::get_company_sel(),
            'user_sel' => ['' => 'Please select user'] + User::get_user_sel(),
            'submit'=> route('sync_listing', ['tenant' => tenant('id')]),
        ]);
    }

    public function SyncCompanyExpense(Request $request)
    {
        $search = array();

        if ($request->isMethod('post')) {
            $submit = $request->input('submit');
            switch ($submit) {
                case 'search':
                    Session::forget('sync_company_expense_listing');
                    $search['freetext'] = $request->input('freetext');
                    $search['company_id'] = $request->input('company_id');
                    $search['company_land_id'] = $request->input('company_land_id');
                    $search['expense_category_id'] = $request->input('expense_category_id');
                    $search['expense_id'] = $request->input('expense_id');
                    $search['comp_expense_type'] = $request->input('comp_expense_type');
                    $search['user_id'] = $request->input('user_id');
                    $search['sync_id'] = $request->input('sync_id');

                    Session::put('sync_company_expense_listing', $search);
                    break;
                case 'reset':
                    Session::forget('sync_company_expense_listing');
                    break;
                }
             }

            $search = session('sync_company_expense_listing') ?? array();

        return view('sync_list.sync_company_expense', [
            'submit' => route('sync_company_expense_listing', ['tenant' => tenant('id')]),
            'search' => $search,
            'records' => SyncCompanyExpense::get_records($search),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company_sel' => Company::get_company_sel(),
            'expense_category_sel' => SettingExpenseCategory::get_existing_expense_category_sel(),
            'expense_sel' => SettingExpense::get_expense_sel(),
            'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(),
            'expense_type_sel' => [ ''=> 'Please Select Expense Type', 'daily' => 'Daily', 'monthly' => 'Monthly'],
        ]);
    }

    public function SyncFormulaUsage(Request $request)
    {
        $user = Auth::user();
        $search = array();
        // $setting_warehouse = SettingWarehouse::get_warehouse_sel_by_company($user->company_id);
        // $product_id = Product::get_product_sel_for_stock_transfer();

        if ($request->isMethod('post')) {
          // dd($request->all());?

            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                        $search['freetext'] = $request->input('freetext');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['raw_material_id'] = $request->input('raw_material_id');
                        $search['user_id'] = $request->input('user_id');
                        $search['formula_usage_type'] = $request->input('formula_usage_type');
                        $search['formula_usage_status'] = $request->input('formula_usage_status');
                        $search['sync_id'] = $request->input('sync_id');
                        Session::put('sync_formula_usage_search', $search);
                    break;
                case 'reset':
                    session()->forget('sync_formula_usage_search');
                    break;
            }
        }
        // $formula_usage = FormulaUsage::get_records($search);
        $search = session('sync_formula_usage_search') ?? array();
        return view('sync_list.sync_formula_usage', [
            'submit' => route('sync_formula_usage_listing', ['tenant' => tenant('id')]),
            'records' => SyncFormulaUsage::get_records($search),
            // 'status' => ProductStatus::get_records(),
            'product_tree' => CompanyLandTree::get_tree_w_product_by_land(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company_sel' => Company::get_company_sel(),
            'search' => $search,
            'company' => Company::get_company_sel(),
            'company_land' => CompanyLand::get_company_land_sel(),
            'company_pnl_sub_item' => CompanyPnlSubItem::all(),
            'raw_material_sel' => SettingRawMaterial::get_rm_sel(),
            'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(),
            'formula_usage_status_sel' => ['' => 'Please Select Status', 'completed' => 'Completed', 'pending' => 'Pending', 'deleted' => 'Deleted'],
            'formula_usage_type_sel' => ['' => 'Please Select Type', 'man' => 'Man', 'drone' => 'Drone'],
        ]);
    }

    public function SyncDeliveryOrder(Request $request){

        $pageNumber = 1;
        if (isset($_GET['page'])){
            $pageNumber = $_GET['page'];
        }

        $search = array();

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['filter_syncDeliveryOrder' => [
                        'freetext' => $request->input('freetext'),
                        "delivery_order_type_id"=>  $request->input('delivery_order_type_id'),
                        "company_land_id"=>$request->input('company_land_id'),
                        'cc_from' => $request->input('cc_from'),
                        'cc_to' => $request->input('cc_from'),
                        'sync_id' => $request->input('sync_id'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('filter_syncDeliveryOrder');
                    break;
            }
        }

        $deliver_order_type_sel = DeliveryOrderType::get_sel();
        $company_land_sel = CompanyLand::get_company_land_sel();

        $search = session('filter_syncDeliveryOrder') ? session('filter_syncDeliveryOrder') : $search;
        $syncDeliveryOrder = SyncDeliveryOrder::get_records($search,[1]);


        $perpage = 10;
        $total_records = count($syncDeliveryOrder);
        $start = ($pageNumber * $perpage)- $perpage;

        return view('sync_list.deliveryOrder', [
            'submit'=> route('sync_delivery_order_listing', ['tenant' => tenant('id')]),
            'records'=> $syncDeliveryOrder,
            'title'=> 'Add',
            // 'syncDeliveryOrder'=>  $syncDeliveryOrder,
            'order_type_sel'=> $deliver_order_type_sel,
            'search'=> $search,
            'company_land_sel' => $company_land_sel,
            'cc_from' => $request->input('cc_from'),
            'cc_to' => $request->input('cc_from'),
        ]);

    }


    public function SyncDeliveryOrderItems(){
        $pageNumber = 1;
        if (isset($_GET['page'])){
            $pageNumber = $_GET['page'];
        }
        $search = array();

        $search = session('filter_deliveryOrderItems') ? session('filter_deliveryOrderItems') : $search;
        $syncDeliveryOrderItems = SyncDeliveryOrderItems::get_records($search,[1]);

        $perpage = 10;
        $total_records = count($syncDeliveryOrderItems);
        $start = ($pageNumber * $perpage)- $perpage;

        return view('sync_list.deliveryOrderItems', [
            'submit'=> route('syncDeliveryOrderItems_listing', ['tenant' => tenant('id')]),
            'records'=> $syncDeliveryOrderItems,
            'title'=> 'Add',
            'syncDeliveryOrderItems'=>  $syncDeliveryOrderItems,
            'search'=> $search,
            // 'company_name_sel'=> $company_name_sel
        ]);

    }
        public function view_do_items($id)
        {
            $search['sync_delivery_order_id'] = $id;
            Session::put('filter_deliveryOrderItems', $search);
            return redirect()->route('syncDeliveryOrderItems_listing', ['tenant' => tenant('id')]);
        }

    public function SyncCustomer(Request $request){

        $pageNumber = 1;
        if (isset($_GET['page'])){
            $pageNumber = $_GET['page'];
        }
        $search = array();

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['filter_syncCustomer' => [
                        'freetext' => $request->input('freetext'),
                        "company_id"=>$request->input('company_id'),
                        'sync_id' => $request->input('sync_id'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('filter_syncCustomer');
                    break;
            }
        }
        $company_name_sel = Company::get_company_sel();

        $search = session('filter_syncCustomer') ? session('filter_syncCustomer') : $search;
        $syncCustomer = SyncCustomer::get_records($search,[1]);

        $perpage = 10;
        $total_records = count($syncCustomer);
        $start = ($pageNumber * $perpage)- $perpage;

        return view('sync_list.customer', [
            'submit'=> route('synccustomer_listing', ['tenant' => tenant('id')]),
            'records'=> $syncCustomer,
            'title'=> 'Add',
            'syncCustomer'=>  $syncCustomer,
            'search'=> $search,
            'company_name_sel'=> $company_name_sel
        ]);
    }

    public function SyncDaily(Request $request){

        $pageNumber = 1;
        if (isset($_GET['page'])){
            $pageNumber = $_GET['page'];
        }
        $search = array();

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['filter_syncDaily' => [
                        'freetext' => $request->input('freetext'),
                        "product_id"=>$request->input('product_id'),
                        "sync_collect_code"=>$request->input('sync_collect_code'),
                        "setting_product_size_id"=>$request->input('setting_product_size_id'),
                        "company_land_id"=>$request->input('company_land_id'),
                        'cc_from' => $request->input('cc_from'),
                        'cc_to' => $request->input('cc_from'),
                        'sync_id' => $request->input('sync_id'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('filter_syncDaily');
                    break;
            }
        }

        $product_name_sel = Product::get_product_sel_for_stock_transfer();
        $product_size_sel = SettingSize::get_product_size_sel();
        $company_land_sel = CompanyLand::get_company_land_sel();


        $search = session('filter_syncDaily') ? session('filter_syncDaily') : $search;
        $syncDaily = SyncDaily::get_records($search,[1]);
        // dd($syncDaily);
        $perpage = 10;
        $total_records = count($syncDaily);
        $start = ($pageNumber * $perpage)- $perpage;

        return view('sync_list.daily', [
            'submit'=> route('daily_listing', ['tenant' => tenant('id')]),
            'records' => $syncDaily,
            'title'=> 'Add',
            'syncDaily'=>  $syncDaily,
            'search'=> $search,
            'product_name_sel' => $product_name_sel,
            'product_size_sel' => $product_size_sel,
            'company_land_sel' => $company_land_sel
        ]);
    }

    public function sync_zip_file(Request $request){
        $validator = null;
        $post = null;
        $sync_id = null;

        if ($request->isMethod('post')) {
            // echo '<pre>';
            // print_r(ini_get('post_max_size'));
            // print_r(ini_get('upload_max_filesize'));
            // print_r($request->all());
            // exit;
            $validator = Validator::make($request->all(), [
                'zip_file' => 'required|mimetypes:application/zip',
            ])->setAttributeNames([
                'zip_file' => 'Zip File',
            ]);

            if (!$validator->fails()) {
                $fileee = file_get_contents($request->zip_file);
                $tmpDir = sys_get_temp_dir();
                $temp_path = $tmpDir.'/test.zip';
                file_put_contents($temp_path, $fileee);

                $zip_archive = new ZipArchive;
                $zip_archive->open($temp_path);
                if($zip_archive->numFiles){
                    $dir = null;
                    for ($i = 0; $i < $zip_archive->numFiles; $i++) {
                        $filename = $zip_archive->getNameIndex($i);
                        if(pathinfo($filename,PATHINFO_EXTENSION)== 'txt'){
                            $txt_file = $zip_archive->getFromIndex($i);
                            if(!$txt_file){
                                // return [
                                //     'status' => false,
                                //     'message' => 'Empty .txt file'
                                // ];
                                Session::flash('fail_msg', 'Data is empty!');
                                return redirect()->route('sync_zip_file', ['tenant' => tenant('id')]);

                            }
                            $data = (object)json_decode($txt_file)->data;


                            $sync = Sync::create([
                                'user_id' => $data->user_id,
                                'company_id' => $data->company_id,
                                'sync_created' => now(),
                                'sync_updated' => now(),
                            ]);
                            $sync_id = $sync->sync_id;

                            $sync->addMedia($request->zip_file)->toMediaCollection('zip_files');

                            if(@$data->sync_collect){
                                $sync_collect = $data->sync_collect;
                                foreach($sync_collect as $sc){
                                    $row = (object) $sc;
                                    $db_sync_collect = SyncCollect::firstOrCreate([
                                        'sync_collect_code' => $row->sync_collect_code,
                                        'sync_collect_created' => $row->sync_collect_created,
                                    ],[
                                        'product_id' => $row->product_id,
                                        'setting_product_size_id' => $row->setting_product_size_id,
                                        'sync_collect_quantity' => $row->sync_collect_quantity,
                                        'sync_collect_created' => $row->sync_collect_created,
                                        'sync_collect_updated' => $row->sync_collect_updated,
                                        'sync_id' => $sync_id,
                                        'company_land_id' => $row->company_land_id,
                                        'sync_collect_date' => now(),
                                        'sync_collect_code' => $row->sync_collect_code,
                                        'sync_collect_status' => $row->sync_collect_status,
                                        'user_id' => $data->user_id,
                                        'sync_collect_remark' => @$row->sync_collect_remark,
                                    ]);

                                    if($db_sync_collect->wasRecentlyCreated){
                                        $collect = Collect::create([
                                            'product_id' => $row->product_id,
                                            'setting_product_size_id' => $row->setting_product_size_id,
                                            'collect_quantity' => $row->sync_collect_quantity,
                                            'collect_created' => $row->sync_collect_created,
                                            'collect_updated' => $row->sync_collect_updated,
                                            'company_id' => $data->company_id,
                                            'company_land_id' => $row->company_land_id,
                                            'collect_date' => now(),
                                            'collect_code' => $row->sync_collect_code,
                                            'collect_status' => $row->sync_collect_status,
                                            'user_id' => $data->user_id,
                                            'sync_id' => $sync_id,
                                            'collect_remark' => @$row->sync_collect_remark
                                        ]);

                                        if(@$row->sync_collect_filename && !$collect->hasMedia('collect_media')){
                                          if(count($row->sync_collect_filename) > 0){
                                            foreach($row->sync_collect_filename as $key => $sync_collect_filename){
                                              $img_string = $zip_archive->getFromName($dir.$sync_collect_filename);
                                              // if($img_string){
                                              //     $image = imagecreatefromstring($img_string);
                                              //     $temp= explode('.',$row->sync_collect_filename);
                                              //     $extension = end($temp);
                                              //     $temp_file = Media::generate_temp_media_from_string($image,$extension);
                                              //     $collect->addMedia($temp_file)->usingFileName($row->sync_collect_filename)->toMediaCollection('collect_media');
                                              // }
                                              // $img_string = $zip_archive->getFromName($dir.$row->sync_collect_filename);
                                              $collect->addMediaFromString($img_string)->usingFileName($sync_collect_filename)->toMediaCollection('collect_media');
                                            }
                                          }
                                        }
                                    }

                                    $db_sync_collect->update([
                                        'is_executed' => 1
                                    ]);
                                }
                            }

                            if(@$data->sync_delivery_order){
                                $sync_delivery_order = $data->sync_delivery_order;
                                foreach($sync_delivery_order as $sdo){
                                    $row = (object)$sdo;
                                    $do_status_id = $row->sync_delivery_order_status_id;

                                    if($row->sync_delivery_order_status_id == 4 || $row->sync_delivery_order_status_id == 5){ //Pending verification || Verified -> approved
                                        $do_status_id = 2; //approved
                                    }

                                    $customer = Customer::where('customer_id', $row->customer_id)->first();
                                    $db_sync_delivery_order = SyncDeliveryOrder::firstOrCreate([
                                        'sync_delivery_order_no' => $row->sync_delivery_order_no,
                                        'sync_delivery_order_created' => $row->sync_delivery_order_created,
                                    ],[
                                        'sync_delivery_order_no' => $row->sync_delivery_order_no,
                                        'sync_delivery_order_total_quantity' => $row->sync_delivery_order_total_quantity,
                                        'customer_id' => $row->customer_id,
                                        'customer_name' => $row->customer_name,
                                        'sync_id' => $sync_id,
                                        'customer_ic' => $row->customer_ic,
                                        'customer_mobile_no' => $row->customer_mobile_no,
                                        'sync_delivery_order_date' => now(),
                                        'company_land_id' => $row->company_land_id,
                                        'sync_delivery_order_created' => $row->sync_delivery_order_created,
                                        'sync_delivery_order_updated' => $row->sync_delivery_order_updated,
                                        'sync_delivery_order_status_id' => $do_status_id,
                                        'sync_delivery_order_type_id' => $row->sync_delivery_order_type_id,
                                        'sync_delivery_order_remark' => @$row->sync_delivery_order_remark,
                                    ]);
                                    $sync_delivery_order_id = $db_sync_delivery_order->sync_delivery_order_id;

                                    if($db_sync_delivery_order->wasRecentlyCreated){
                                        $delivery_order = DeliveryOrder::create([
                                            'delivery_order_created' =>  $row->sync_delivery_order_created,
                                            'delivery_order_updated' =>  $row->sync_delivery_order_updated,
                                            'delivery_order_no' => $row->sync_delivery_order_no,
                                            'customer_id' => $row->customer_id,
                                            'customer_ic' => $row->customer_ic,
                                            'customer_mobile_no' => $row->customer_mobile_no,
                                            'customer_name' => $row->customer_name,
                                            'delivery_order_total_quantity' => $row->sync_delivery_order_total_quantity,
                                            'sync_id' => $sync_id,
                                            'delivery_order_status_id' => $do_status_id,
                                            'delivery_order_type_id' => $row->sync_delivery_order_type_id,
                                            'company_id' => $data->company_id,
                                            'company_land_id' => $row->company_land_id,
                                            'user_id' => $data->user_id,
                                            'delivery_order_remark' => @$row->sync_delivery_order_remark
                                        ]);

                                        if(@$row->sync_delivery_order_signature){
                                            $img_string = $zip_archive->getFromName($dir.$row->sync_delivery_order_signature);
                                            if($img_string){
                                                $image = imagecreatefromstring($img_string);
                                                imagesavealpha($image, true);
                                                imagealphablending($image, false);
                                                $temp= explode('.',$row->sync_delivery_order_signature);
                                                $extension = end($temp);
                                                $temp_file = Media::generate_temp_media_from_string($image,$extension);
                                                $delivery_order->addMedia($temp_file)->usingFileName($row->sync_delivery_order_signature)->toMediaCollection('delivery_order_signature');
                                            }
                                        }

                                        $customer_pic_exist = CustomerPIC::check_existing($row->customer_id, $row->customer_ic);

                                        if(!$customer_pic_exist){
                                            CustomerPIC::create([
                                                'customer_pic_name' => $row->customer_name,
                                                'customer_pic_ic' => $row->customer_ic,
                                                'customer_pic_mobile_no' => $row->customer_mobile_no,
                                                'customer_id' => $row->customer_id,
                                                'customer_pic_created' => now(),
                                                'customer_pic_updated' => now()
                                            ]);
                                        }

                                        foreach($row->sync_delivery_order_item as $sdoi){
                                            $do_item = (object)$sdoi;
                                            $db_sync_delivery_order_item = SyncDeliveryOrderItems::create([
                                                'sync_delivery_order_id' => $sync_delivery_order_id,
                                                'product_id' => $do_item->product_id,
                                                'setting_product_size_id' => $do_item->setting_product_size_id,
                                                'sync_delivery_order_item_quantity' => $do_item->sync_delivery_order_item_quantity,
                                                'sync_delivery_order_item_collect_no' => @$do_item->collect_code,
                                                'sync_delivery_order_item_created' => $do_item->sync_delivery_order_item_created,
                                                'sync_delivery_order_item_updated' => $do_item->sync_delivery_order_item_updated,
                                                'no_collect_code' => @$do_item->no_collect_code??0,
                                            ]);

                                            $delivery_order_item = DeliveryOrderItem::create([
                                                'delivery_order_item_created' => $do_item->sync_delivery_order_item_created,
                                                'delivery_order_item_updated' => $do_item->sync_delivery_order_item_updated,
                                                'delivery_order_item_collect_no' => @$do_item->collect_code,
                                                'delivery_order_id' => $delivery_order->delivery_order_id,
                                                'product_id'=> $do_item->product_id,
                                                'setting_product_size_id'=> $do_item->setting_product_size_id,
                                                'delivery_order_item_quantity' => $do_item->sync_delivery_order_item_quantity,
                                                'no_collect_code' => @$do_item->no_collect_code??0,
                                            ]);

                                            if(@$do_item->sync_delivery_order_item_filename && !$delivery_order_item->hasMedia('delivery_order_item_media')){
                                                if(is_array($do_item->sync_delivery_order_item_filename) && count(@$do_item->sync_delivery_order_item_filename) > 0){
                                                    foreach($do_item->sync_delivery_order_item_filename as $key => $sync_delivery_order_item_filename){
                                                      $img_string = $zip_archive->getFromName($dir.$sync_delivery_order_item_filename);
                                                      if($img_string){
                                                          $image = imagecreatefromstring($img_string);
                                                          $temp= explode('.',$sync_delivery_order_item_filename);
                                                          $extension = end($temp);
                                                          $temp_file = Media::generate_temp_media_from_string($image,$extension);
                                                          $delivery_order_item->addMedia($temp_file)->usingFileName($sync_delivery_order_item_filename)->toMediaCollection('delivery_order_item_media');
                                                      }
                                                    }
                                                  }else{
                                                    $img_string = $zip_archive->getFromName($dir.$do_item->sync_delivery_order_item_filename);
                                                    if($img_string){
                                                        $image = imagecreatefromstring($img_string);
                                                        $temp= explode('.',$do_item->sync_delivery_order_item_filename);
                                                        $extension = end($temp);
                                                        $temp_file = Media::generate_temp_media_from_string($image,$extension);
                                                        $delivery_order_item->addMedia($temp_file)->usingFileName($do_item->sync_delivery_order_item_filename)->toMediaCollection('delivery_order_item_media');
                                                    }
                                                }
                                            }

                                            $db_sync_delivery_order_item->update([
                                                'delivery_order_item_id' => $delivery_order_item->delivery_order_item_id
                                            ]);

                                            if($delivery_order->delivery_order_type_id == 2 && $customer->warehouse_id){ //warehouse
                                                $product_stock_warehouse = ProductStockWarehouse::where([
                                                    'warehouse_id' => $customer->warehouse_id,
                                                    'product_id'=> $do_item->product_id,
                                                    'setting_product_size_id'=> $do_item->setting_product_size_id,
                                                ])->first();

                                                if($product_stock_warehouse){
                                                    $product_stock_warehouse_qty_new = $product_stock_warehouse->product_stock_warehouse_qty_current - $do_item->sync_delivery_order_item_quantity;
                                                    $product_stock_transfer_description = Setting::get_by_slug('product_stock_transfer_description');
                                                    ProductStockTransfer::create([
                                                        'product_stock_transfer_description' => $product_stock_transfer_description,
                                                        'product_stock_transfer_remark' => 'Item #'.$delivery_order_item->delivery_order_item_id.' has been transfer to warehouse #'.@$sdo->warehouse_id,
                                                        'product_stock_transfer_qty' => $do_item->sync_delivery_order_item_quantity,
                                                        'product_stock_transfer_qty_before' => $product_stock_warehouse->product_stock_warehouse_qty_current,
                                                        'product_stock_transfer_qty_after' => $product_stock_warehouse_qty_new,
                                                        'product_stock_warehouse_id' => @$product_stock_warehouse->product_stock_warehouse_id ?? 0,
                                                        'product_stock_transfer_updated' => now(),
                                                        'product_stock_transfer_created' => now(),
                                                        'setting_product_size_id' => $do_item->setting_product_size_id,
                                                        'product_id' => $do_item->product_id
                                                    ]);

                                                    $product_stock_warehouse->update([
                                                        'product_stock_warehouse_qty_current' => $product_stock_warehouse_qty_new
                                                    ]);
                                                }
                                            }
                                        }
                                        $sync_delivery_order_log = (object)$row->sync_delivery_order_log;
                                        SyncDeliveryOrderLog::create([
                                            'sync_delivery_order_id' => $sync_delivery_order_id,
                                            'sync_delivery_order_log_action' => $sync_delivery_order_log->sync_delivery_order_log_action,
                                            'sync_delivery_order_log_description' => $sync_delivery_order_log->sync_delivery_order_log_description,
                                            'user_id' => $data->user_id,
                                            'sync_delivery_order_log_created' => $sync_delivery_order_log->sync_delivery_order_log_created
                                        ]);

                                        DeliveryOrderLog::create([
                                            'delivery_order_id' => $delivery_order->delivery_order_id,
                                            'delivery_order_log_created' => $sync_delivery_order_log->sync_delivery_order_log_created,
                                            'delivery_order_log_action' => $sync_delivery_order_log->sync_delivery_order_log_action,
                                            'delivery_order_log_description' => $sync_delivery_order_log->sync_delivery_order_log_description,
                                            'user_id' => $data->user_id
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
                if($sync_id){
                    Session::flash('success_msg', 'Successfully synced. ');
                    return redirect()->route('sync_listing', ['tenant' => tenant('id')]);
                }else{
                    Session::flash('fail_msg', 'Invalid data in zip file');
                    return redirect()->route('sync_zip_file', ['tenant' => tenant('id')]);
                }
            }


            $post = (object) $request->all();
        }

        return view('sync_list/form', [
            'submit' => route('sync_zip_file', ['tenant' => tenant('id')]),
            'title' => 'Add',
            'post' => $post,

        ])->withErrors($validator);
    }

    public function revert_sync(Request $request){
        $sync_id = $request->input('sync_id');
        $sync = Sync::find($sync_id);
        if(!$sync){
            Session::flash("fail_msg", "Error, please try again later...");
            return redirect()->route('sync_listing', ['tenant' => tenant('id')]);
        }
        $is_revertable = DeliveryOrder::check_is_revertable($sync_id);
        if(!$is_revertable){
            Session::flash("fail_msg", "Sync could not be reverted");
            return redirect()->route('sync_listing', ['tenant' => tenant('id')]);
        }

        $delivery_order = DeliveryOrder::with('delivery_order_items','delivery_order_expense')
            ->where('sync_id', $sync_id)->get();
        $collect = Collect::where('sync_id', $sync_id)->get();
        $sync_delivery_order = SyncDeliveryOrder::where('sync_id', $sync_id)->get();
        $sync_collect = SyncCollect::where('sync_id', $sync_id)->get();

        if(!$delivery_order->isEmpty()){
            foreach($delivery_order as $do){
                if(!@$do->delivery_order_items->isEmpty()){
                    foreach($do->delivery_order_items as $doi){
                        $doi->delete();
                    }
                }
                if(!@$do->delivery_order_expense->isEmpty()){
                    foreach($do->delivery_order_expense as $doe){
                        $doe->delete();
                    }
                }
                $do->delete();
            }
        }
        if(!$sync_delivery_order->isEmpty()){
            foreach($sync_delivery_order as $sdo){
                if(!@$sdo->sync_delivery_order_items->isEmpty()){
                    foreach($sdo->sync_delivery_order_items as $sdoi){
                        $sdoi->delete();
                    }
                }
                $sdo->delete();
            }
        }
        if(!$collect->isEmpty()){
            foreach($collect as $c){
                $c->delete();
            }
        }
        if(!$sync_collect->isEmpty()){
            foreach($sync_collect as $sc){
                $sc->delete();
            }
        }

        $sync->update([
            'is_reverted' => 1
        ]);

        Session::flash("success_msg", "Successfully reverted sync");
        return redirect()->route('sync_listing', ['tenant' => tenant('id')]);

    }

    public function resync(Request $request){

        $sync_id = $request->input('sync_id');
        $sync = Sync::find($sync_id);

        if(!$sync || !@$sync->is_reverted){
            Session::flash("fail_msg", "Error, please try again later...");
            return redirect()->route('sync_listing', ['tenant' => tenant('id')]);
        }

        $media_url = $sync->getMedia('zip_files')->last()->getUrl();

        $fileee = file_get_contents($media_url);
        $tmpDir = sys_get_temp_dir();
        $temp_path = $tmpDir.'/test.zip';
        file_put_contents($temp_path, $fileee);

        $zip_archive = new ZipArchive;
        $zip_archive->open($temp_path);
        if($zip_archive->numFiles){
            $dir = null;
            for ($i = 0; $i < $zip_archive->numFiles; $i++) {
                $filename = $zip_archive->getNameIndex($i);
                if(pathinfo($filename,PATHINFO_EXTENSION)== 'txt'){
                    $txt_file = $zip_archive->getFromIndex($i);
                    if(!$txt_file){
                        Session::flash("fail_msg", "Error, no .txt file...");
                        return redirect()->route('sync_listing', ['tenant' => tenant('id')]);
                    }
                    $data = (object)json_decode($txt_file)->data;

                    $sync_id = $sync->sync_id;

                    if(@$data->sync_collect){
                        $sync_collect = $data->sync_collect;
                        foreach($sync_collect as $sc){
                            $row = (object) $sc;
                            $db_sync_collect = SyncCollect::firstOrCreate([
                                'sync_collect_code' => $row->sync_collect_code,
                                'sync_collect_created' => $row->sync_collect_created,
                            ],[
                                'product_id' => $row->product_id,
                                'setting_product_size_id' => $row->setting_product_size_id,
                                'sync_collect_quantity' => $row->sync_collect_quantity,
                                'sync_collect_created' => $row->sync_collect_created,
                                'sync_collect_updated' => $row->sync_collect_updated,
                                'sync_id' => $sync_id,
                                'company_land_id' => $row->company_land_id,
                                'sync_collect_date' => now(),
                                'sync_collect_code' => $row->sync_collect_code,
                                'sync_collect_status' => $row->sync_collect_status,
                                'user_id' => $data->user_id,
                                'sync_collect_remark' => @$row->sync_collect_remark,
                            ]);

                            if($db_sync_collect->wasRecentlyCreated){
                                $collect = Collect::create([
                                    'product_id' => $row->product_id,
                                    'setting_product_size_id' => $row->setting_product_size_id,
                                    'collect_quantity' => $row->sync_collect_quantity,
                                    'collect_created' => $row->sync_collect_created,
                                    'collect_updated' => $row->sync_collect_updated,
                                    'company_id' => $data->company_id,
                                    'company_land_id' => $row->company_land_id,
                                    'collect_date' => now(),
                                    'collect_code' => $row->sync_collect_code,
                                    'collect_status' => $row->sync_collect_status,
                                    'user_id' => $data->user_id,
                                    'sync_id' => $sync_id,
                                    'collect_remark' => @$row->sync_collect_remark
                                ]);

                                if(@$row->sync_collect_filename){
                                    $img_string = $zip_archive->getFromName($dir.$row->sync_collect_filename);
                                    if($img_string){
                                        $image = imagecreatefromstring($img_string);
                                        $temp= explode('.',$row->sync_collect_filename);
                                        $extension = end($temp);
                                        $temp_file = Media::generate_temp_media_from_string($image,$extension);
                                        $collect->addMedia($temp_file)->usingFileName($row->sync_collect_filename)->toMediaCollection('collect_media');
                                    }
                                }
                            }

                            $db_sync_collect->update([
                                'is_executed' => 1
                            ]);
                        }
                    }

                    if(@$data->sync_delivery_order){
                        $sync_delivery_order = $data->sync_delivery_order;
                        foreach($sync_delivery_order as $sdo){
                            $row = (object)$sdo;
                            $do_status_id = $row->sync_delivery_order_status_id;

                            if($row->sync_delivery_order_status_id == 4 || $row->sync_delivery_order_status_id == 5){ //Pending verification || Verified -> approved
                                $do_status_id = 2; //approved
                            }

                            $customer = Customer::where('customer_id', $row->customer_id)->first();
                            $db_sync_delivery_order = SyncDeliveryOrder::firstOrCreate([
                                'sync_delivery_order_no' => $row->sync_delivery_order_no,
                                'sync_delivery_order_created' => $row->sync_delivery_order_created,
                            ],[
                                'sync_delivery_order_no' => $row->sync_delivery_order_no,
                                'sync_delivery_order_total_quantity' => $row->sync_delivery_order_total_quantity,
                                'customer_id' => $row->customer_id,
                                'customer_name' => $row->customer_name,
                                'sync_id' => $sync_id,
                                'customer_ic' => $row->customer_ic,
                                'customer_mobile_no' => $row->customer_mobile_no,
                                'sync_delivery_order_date' => now(),
                                'company_land_id' => $row->company_land_id,
                                'sync_delivery_order_created' => $row->sync_delivery_order_created,
                                'sync_delivery_order_updated' => $row->sync_delivery_order_updated,
                                'sync_delivery_order_status_id' => $do_status_id,
                                'sync_delivery_order_type_id' => $row->sync_delivery_order_type_id,
                                'sync_delivery_order_remark' => @$row->sync_delivery_order_remark,
                            ]);
                            $sync_delivery_order_id = $db_sync_delivery_order->sync_delivery_order_id;

                            if($db_sync_delivery_order->wasRecentlyCreated){
                                $delivery_order = DeliveryOrder::create([
                                    'delivery_order_created' =>  $row->sync_delivery_order_created,
                                    'delivery_order_updated' =>  $row->sync_delivery_order_updated,
                                    'delivery_order_no' => $row->sync_delivery_order_no,
                                    'customer_id' => $row->customer_id,
                                    'customer_ic' => $row->customer_ic,
                                    'customer_mobile_no' => $row->customer_mobile_no,
                                    'customer_name' => $row->customer_name,
                                    'delivery_order_total_quantity' => $row->sync_delivery_order_total_quantity,
                                    'sync_id' => $sync_id,
                                    'delivery_order_status_id' => $do_status_id,
                                    'delivery_order_type_id' => $row->sync_delivery_order_type_id,
                                    'company_id' => $data->company_id,
                                    'company_land_id' => $row->company_land_id,
                                    'user_id' => $data->user_id,
                                    'delivery_order_remark' => @$row->sync_delivery_order_remark
                                ]);

                                if(@$row->sync_delivery_order_signature){
                                    $img_string = $zip_archive->getFromName($dir.$row->sync_delivery_order_signature);
                                    if($img_string){
                                        $image = imagecreatefromstring($img_string);
                                        imagesavealpha($image, true);
                                        imagealphablending($image, false);
                                        $temp= explode('.',$row->sync_delivery_order_signature);
                                        $extension = end($temp);
                                        $temp_file = Media::generate_temp_media_from_string($image,$extension);
                                        $delivery_order->addMedia($temp_file)->usingFileName($row->sync_delivery_order_signature)->toMediaCollection('delivery_order_signature');
                                    }
                                }

                                $customer_pic_exist = CustomerPIC::check_existing($row->customer_id, $row->customer_ic);

                                if(!$customer_pic_exist){
                                    CustomerPIC::create([
                                        'customer_pic_name' => $row->customer_name,
                                        'customer_pic_ic' => $row->customer_ic,
                                        'customer_pic_mobile_no' => $row->customer_mobile_no,
                                        'customer_id' => $row->customer_id,
                                        'customer_pic_created' => now(),
                                        'customer_pic_updated' => now()
                                    ]);
                                }

                                foreach($row->sync_delivery_order_item as $sdoi){
                                    $do_item = (object)$sdoi;
                                    $db_sync_delivery_order_item = SyncDeliveryOrderItems::create([
                                        'sync_delivery_order_id' => $sync_delivery_order_id,
                                        'product_id' => $do_item->product_id,
                                        'setting_product_size_id' => $do_item->setting_product_size_id,
                                        'sync_delivery_order_item_quantity' => $do_item->sync_delivery_order_item_quantity,
                                        'sync_delivery_order_item_collect_no' => @$do_item->collect_code,
                                        'sync_delivery_order_item_created' => $do_item->sync_delivery_order_item_created,
                                        'sync_delivery_order_item_updated' => $do_item->sync_delivery_order_item_updated,
                                        'no_collect_code' => @$do_item->no_collect_code??0,
                                    ]);

                                    $delivery_order_item = DeliveryOrderItem::create([
                                        'delivery_order_item_created' => $do_item->sync_delivery_order_item_created,
                                        'delivery_order_item_updated' => $do_item->sync_delivery_order_item_updated,
                                        'delivery_order_item_collect_no' => @$do_item->collect_code,
                                        'delivery_order_id' => $delivery_order->delivery_order_id,
                                        'product_id'=> $do_item->product_id,
                                        'setting_product_size_id'=> $do_item->setting_product_size_id,
                                        'delivery_order_item_quantity' => $do_item->sync_delivery_order_item_quantity,
                                        'no_collect_code' => @$do_item->no_collect_code??0,
                                    ]);

                                    if(@$do_item->sync_delivery_order_item_filename){
                                        $img_string = $zip_archive->getFromName($dir.$do_item->sync_delivery_order_item_filename);
                                        if($img_string){
                                            $image = imagecreatefromstring($img_string);
                                            $temp= explode('.',$do_item->sync_delivery_order_item_filename);
                                            $extension = end($temp);
                                            $temp_file = Media::generate_temp_media_from_string($image,$extension);
                                            $delivery_order_item->addMedia($temp_file)->usingFileName($do_item->sync_delivery_order_item_filename)->toMediaCollection('delivery_order_item_media');
                                        }
                                    }

                                    $db_sync_delivery_order_item->update([
                                        'delivery_order_item_id' => $delivery_order_item->delivery_order_item_id
                                    ]);

                                    if($delivery_order->delivery_order_type_id == 2 && $customer->warehouse_id){ //warehouse
                                        $product_stock_warehouse = ProductStockWarehouse::where([
                                            'warehouse_id' => $customer->warehouse_id,
                                            'product_id'=> $do_item->product_id,
                                            'setting_product_size_id'=> $do_item->setting_product_size_id,
                                        ])->first();

                                        if($product_stock_warehouse){
                                            $product_stock_warehouse_qty_new = $product_stock_warehouse->product_stock_warehouse_qty_current - $do_item->sync_delivery_order_item_quantity;
                                            $product_stock_transfer_description = Setting::get_by_slug('product_stock_transfer_description');
                                            ProductStockTransfer::create([
                                                'product_stock_transfer_description' => $product_stock_transfer_description,
                                                'product_stock_transfer_remark' => 'Item #'.$delivery_order_item->delivery_order_item_id.' has been transfer to warehouse #'.@$sdo->warehouse_id,
                                                'product_stock_transfer_qty' => $do_item->sync_delivery_order_item_quantity,
                                                'product_stock_transfer_qty_before' => $product_stock_warehouse->product_stock_warehouse_qty_current,
                                                'product_stock_transfer_qty_after' => $product_stock_warehouse_qty_new,
                                                'product_stock_warehouse_id' => @$product_stock_warehouse->product_stock_warehouse_id ?? 0,
                                                'product_stock_transfer_updated' => now(),
                                                'product_stock_transfer_created' => now(),
                                                'setting_product_size_id' => $do_item->setting_product_size_id,
                                                'product_id' => $do_item->product_id
                                            ]);

                                            $product_stock_warehouse->update([
                                                'product_stock_warehouse_qty_current' => $product_stock_warehouse_qty_new
                                            ]);
                                        }
                                    }
                                }
                                $sync_delivery_order_log = (object)$row->sync_delivery_order_log;
                                SyncDeliveryOrderLog::create([
                                    'sync_delivery_order_id' => $sync_delivery_order_id,
                                    'sync_delivery_order_log_action' => $sync_delivery_order_log->sync_delivery_order_log_action,
                                    'sync_delivery_order_log_description' => $sync_delivery_order_log->sync_delivery_order_log_description,
                                    'user_id' => $data->user_id,
                                    'sync_delivery_order_log_created' => $sync_delivery_order_log->sync_delivery_order_log_created
                                ]);

                                DeliveryOrderLog::create([
                                    'delivery_order_id' => $delivery_order->delivery_order_id,
                                    'delivery_order_log_created' => $sync_delivery_order_log->sync_delivery_order_log_created,
                                    'delivery_order_log_action' => $sync_delivery_order_log->sync_delivery_order_log_action,
                                    'delivery_order_log_description' => $sync_delivery_order_log->sync_delivery_order_log_description,
                                    'user_id' => $data->user_id
                                ]);
                            }
                        }
                    }
                    $sync->update([
                        'is_reverted' => 0
                    ]);

                    Session::flash("success_msg", "Successfully resynced");
                    return redirect()->route('sync_listing', ['tenant' => tenant('id')]);
                }
            }
        }

    }

    public function view_sync_company_expense($sync_id)
    {
        $search['sync_id'] = $sync_id;
        Session::put('sync_company_expense_listing', $search);
        return redirect()->route('sync_company_expense_listing', ['tenant' => tenant('id')]);
    }

    public function view_sync_formula_usage($sync_id)
    {
        $search['sync_id'] = $sync_id;
        Session::put('sync_formula_usage_search', $search);
        return redirect()->route('sync_formula_usage_listing', ['tenant' => tenant('id')]);
    }

    public function view_sync_do($sync_id)
    {
        $search['sync_id'] = $sync_id;
        Session::put('filter_syncDeliveryOrder', $search);
        return redirect()->route('sync_delivery_order_listing', ['tenant' => tenant('id')]);
    }

    public function view_sync_customer($sync_id)
    {
        $search['sync_id'] = $sync_id;
        Session::put('filter_syncCustomer', $search);
        return redirect()->route('synccustomer_listing', ['tenant' => tenant('id')]);
    }

    public function view_sync_daily($sync_id)
    {
        $search['sync_id'] = $sync_id;
        Session::put('filter_syncDaily', $search);
        return redirect()->route('daily_listing', ['tenant' => tenant('id')]);
    }

    public function view_sync_listing($sync_id)
    {
        $search['sync_id'] = $sync_id;
        Session::put('sync_listing', $search);
        return redirect()->route('sync_listing', ['tenant' => tenant('id')]);
    }

    public function ajax_find_sync_formula_usage_item_details(Request $request)
    {
      $sync_formula_usage_id = $request->input('formula_usage_id');
      $user_id = $request->input('user_id');

      // $sync_formula_usage_id = 5;
      // $user_id = 55;
      $result = SyncFormulaUsageItem::get_by_formula_id($sync_formula_usage_id, $user_id);

      return $result;
    }

}
