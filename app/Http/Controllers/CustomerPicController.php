<?php

namespace App\Http\Controllers;

use App\Model\CustomerPIC;
use Illuminate\Http\Request;
use Log;

class CustomerPicController extends Controller
{
    public function __construct()
    {
        return $this->middleware(['auth']);
    }

    public function ajax_check_pic(Request $request)
    {
        $ic = $request->input('customer_ic');
        // var_dump($ic);
        $pic = CustomerPIC::get_pic_ic($ic);
        if ($pic) {
            return ['data' => $pic, 'status' => true];
        } else {
            return ['data' => '', 'status' => false];
        }
    }

    public function ajax_check_pic_id(Request $request)
    {
        $freetext = $request->input('term');
        $id = $request->input('id');
        $result = CustomerPIC::get_pic_cid($freetext, $id);
        // return response()->json($result);
        return $result;
    }

    public function ajax_search_customer_pic_by_id(Request $request)
    {
        if ($request->isMethod('post')) {
            $customer_pic_ic = $request->input('customer_ic');
            $customer_id = $request->input('c_id');
            $result = CustomerPIC::get_by_customer_pic_ic($customer_pic_ic, $customer_id);
            return response()->json(['data' => $result, 'status' => $result ? true : false]);
        } else {
            $customer_pic_ic = $request->input('term');
            $customer_id = $request->input('c_id');
            $result = CustomerPIC::get_by_customer_pic_ic($customer_pic_ic, $customer_id, true);
            // dd(response()->json(['data' => $result, 'status' => $result ? true : false]));
            return response()->json(['results' => $result, 'status' => $result ? true : false]);
        }
    }
}
