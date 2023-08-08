<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SyncDaily extends Model
{
    protected $table = 'tbl_sync_collect';
    protected $primaryKey = 'sync_collect_id';
    const CREATED_AT = 'sync_collect_created';
    const UPDATED_AT = 'sync_collect_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'product_id',
        'setting_product_size_id',
        'sync_collect_quantity',
        'sync_collect_created',
        'sync_collect_updated',
        'sync_id',
        'is_executed',
        'company_land_id',
        'sync_collect_date',
        'sync_collect_code',
        'user_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function setting_product_size()
    {
        return $this->belongsTo(SettingSize::class, 'setting_product_size_id');
    }

    public function company_land()
    {
        return $this->belongsTo(CompanyLand::class, 'company_land_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public static function get_records($search, $perpage)
    {
        $syncdaily = Syncdaily::query();
        $user = Auth::user();
        $syncdaily->leftJoin('tbl_company_land as cl', 'cl.company_land_id', '=', 'tbl_sync_collect.company_land_id');

        if (@$search['freetext']){
            $freetext = $search['freetext'];
            $syncdaily->where(function ($q) use ($freetext) {
                $q->where('setting_product_size_id', 'like', '%' . $freetext . '%');
                $q->orWhere('sync_collect_code', 'like', '%' . $freetext . '%');
                $q->orWhere('sync_id', 'like', '%' . $freetext . '%');

                $q->orWhereHas('product', function ($q2) use ($freetext) {
                    $q2->where('product_name', 'like', '%' . $freetext . '%');
                });

                $q->orWhereHas('setting_product_size', function ($q2) use ($freetext) {
                    $q2->where('setting_product_size_name', 'like', '%' . $freetext . '%');
                });

                $q->orWhereHas('company_land', function ($q2) use ($freetext) {
                    $q2->where('company_land_name', 'like', '%' . $freetext . '%');
                });

                $q->orWhereHas('user', function ($q2) use ($freetext) {
                    $q2->where('user_fullname', 'like', '%' . $freetext . '%');
                });
            });
        }

        if (@$search['product_id']) {
            $syncdaily->where('product_id', $search['product_id']);
        }

        if (@$search['setting_product_size_id']) {
            $syncdaily->where('setting_product_size_id', $search['setting_product_size_id']);
        }

        if (@$search['company_land_id']) {
            $syncdaily->where('cl.company_land_id', $search['company_land_id']);
        }

        if (@$search['cc_from']) {
            $syncdaily->where('sync_collect_date', '>=', $search['cc_from'] . ' 00:00:00');
        }

        if (@$search['cc_from']) {
            $syncdaily->where('sync_collect_date', '<=', $search['cc_to'] . ' 23:59:59');
        }

        if (@$search['sync_id']) {
            $syncdaily->where('sync_id', $search['sync_id']);
        }

        // if (auth()->user()->company_id != 0) {
        //     $syncdaily->where('company_id', auth()->user()->company_id);
        //   }
        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
          $ids = array();
          foreach(auth()->user()->user_company as $key => $user_company){
            // $company->where('company_id', $user_company->company_id);
            $ids[$key] = $user_company->company_id;
            // dd($ids[$key]);
          }
          $syncdaily->whereIn('company_id', $ids);
        }else if(auth()->user()->company_id != 0){
           $syncdaily->where('company_id', auth()->user()->company_id);
        }


        $syncdaily->orderBy('sync_collect_created', 'DESC');
        return $syncdaily->paginate(10);
    }

}
