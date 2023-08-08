<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Customer extends Model
{
    protected $table = 'tbl_customer';

    protected $primaryKey = 'customer_id';

    const CREATED_AT = 'customer_created';
    const UPDATED_AT = 'customer_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'customer_id', 'customer_company_name', 'customer_name', 'customer_mobile_no', 'customer_address',
        'customer_address2', 'customer_state', 'customer_city', 'customer_code', 'customer_country',
        'customer_created', 'customer_updated', 'company_id', 'customer_email', 'customer_postcode',
        'customer_category_id', 'warehouse_id', 'customer_acc_name', 'customer_acc_mobile_no',
        'customer_credit','customer_status'
    ];

    public static function get_record($search, $perpage)
    {
        $customer = Customer::query();

        $customer->with('company');
        $customer->whereHas('company', function($q){
            $q->where('is_display', '=', 1);
        });
        $customer->with('customer_category');
        $customer->with('warehouse');

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $customer->where(function ($q) use ($freetext) {
                $q->where('tbl_customer.customer_email', 'like', '%' . $freetext . '%')
                    ->orWhere('tbl_customer.customer_mobile_no', 'like', '%' . $freetext . '%')
                    ->orWhere('tbl_customer.customer_name', 'like', '%' . $freetext . '%')
                    ->orWhere('tbl_customer.customer_acc_name', 'like', '%' . $freetext . '%')
                    ->orWhere('tbl_customer.customer_acc_mobile_no', 'like', '%' . $freetext . '%');
                $q->orWhereHas('warehouse', function ($q2) use ($freetext) {
                    $q2->where('warehouse_name', 'like', '%' . $freetext . '%');
                });
            });
        }

        if (@$search['company_id']) {
            $customer = $customer->where('company_id', $search['company_id']);
        }

        if (@$search['customer_status']) {
            $customer = $customer->where('customer_status', $search['customer_status']);
        }

        if (@$search['customer_category_id']) {
            $customer = $customer->where('customer_category_id', $search['customer_category_id']);
        }

        if (@auth()->user()->company_id && auth()->user()->company_id != 0) {
            $customer = $customer->where('company_id', auth()->user()->company_id);
        }

        $customer->orderBy('customer_created', 'DESC');
        return $customer->paginate(15);
    }

    public static function get_company_customer()
    {
        $query = Customer::query()
                    ->selectRaw('DISTINCT(customer_company_name) as customer_company_name')
                    ->pluck('customer_company_name');
        $customer = $query->toArray();
        $result = array();
        foreach($customer as $c){
            $result[$c] = $c;
        }

        return $result;

    }

    public static function get_customer_status_sel()
    {
        return [
            'activate' => 'Activate',
            'inactivate' => 'Inactivate',

        ];
    }

    public static function check_customer_mobile_exist($customer_mobile_no, $customer_id = 0)
    {
        $result = false;
        $query = Customer::where([
            'customer_mobile_no' => $customer_mobile_no,
        ]);
        if ($customer_id > 0) {
            $query->where('customer_id', '!=', $customer_id);
        }
        $check_customer = $query->first();

        if ($check_customer) {
            $result = true;
        }
        return $result;
    }

    public static function get_by_mobile_no($mobile_no, $select = false)
    {
        $list = [];
        $query = Customer::query();

        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $ids = array();
            foreach(auth()->user()->user_company as $key => $user_company){
                $ids[$key] = $user_company->company_id;
            }
            $query->whereIn('company_id', $ids);
        }else if(auth()->user()->company_id != 0){
           $query->where('company_id', auth()->user()->company_id);
        }

        if ($select == true) {
            if ($mobile_no) {
                $query->where('customer_mobile_no', 'like', '%' . $mobile_no . '%');
            }
            $query = $query->get();
            if ($query) {
                foreach ($query as $key => $value) {
                    array_push($list, array(
                        'id' => $value->customer_id,
                        'text' => $value->customer_id . '. '. $value->customer_company_name . ' - ' . $value->customer_mobile_no
                    ));
                }
            }
        } else {
            if ($mobile_no) {
                $query->where('customer_id', $mobile_no);
            }
            $query = $query->get();
            if ($query) {
                foreach ($query as $key => $value) {
                    array_push($list, array(
                        'id' => $value->customer_id,
                        'label' => strtoupper($value->customer_mobile_no),
                        'value' => strtoupper($value->customer_mobile_no),
                        'name' => $value->customer_name,
                        'company' => $value->customer_company_name,
                        'company_id' => $value->company_id,
                        'address' => $value->customer_address,
                        'address2' => $value->customer_address2,
                        'state' => $value->customer_state,
                        'city' => $value->customer_city,
                        'type' => $value->customer_category_id,
                        'postcode' => $value->customer_postcode,
                        'country' => $value->customer_country,
                        'warehouse' => $value->warehouse_id
                    ));
                }
            }
        }
        // Log::info($list);

        return $list;
    }

    public static function get_by_customer_name($customer_name, $select = false)
    {
        $list = [];
        $query = Customer::query()->groupBy('customer_code');

        // if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
        //     $ids = array();
        //     foreach(auth()->user()->user_company as $key => $user_company){
        //         $ids[$key] = $user_company->company_id;
        //     }
        //     $query->whereIn('company_id', $ids);
        // }else if(auth()->user()->company_id != 0){
        //    $query->where('company_id', auth()->user()->company_id);
        // }

        if ($select == true) {
            if ($customer_name) {
                $query->where('customer_name', 'like', '%' . $customer_name . '%');
            }
            $query = $query->get();
            if ($query) {
                foreach ($query as $key => $value) {
                    array_push($list, array(
                        'id' => $value->customer_name,
                        'text' => $value->customer_name . ' - ' . $value->customer_code
                    ));
                }
            }
        } else {
            if ($customer_name) {
                $query->where('customer_name', $customer_name);
            }
            $query = $query->get();
            if ($query) {
                foreach ($query as $key => $value) {
                    array_push($list, array(
                        'id' => $value->customer_id,
                        'customer_code' => $value->customer_code,
                        'label' => strtoupper($value->customer_mobile_no),
                        'value' => strtoupper($value->customer_mobile_no),
                        'name' => $value->customer_name,
                        'email' => $value->customer_email,
                        'category' => $value->customer_category_id,
                        'company' => $value->customer_company_name,
                        'company_id' => $value->company_id,
                        'customer_acc_name' => $value->customer_acc_name,
                        'customer_acc_mobile_no' => $value->customer_acc_mobile_no,
                        'address' => $value->customer_address,
                        'address2' => $value->customer_address2,
                        'state' => $value->customer_state,
                        'city' => $value->customer_city,
                        'postcode' => $value->customer_postcode,
                        'country' => $value->customer_country,
                        'warehouse' => $value->warehouse_id
                    ));
                }
            }
        }

        return $list;
    }

    public static function get_by_customer_id($id)
    {
        $customer_arr = [];
        $query = Customer::find($id);
        if ($query) {
            $customer_arr[0] = [$query->customer_name, $query->customer_mobile_no, $query->customer_company_name];
        }
        return $customer_arr;
    }

    public static function get_customer_sel()
    {
        $company_id = auth()->user()->company_id;
        $result = array();

        $query = Customer::query();

        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $ids = array();
            foreach(auth()->user()->user_company as $key => $user_company){
                $ids[$key] = $user_company->company_id;
            }
            $query->whereIn('company_id', $ids);
        }else if(auth()->user()->company_id != 0){
            $query->where('company_id', $company_id);
        }

        $result = $query->orderBy('customer_company_name')->get();
        $data = array();
        if ($result) {
            foreach($result as $row){
                $data[$row->customer_id] = $row->customer_company_name.' - '.$row->customer_name;
            }
        }
        return $data;
    }

    public static function get_customer_by_company($search)
    {
        $company_id = auth()->user()->company_id;
        $result = array();

        $query = Customer::query();

        if(@$search['company_id']){
            $query->where('company_id', $search['company_id']);
        }elseif(auth()->user()->user_type_id != 1){
            if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
                $ids = array();
                foreach(auth()->user()->user_company as $key => $user_company){
                    $ids[$key] = $user_company->company_id;
                }
                $query->whereIn('company_id', $ids);
            }else if(auth()->user()->company_id != 0){
                $query->where('company_id', $company_id);
            }
        }

        $result = $query->orderBy('customer_company_name')->get();
        $data = array();
        if ($result) {
            foreach($result as $row){
                $data[$row->customer_id] = $row->customer_company_name .'-'. $row->customer_name;
            }
        }
        return $data;
    }

    public static function get_customer_by_company_id_and_customer_company_name($company_id,$customer_company_name){
        $query = Customer::query()
            ->where('company_id', $company_id)
            ->where('customer_company_name', $customer_company_name);
        $result = $query->first();
        return $result;
    }

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }

    public function customer_category()
    {
        return $this->belongsTo('App\Model\CustomerCategory', 'customer_category_id')->where('is_deleted', '!=', 1);
    }

    public function customer_pic()
    {
        return $this->hasMany(CustomerPIC::class, 'customer_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(SettingWarehouse::class, 'warehouse_id');
    }

    public function customer_credit_history()
    {
        return $this->hasMany('App\Model\CustomerCreditHistory', 'customer_id');
    }
}
