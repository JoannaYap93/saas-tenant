<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SyncCollect extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'tbl_sync_collect';
    protected $primaryKey = 'sync_collect_id';
    const CREATED_AT = 'sync_collect_date';
    const UPDATED_AT = NULL;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'product_id',
        'setting_product_size_id',
        'sync_collect_quantity',
        'sync_id',
        'is_executed',
        'company_land_id',
        'sync_collect_date',
        'sync_collect_code',
        'sync_collect_status',
        'user_id',
        'sync_collect_created',
        'sync_collect_updated'
    ];

    public static function get_count($sync_id)
    {
        $result = 0;

        $query = SyncCollect::query()
                            ->selectRaw('COUNT(sync_collect_id) as sync_collect_count')
                            ->where('sync_id', $sync_id)
                            ->groupBy('sync_id')
                            ->first();

        if(!empty($query))
        {
            $result = $query->sync_collect_count;
        }

        return $result;
    }

    public static function get_date($sync_id)
    {
        $array = array();

        $query = SyncCollect::query()
                ->selectRaw('sync_id, DATE(sync_collect_created) as collect_created, DATE(sync_collect_date) as collect_date')
                ->where('sync_id', $sync_id)
                ->get();

        if(!empty($query))
        {
            foreach($query as $result)
            {
                if(isset($array[$result->sync_id]))
                {

                    if(isset($array[$result->sync_id]['collect_created']) && !str_contains($array[$result->sync_id]['collect_created'], $result->collect_created)){
                        $array[$result->sync_id]['collect_created'] .= "<br>" . $result->collect_created;
                    }

                    if(isset($array[$result->sync_id]['collect_date']) && !str_contains($array[$result->sync_id]['collect_date'], $result->collect_date)){
                        $array[$result->sync_id]['collect_date'] .= "<br>" . $result->collect_date;
                    }

                }else{
                    $array[$result->sync_id]['collect_created'] = $result->collect_created;
                    $array[$result->sync_id]['collect_date'] = $result->collect_date;
                }

            }
        }

        return $array;
    }

    public function company_land()
    {
        return $this->belongsTo(CompanyLand::class, 'company_land_id');
    }
}
