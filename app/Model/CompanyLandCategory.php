<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class CompanyLandCategory extends Model
{
    protected $table = 'tbl_company_land_category';

    protected $primaryKey = 'company_land_category_id';

    const CREATED_AT = 'company_land_category_created';
    const UPDATED_AT = 'company_land_category_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'company_land_category_name', 'company_land_category_created',
        'company_land_category_updated', 'is_deleted', 'company_farm_id'
    ];

    public static function get_land_category_sel()
    {
        $land_category = CompanyLandCategory::query()->where('is_deleted', 0)->get();
        $temp[''] = 'Please Select Category';
        foreach ($land_category as $category) {
            $temp[$category->company_land_category_id] = $category->company_land_category_name;
        }
        return $temp;
    }

    public static function get_record($search, $perpage)
    {

        $company = CompanyLandCategory::query();

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $company->where(function ($q) use ($freetext) {
                $q->where('tbl_company_land_category.company_land_category_id', 'like', '%' . $freetext . '%')
                    ->orWhere('tbl_company_land_category.company_land_category_name', 'like', '%' . $freetext . '%');
            });
        }

        if (@$search['company_land_category_id']) {
            $company = $company->where('tbl_company_land_category.company_land_category_id', $search['company_land_category_id']);
        }

        $company = $company->where('is_deleted', 0);

        $company->orderBy('company_land_category_created', 'DESC');
        return $company->paginate($perpage);
    }

    public static function get_farm_cat($id)
    {
        $query = CompanyLandCategory::query()->where('company_farm_id', $id)->where('is_deleted', 0)->get();
        return $query;
    }

    public static function get_all_cat()
    {
        $result = [];
        $query = CompanyLandCategory::query()->get();
        foreach ($query as $key => $value) {
            $result[$key] = ['id' => $value->company_land_category_id, 'name' => $value->company_land_category_name];
        }
        return $result;
    }

    public function company_farm()
    {
        return $this->belongsTo(CompanyFarm::class, 'company_farm_id');
    }
}
