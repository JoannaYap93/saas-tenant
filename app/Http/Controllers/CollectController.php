<?php

namespace App\Http\Controllers;

use App\Model\Collect;
use App\Model\Company;
use App\Model\CompanyLand;
use App\Model\Product;
use App\Model\ProductCategory;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CollectController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function listing(Request $request)
    {
        $search = array();

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');

            switch ($submit_type) {
                case 'search':
                    session(['filter_collect' => [
                        'freetext' => $request->input('freetext'),
                        'company_id' => $request->input('company_id'),
                        'collect_from' => $request->input('collect_from'),
                        'collect_to' => $request->input('collect_to'),
                        'company_land_id' => $request->input('company_land_id'),
                        'product_category_id' => $request->input('product_category_id'),
                        'product_id' => $request->input('product_id'),
                        'product_size_id' => $request->input('product_size_id'),
                        'collect_status' => $request->input('collect_status'),
                        'user_id' => $request->input('user_id'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('filter_collect');
                    break;
            }
        }

        $search = session('filter_collect') ?? $search;

        return view('collect.listing', [
            'submit' => route('collect_listing'),
            'records' => Collect::get_records($search),
            'search' => $search,
            'collect_status_sel' => [
                ''=>'Please Select Status',
                'pending' => 'Pending',
                'delivered' => 'Delivered',
                'completed' => 'Completed',
                'deleted' => 'Deleted'
            ],
            'company_sel' => Company::get_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'product_category_sel' => ProductCategory::get_category_sel(),
            'product_sel' => Product::get_by_company(),
            'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(),
        ]);
    }

    public function delete(Request $request)
    {
        $collect_id = $request->input('collect_id');
        $collect_log_description = $request->input('collect_log_description');
        $collect = Collect::find($collect_id);
        if (!$collect) {
            Session::flash("fail_msg", "Error, please try again later...");
            return redirect()->route('collect_listing');
        }

        if (!$collect_log_description) {
            Session::flash("fail_msg", "Remark field is required");
            return redirect()->route('collect_listing');
        }

        $collect->update([
            'collect_status' => 'Deleted', //deleted
            'collect_remark' => $collect_log_description,
        ]);

        Session::flash('success_msg', 'Successfully deleted collect  - '.$collect->collect_code);
        return redirect()->route('collect_listing');
    }

    public function edit(Request $request)
    {
        $collect_id = $request->input('collect_id');
        $company_land_id = $request->input('company_land_id');

        $collect = Collect::find($collect_id);
        if($collect){
          $collect->update([
            'company_land_id' => $company_land_id,
          ]);

          Session::flash('success_msg', 'Successfully Updated collect  - '.$collect->collect_code);
          return redirect()->route('collect_listing');
        }else{
          Session::flash('fail_msg', 'No Collect Found...');
          return redirect()->route('collect_listing');
        }
    }
}
