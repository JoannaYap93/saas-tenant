<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class CustomerCategory extends Model
{
    use HasSlug;
    protected $table = 'tbl_customer_category';
    protected $primaryKey = 'customer_category_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'customer_category_created';
    const UPDATED_AT = 'customer_category_updated';

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('customer_category_name')
            ->saveSlugsTo('customer_category_slug');
    }

    protected $fillable = [
        'customer_category_id',
        'customer_category_name',
        'customer_category_slug',
        'is_deleted',
        'customer_category_created',
        'customer_category_updated',
        'customer_category_status',
        'customer_category_priority',
        'company_id'
    ];

    public static function get_records($search, $perpage)
    {
        $customerCategory = CustomerCategory::query();

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $customerCategory->where(function ($q) use ($freetext) {
                $q->where('customer_category_name', 'like', '%' . $freetext . '%');
                $q->orWhere('customer_category_slug', 'like', '%' . $freetext . '%');
            });
        }

        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $ids = auth()->user()->user_company->pluck('company_id')->toArray();
            array_push($ids,0);

            $customerCategory->whereIn('company_id', $ids);
        }else if(auth()->user()->company_id != 0){
            $customerCategory->whereIn('company_id', [0, auth()->user()->company_id]);
        }

        $customerCategory->where('is_deleted', '!=', '1')->get();
        $customerCategory->orderBy('company_id', 'ASC');
        $customerCategory->orderBy('customer_category_created', 'DESC');

        return $customerCategory->paginate($perpage);
    }

    public function customer_category_status()
    {
        return $this->belongsTo(CustomerCategoryStatus::class, 'customer_category_status_id');
    }


    public static function get_customer_category_is_deleted()
    {
        $customerCategory = CustomerCategory::query();
        $customerCategory->where('is_deleted', '=', 1)
            ->get();

        return $customerCategory;
    }

    public static function customer_category_name($customer_category_name)
    {
        $customerCategory = CustomerCategory::query();
        $customerCategory->where('customer_category_name', $customer_category_name);

        if (auth()->user()->company_id != 0) {
            $customerCategory->where('company_id', auth()->user()->company_id);
        }

        $result = optional($customerCategory->first())->customer_category_slug;

        return $result;
    }

    public static function customer_category_for_report_sel()
    {
        $customerCategory = CustomerCategory::query();

        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $ids = array();
            foreach(auth()->user()->user_company as $key => $user_company){
                // $company->where('company_id', $user_company->company_id);
                // $ids[$key] = $user_company->company_id;
                // dd($ids[$key]);
                array_push($ids,$user_company->company_id);
            }
            $customerCategory->whereIn('company_id', [0, json_encode($ids)]);
        }else if(auth()->user()->company_id != 0){
            $customerCategory->whereIn('company_id', [0, auth()->user()->company_id]);
        }

        $customerCategory->where('is_deleted', '!=', '1');
        $customerCategory->where('customer_category_status', '!=', 'inactive');
        $customerCategory->orderBy('company_id', 'ASC');

        $customerCategory->orderBy('customer_category_created', 'DESC');
        $category = $customerCategory->get();

        $temp[''] = 'Please select Category';
        foreach ($category as $value) {
            $temp[$value->customer_category_id] = $value->customer_category_name;
        }
        return $temp;

    }

    public static function get_customer_category_sel()
    {
        $category = CustomerCategory::where('is_deleted', '!=', 1);
        if (auth()->user()->company_id != 0) {
        $category = $category->whereIn('company_id', [0, auth()->user()->company_id]);
        // $category = $category->where('company_id', auth()->user()->company_id);
        }
        $category = $category->orderBy('company_id', 'ASC');
        $category = $category->get();
        // dd($category);

        $temp[''] = 'Please select Category';
        foreach ($category as $value) {
            $temp[$value->customer_category_id] = $value->customer_category_name;
        }
        return $temp;
    }

    public static function get_first_category()
    {
        $user = auth()->user();
        $query = CustomerCategory::query()->where('company_id', $user->company_id)->first();
        return $query;
    }
}
