<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PaymentUrl extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'tbl_payment_url';
    protected $primaryKey = 'payment_url_id';
    const CREATED_AT = 'payment_url_created';
    const UPDATED_AT = 'payment_url_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'customer_id',
        'payment_url_total',
        'payment_url_status',
        'user_id'
    ];

    public function payment_url_item()
    {
        return $this->hasMany('App\Model\PaymentUrlItem', 'payment_url_id')->where('is_deleted', 0);
    }

    public function payment_url_log()
    {
        return $this->hasMany('App\Model\PaymentUrlLog', 'payment_url_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Model\Customer', 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User', 'user_id');
    }

    public static function get_records($search){
		$company_id = auth()->user()->company_id ?? null;

        $query = PaymentUrl::with('payment_url_item','payment_url_log','customer','user');

        if(@$search['freetext']){
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->orWhereHas('customer', function ($q2) use ($freetext) {
                    $q2->where('customer_name', 'like', '%' . $freetext . '%');
                    $q2->orWhere('customer_address', 'like', '%' . $freetext . '%');
                    $q2->orWhere('customer_city', 'like', '%' . $freetext . '%');
                    $q2->orWhere('customer_state', 'like', '%' . $freetext . '%');
                });
                $q->orWhereHas('payment_url_item', function ($q2) use ($freetext) {
                    $q2->whereHas('invoice', function ($q3) use ($freetext) {
                        $q3->Where('invoice_no', 'like', '%' . $freetext . '%');
                    });
                });
            });
        }

        if (@$search['pu_from']) {
            $query->where('payment_url_created', '>=', $search['pu_from'] . ' 00:00:00');
        }

        if (@$search['pu_from']) {
            $query->where('payment_url_created', '<=', $search['pu_to'] . ' 23:59:59');
        }

        if (@$search['customer_id']){
            $query->where('customer_id', $search['customer_id']);
        }

        if (@$search['payment_url_status']){
            $query->where('payment_url_status', $search['payment_url_status']);
        }else{
          $query->where('payment_url_status', '!=', 'Cancelled');
        }

        if (@$search['company_id']) {
            $query->whereHas('user', function($q) use($search){
                $q->where('company_id', $search['company_id']);
            });
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    $ids[$key] = $user_company->company_id;
                }
                $query->whereHas('user', function($q) use($ids){
                    $q->whereIn('company_id', $ids);
                });
            } else if (auth()->user()->company_id != 0) {
                $query->whereHas('user', function($q){
                    $q->where('company_id', auth()->user()->company_id);
                });
            } else {
                $query->whereHas('user', function($q){
                    $q->where('company_id', '<>', 1);
                });
            }
        }
        // if (@$search['company_id']) {
        //     $query->whereHas('user', function($q) use($search){
        //         $q->where('company_id', $search['company_id']);
        //     });

        // }

        if (@$company_id && $company_id != 0) {
            $query->whereHas('user', function($q) use($company_id){
                $q->where('company_id', $company_id);
            });
        }

        $result = $query->paginate(15);
        return $result;
    }

    public static function get_payment_url_status(){
        $status = [
            '0' => 'Pending',
            '1' => 'Pending Approval',
            '2' => 'Paid',
            '3' => 'Cancelled',
        ];

        return $status;
    }

    public static function count_status($search){
		$company_id = auth()->user()->company_id ?? null;

        $status = PaymentUrl::get_payment_url_status();
        for ($i = 0; $i < count($status); $i++) {
            $query = PaymentUrl::with('payment_url_item','payment_url_log','customer')
                ->where('payment_url_status', $status[$i]);
            if(@$search['freetext']){
                $freetext = $search['freetext'];
                $query->where(function ($q) use ($freetext) {
                    $q->orWhereHas('customer', function ($q2) use ($freetext) {
                        $q2->where('customer_name', 'like', '%' . $freetext . '%');
                        $q2->orWhere('customer_address', 'like', '%' . $freetext . '%');
                        $q2->orWhere('customer_city', 'like', '%' . $freetext . '%');
                        $q2->orWhere('customer_state', 'like', '%' . $freetext . '%');
                    });
                    $q->orWhereHas('payment_url_item', function ($q2) use ($freetext) {
                        $q2->whereHas('invoice', function ($q3) use ($freetext) {
                            $q3->Where('invoice_no', 'like', '%' . $freetext . '%');
                        });
                    });
                });
            }

            if (@$search['pu_from']) {
                $query->where('payment_url_created', '>=', $search['pu_from'] . ' 00:00:00');
            }

            if (@$search['pu_from']) {
                $query->where('payment_url_created', '<=', $search['pu_to'] . ' 23:59:59');
            }

            if (@$search['customer_id']){
                $query->where('customer_id', $search['customer_id']);
            }

            if (@$search['company_id']) {
                $query->whereHas('user', function($q) use($search){
                    $q->where('company_id', $search['company_id']);
                });
            } else {
                if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                    $ids = array();
                    foreach (auth()->user()->user_company as $key => $user_company) {
                        $ids[$key] = $user_company->company_id;
                    }
                    $query->whereHas('user', function($q) use($ids){
                        $q->whereIn('company_id', $ids);
                    });
                } else if (auth()->user()->company_id != 0) {
                    $query->whereHas('user', function($q){
                        $q->where('company_id', auth()->user()->company_id);
                    });
                } else {
                    $query->whereHas('user', function($q){
                        $q->where('company_id', '<>', 1);
                    });
                }
            }

            if (@$company_id && $company_id != 0) {
                $query->whereHas('user', function($q) use($company_id){
                    $q->where('company_id', $company_id);
                });
            }

            $result[$i] = $query->count('payment_url_id');
        }
        if ($result) {
            $result['default'] = $result[0] + $result[1] + $result[2];
        }
        return $result;
    }

    public static function get_payment_url_details_by_id($payment_url_id, $ajax = false)
    {
        $query = PaymentUrl::with('payment_url_item.invoice','customer')
            ->where('payment_url_id', $payment_url_id);
        $result = $query->first();

        $img = null;
        if($result){
            $img = $result->hasMedia('payment_slip') ? $result->getFirstMediaUrl('payment_slip', 'thumb') : null;
        }

        if($ajax){
            return response()->json(['data' => $result, 'status' => true, 'img' => $img]);
        }else{
            return $result;
        }
    }
}
