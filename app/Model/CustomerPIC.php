<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerPIC extends Model
{
    protected $table = 'tbl_customer_pic';
    protected $primaryKey = 'customer_pic_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'customer_pic_created';
    const UPDATED_AT = 'customer_pic_updated';

    protected $fillable = [
        'customer_pic_name',
        'customer_pic_created',
        'customer_pic_updated',
        'customer_pic_ic',
        'customer_id',
        'customer_pic_mobile_no',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public static function get_pic_ic($ic)
    {
        $query = CustomerPIC::query()->where('customer_pic_ic', $ic)->first();
        return $query;
    }

    public static function get_pic_customer_id($customer_id)
    {
        $query = CustomerPIC::query()->where('customer_id', $customer_id)->get();
        return $query;
    }

    public static function get_pic_cid($ic, $id = null)
    {
        $select = array();
        $set = array();
        // $query = CustomerPIC::query();
        // if ($id != null) {
        //     $query = $query->where('customer_id', $id);
        // }
        // $query = $query->where('customer_pic_ic', 'like', '%' . $ic . '%')->limit(5);
        $query = DB::table('tbl_customer_pic')->where('customer_pic_ic', 'like', '%' . $ic . '%')->limit(5);
        $query = $query->get();
        if ($query) {
            foreach ($query as $key => $value) {
                if (in_array($value->customer_pic_ic, $set) == false) {
                    array_push($select, array(
                        'id' => $value->customer_pic_id,
                        'label' => $value->customer_pic_name,
                        'value' => $value->customer_pic_ic,
                        'mobile' => $value->customer_pic_mobile_no
                    ));
                    array_push($set, $value->customer_pic_ic);
                }
            }
        }
        return $select;
    }

    public static function check_existing($customer_id, $customer_ic)
    {
        $exist = false;
        $customer_pic = CustomerPic::where('customer_id', $customer_id)
            ->where('customer_pic_ic', $customer_ic)
            ->first();
        if ($customer_pic) {
            $exist = true;
        }
        return $exist;
    }

    public static function get_by_customer_pic_ic($customer_pic_ic, $customer_id, $select = false)
    {
        $list = [];
        $query = CustomerPIC::query();
        $query->join('tbl_customer', 'tbl_customer.customer_id', 'tbl_customer_pic.customer_id');
        // if (auth()->user()->company_id && auth()->user()->company_id != 0) {
        //     $query->where('company_id', auth()->user()->company_id);
        // }
        if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
            $ids = array();
            foreach (auth()->user()->user_company as $key => $user_company) {
                // $company->where('company_id', $user_company->company_id);
                $ids[$key] = $user_company->company_id;
                // dd($ids[$key]);
            }
            $query->whereIn('tbl_customer.company_id', $ids);
        } else if (auth()->user()->company_id != 0) {
            $query->where('tbl_customer.company_id', auth()->user()->company_id);
        }
        // $query->where('company_id', 2);
        if ($select == true) {
            if ($customer_pic_ic) {
                $query->where('customer_pic_ic', 'like', '%' . $customer_pic_ic . '%');
            }
            $query->where('tbl_customer_pic.customer_id', $customer_id);
            $query = $query->get();
            if ($query) {
                foreach ($query as $key => $value) {
                    array_push($list, array(
                        'id' => $value->customer_pic_ic,
                        'text' => $value->customer_pic_name . ($value->customer_pic_ic ? ' - ' . $value->customer_pic_ic : '')
                    ));
                }
            }
        } else {
            if ($customer_pic_ic) {
                $query->where('customer_pic_ic', $customer_pic_ic);
            }
            $query->where('tbl_customer_pic.customer_id', $customer_id);
            $query = $query->get();
            if ($query) {
                foreach ($query as $key => $value) {
                    array_push($list, array(
                        'ic' => $value->customer_pic_ic,
                        'pic_name' => $value->customer_pic_name,
                        'pic_mobile' => $value->customer_pic_mobile_no,
                        // 'label' => strtoupper($value->customer_mobile_no),
                        // 'value' => strtoupper($value->customer_mobile_no),
                        // 'name' => $value->customer_name,
                        // 'company' => $value->customer_company_name,
                        // 'company_id' => $value->company_id,
                        // 'address' => $value->customer_address,
                        // 'address2' => $value->customer_address2,
                        // 'state' => $value->customer_state,
                        // 'city' => $value->customer_city,
                        // 'postcode' => $value->customer_postcode,
                        // 'country' => $value->customer_country,
                        // 'warehouse' => $value->warehouse_id
                    ));
                }
            }
        }

        return $list;
    }
}
