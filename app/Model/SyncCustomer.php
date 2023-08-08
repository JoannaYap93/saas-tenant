<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SyncCustomer extends Model
{
    protected $table = 'tbl_sync_customer';
    protected $primaryKey = 'sync_customer_id';
    const CREATED_AT = 'sync_customer_created';
    const UPDATED_AT = 'sync_customer_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'sync_customer_created',
        'sync_customer_updated',
        'sync_customer_company_name',
        'sync_customer_name',
        'sync_customer_mobile',
        'sync_customer_email',
        'sync_customer_code',
        'sync_customer_address',
        'sync_customer_address2',
        'sync_customer_state',
        'sync_customer_city',
        'sync_customer_postcode',
        'sync_id',
        'is_executed',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public static function get_records($search)
    {
        $syncCustomer = SyncCustomer::query();

        if (@$search['freetext']){
            $freetext = $search['freetext'];
            $syncCustomer->where(function ($q) use ($freetext) {
                $q->where('sync_customer_company_name', 'like', '%' . $freetext . '%');
                $q->orWhere('sync_customer_name', 'like', '%' . $freetext . '%');
                $q->orWhere('sync_customer_mobile', 'like', '%' . $freetext . '%');
                $q->orWhere('sync_id', 'like', '%' . $freetext . '%');
                $q->orWhereHas('company', function ($q2) use ($freetext) {
                    $q2->where('company_name', 'like', '%' . $freetext . '%');
                });

            });
        }

        if (@$search['company_id']) {
            $syncCustomer->where('company_id', $search['company_id']);
        }

        if (@$search['sync_id']) {
            $syncCustomer->where('sync_id', $search['sync_id']);
        }

        // if (auth()->user()->company_id != 0) {
        //     $syncCustomer->where('company_id', auth()->user()->company_id);
        //   }
        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
          $ids = array();
          foreach(auth()->user()->user_company as $key => $user_company){
            // $company->where('company_id', $user_company->company_id);
            $ids[$key] = $user_company->company_id;
            // dd($ids[$key]);
          }
          $syncCustomer->whereIn('company_id', $ids);
        }else if(auth()->user()->company_id != 0){
           $syncCustomer->where('company_id', auth()->user()->company_id);
        }


        $syncCustomer->orderBy('sync_customer_created', 'DESC');
        return $syncCustomer->paginate(10);
    }

}
