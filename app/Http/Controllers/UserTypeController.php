<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Model\UserType;
use App\Models\UserTypeReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserTypeController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'super_admin']);
    }

    public function listing()
    {
        return view('user_type.listing', [
            'records' => UserType::get_user_type(),
        ]);
    }

    public function manage_referral(Request $request, $id)
    {
        $active_product_id = array();
        $active_data = array();
        if ($request->isMethod('post')) {
            foreach ($request->all() as $key => $value) {
                switch ($key) {
                    case str_contains($key, 'formCheck'):
                        $product_id = substr($key, 10);
                        array_push($active_product_id, $product_id);
                        $active_data[$product_id]['stackable'] = 0;
                        break;
                    case str_contains($key, 'user_type_referral_discount_type'):
                        $product_id = substr($key, 33);
                        if (in_array($product_id, $active_product_id)) {
                            $active_data[$product_id]['type'] = $value;
                        }
                        break;
                    case str_contains($key, 'user_type_referral_discount_value'):
                        $product_id = substr($key, 34);
                        if (in_array($product_id, $active_product_id)) {
                            $active_data[$product_id]['value'] = $value;
                        }
                        break;
                    case str_contains($key, 'stackable'):
                        $product_id = substr($key, 10);
                        if (in_array($product_id, $active_product_id)) {
                            $active_data[$product_id]['stackable'] = 1;
                        }
                        break;
                    default:
                }
            }
            UserTypeReferral::query()
                ->where('user_type_id', '=', $id)
                ->delete();
            if ($active_data) {
                foreach ($active_data as $key => $value) {
                    UserTypeReferral::create([
                        'user_type_id' => $id,
                        'product_id' => $key,
                        'user_type_referral_discount_type' => $value['type'],
                        'user_type_referral_discount_value' => $value['value'],
                        'is_stackable' => $value['stackable']
                    ]);
                }
            }

            Session::flash('success_msg', 'Manage Successfully');
            return redirect()->route('user_type_listing', ['tenant' => tenant('id')]);
        }

        return view('user_type.referral', [
            'records' => Product::get_record_user_type_referral($id),
            'user_type_id' => $id
        ]);
    }

    public function add_referral(Request $request, $user_type_id)
    {
        $validator = null;

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required',
                'user_type_referral_discount_type' => 'required',
                'user_type_referral_discount_value' => $request->input('user_type_referral_discount_type') == 'amount' ? 'required|numeric|min:1|regex:/^\d+(\.\d{1,2})?$/' : 'required|numeric|min:1|max:100|regex:/^\d+(\.\d{1,2})?$/'
            ])->setAttributeNames([
                'product_id' => 'Product',
                'user_type_referral_discount_type' => 'Discount Type',
                'user_type_referral_discount_value' => 'Discount Value',
            ]);
            if (!$validator->fails()) {
                UserTypeReferral::create([
                    'product_id' => $request->input('product_id'),
                    'user_type_id' => $user_type_id,
                    'user_type_referral_discount_type' => $request->input('user_type_referral_discount_type'),
                    'user_type_referral_discount_value' => $request->input('user_type_referral_discount_value'),
                    'is_stackable' => 1
                ]);
                return redirect()->route('manage_referral', ['tenant' => tenant('id'), 'id' => $user_type_id])->with('message', 'Referral created successfully!');
            }
        }

        return view('user_type.referral_form', [
            'product_sel' => Product::get_product_sel(),
            'title' => 'Add',
            'submit' => route('add_referral', ['tenant' => tenant('id'), 'user_type_id' => $user_type_id]),
            'cancel' => route('manage_referral', ['tenant' => tenant('id'), 'id' => $user_type_id]),
            'post' => $request->all(),
        ])->withErrors($validator);
    }

    public function ajax_update_tag(Request $request)
    {
        $user_type_referral_id = $request->input('user_type_referral_id');
        $product_id = $request->input('product_id');
        $user_type_id = $request->input('user_type_id');
        $user_type_referral_discount_type = $request->input('user_type_referral_discount_type');
        $user_type_referral_discount_value = $request->input('user_type_referral_discount_value');
        $status = false;
        if ($user_type_referral_id) {
            UserTypeReferral::destroy($user_type_referral_id);
            $status = true;
        } else {
            UserTypeReferral::create([
                'product_id' => $product_id,
                'user_type_id' => $user_type_id,
                'user_type_referral_discount_type' => $user_type_referral_discount_type,
                'user_type_referral_discount_value' => $user_type_referral_discount_value ?? 0,
            ]);
            $status = true;
        }
        return response()->json(['status' => $status]);
    }

    public function ajax_update_user_type_referral_discount_type(Request $request)
    {
        $user_type_referral_discount_type = $request->input('user_type_referral_discount_type');
        $user_type_referral_id = $request->input('user_type_referral_id');
        if ($user_type_referral_id and $user_type_referral_discount_type) {
            UserTypeReferral::find($user_type_referral_id)
                ->update([
                    'user_type_referral_discount_type' => $user_type_referral_discount_type
                ]);
        }
    }
    public function ajax_update_user_type_referral_discount_value(Request $request)
    {
        $user_type_referral_id = $request->input('user_type_referral_id');
        $user_type_referral_discount_value = $request->input('user_type_referral_discount_value');
        $status = false;
        if ($user_type_referral_id and $user_type_referral_discount_value) {
            UserTypeReferral::find($user_type_referral_id)
                ->update([
                    'user_type_referral_discount_value' => $user_type_referral_discount_value
                ]);
        }
    }
}
