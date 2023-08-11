<?php

namespace App\Model;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ClaimItem extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'tbl_claim_item';
    protected $primaryKey = 'claim_item_id';
    const CREATED_AT = 'claim_item_created';
    const UPDATED_AT = 'claim_item_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'claim_id',	'claim_item_date', 'claim_item_name', 'claim_item_value', 'claim_item_amount', 'claim_item_amount_claim', 'is_deleted', 'is_rejected', 'claim_item_rejected_by', 'claim_item_rejected_date', 'claim_item_rejected_remark', 'claim_item_type', 'claim_item_type_value'
    ];

    public static function get_claim_item_for_pdf($claim_id){
        $query = ClaimItem::query()
                          ->where('claim_id', $claim_id)
                          ->where('is_deleted', 0)
                          ->where('is_rejected', 0)
                          ->orderBy('claim_item_date', 'ASC')
                          ->get();

        $arr = [];
        $media_arr = [];
        if($query){
            foreach ($query as $key => $value) {
                $arr[$value->claim_item_id]['date'] = $value->claim_item_date;
                $arr[$value->claim_item_id]['amount'] = $value->claim_item_amount;
                $arr[$value->claim_item_id]['amount_claim'] = $value->claim_item_amount_claim;
                $arr[$value->claim_item_id]['claim_item_name'] = $value->claim_item_name;
                $arr[$value->claim_item_id]['claim_item_type'] = $value->claim_item_type;
                $arr[$value->claim_item_id]['claim_item_type_value'] = $value->claim_item_type_value;

                if($value->claim_item_type == 'company_expense_item_id'){
                    $arr[$value->claim_item_id]['category_id'] = @$value->company_expense_item->company_expense->expense_category->setting_expense_category_id;
                }elseif ($value->claim_item_type == 'raw_material_company_usage_id') {
                    $arr[$value->claim_item_id]['category_id'] = @$value->raw_material_company_usage->raw_material->setting_raw_category->raw_material_category_id;
                }elseif ($value->claim_item_type == 'manually_company_expense_item_category_id') {
                    $arr[$value->claim_item_id]['category_id'] = $value->claim_item_type_value;
                }elseif ($value->claim_item_type == 'manually_raw_material_company_usage_category_id') {
                    $arr[$value->claim_item_id]['category_id'] = $value->claim_item_type_value;      
                }

                if($value->hasMedia('claim_media')){
                    foreach($value->getMedia('claim_media') as $mkey => $media){
                    $media_arr[$mkey] = $media->getUrl();
                    }
                }
            }
         }
        //  dd($arr);

        $arr2 = [];
        $company_expense_query_category = ClaimItem::selectRaw('tbl_setting_expense_category.setting_expense_category_name as category_name,
                                        tbl_setting_expense_category.setting_expense_category_id as category_id,
                                        tbl_claim_item.claim_item_type as claim_item_type')
                                ->join('tbl_company_expense_item', 'tbl_company_expense_item.company_expense_item_id', 'claim_item_type_value')
                                ->join('tbl_company_expense', 'tbl_company_expense.company_expense_id', 'tbl_company_expense_item.company_expense_id')
                                ->join('tbl_setting_expense_category', 'tbl_setting_expense_category.setting_expense_category_id', 'tbl_company_expense.setting_expense_category_id')
                                ->where('tbl_claim_item.claim_item_type', 'company_expense_item_id')
                                ->where('tbl_claim_item.claim_id', $claim_id)
                                ->groupBy('tbl_setting_expense_category.setting_expense_category_name')
                                ->get()->toArray();

        $manually_company_expense_query_category = ClaimItem::selectRaw('tbl_setting_expense_category.setting_expense_category_name as category_name,
                                        tbl_setting_expense_category.setting_expense_category_id as category_id,
                                        tbl_claim_item.claim_item_type as claim_item_type')
                                ->join('tbl_setting_expense_category', 'tbl_setting_expense_category.setting_expense_category_id', 'tbl_claim_item.claim_item_type_value')
                                ->where('tbl_claim_item.claim_item_type', 'manually_company_expense_item_category_id')
                                ->where('tbl_claim_item.claim_id', $claim_id)
                                ->groupBy('tbl_setting_expense_category.setting_expense_category_name')
                                ->get()->toArray();


        $rmcu_query_category = ClaimItem::selectRaw('tbl_raw_material_category.raw_material_category_name as category_name,
                                        tbl_raw_material_category.raw_material_category_id as category_id,
                                        tbl_claim_item.claim_item_type as claim_item_type')
                                ->join('tbl_raw_material_company_usage', 'tbl_raw_material_company_usage.raw_material_company_usage_id', 'tbl_claim_item.claim_item_type_value')
                                ->join('tbl_raw_material', 'tbl_raw_material.raw_material_id', 'tbl_raw_material_company_usage.raw_material_id')
                                ->join('tbl_raw_material_category', 'tbl_raw_material_category.raw_material_category_id', 'tbl_raw_material.raw_material_category_id')
                                ->where('tbl_claim_item.claim_item_type', 'raw_material_company_usage_id')
                                ->where('tbl_claim_item.claim_id', $claim_id)
                                ->groupBy('tbl_raw_material_category.raw_material_category_name')
                                ->get()->toArray();
        
        $manually_rmcu_query_category = ClaimItem::selectRaw('tbl_raw_material_category.raw_material_category_name as category_name,
                                tbl_raw_material_category.raw_material_category_id as category_id,
                                tbl_claim_item.claim_item_type as claim_item_type')
                        ->join('tbl_raw_material_category', 'tbl_raw_material_category.raw_material_category_id', 'tbl_claim_item.claim_item_type_value')
                        ->where('tbl_claim_item.claim_item_type', 'manually_raw_material_company_usage_category_id')
                        ->where('tbl_claim_item.claim_id', $claim_id)
                        ->groupBy('tbl_raw_material_category.raw_material_category_name')
                        ->get()->toArray();

        // dd($arr, $rmcu_query_category, $manually_rmcu_query_category);
        // dd($company_expense_query_category ,$manually_company_expense_query_category);

        if($company_expense_query_category){
            $arr2 += $company_expense_query_category;  
        }

        if($manually_company_expense_query_category){
            foreach($manually_company_expense_query_category as $data){
            array_push($arr2, $data);    
            }   
        } 

        if($rmcu_query_category){
            foreach($rmcu_query_category as $data){
                array_push($arr2, $data);    
                }   
        }

        if($manually_rmcu_query_category){
            foreach($manually_rmcu_query_category as $data){
                array_push($arr2, $data);    
                }   
        }

        // dd($arr2, $arr);

        return ['result' => $arr , 'category' => $arr2, 'media' => $media_arr];
    }

    public static function get_by_id($claim_item_id){
        return ClaimItem::with('claim', 'company_expense_item')
            ->where([
                'claim_item_id' => $claim_item_id,
                'is_deleted' => 0
            ])->first();
    }

    public static function get_img_claim_item_by_id($claim_item_id)
    {
        $query = ClaimItem::query()
            ->where('claim_item_id',$claim_item_id)->get();

        $media_url = [];

        $query->map(function($q) {
            if($q->hasMedia('claim_item_media')){
                foreach($q->getMedia('claim_item_media') as $mkey => $media){
                    $media_url[$mkey] = $media->getUrl();
                }
                $q->media_url = $media_url;
            }
        });
        return $query;

    }

    public function claim()
    {
        return $this->belongsTo(Claim::class, 'claim_id');
    }

    public function company_expense_item()
    {
        return $this->belongsTo(CompanyExpenseItem::class, 'claim_item_type_value', 'company_expense_item_id')
          ->join('tbl_claim_item', 'tbl_claim_item.claim_item_type_value', 'company_expense_item_id')
          ->where('claim_item_type', 'company_expense_item_id');
    }

    public function raw_material_company_usage()
    {
        return $this->belongsTo(RawMaterialCompanyUsage::class,  'claim_item_type_value', 'raw_material_company_usage_id')
        ->join('tbl_claim_item', 'tbl_claim_item.claim_item_type_value', 'raw_material_company_usage_id')
        ->where('claim_item_type', 'raw_material_company_usage_id');
    }

    public function getCompanyExpenseUrlAttribute()
    {
        $route = null;
        if($this->claim_item_type == 'company_expense_item_id'){
            $company_expense_id = CompanyExpenseItem::where('company_expense_item_id', $this->claim_item_type_value)->value('company_expense_id');
            if($company_expense_id){
                $route = route('company_expense_edit', ['tenant' => tenant('id'), 'id' => $company_expense_id]);
            }
        }
        return $route;
    }
}
