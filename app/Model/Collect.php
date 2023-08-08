<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Log;

class Collect extends Model implements HasMedia
{
	use InteractsWithMedia;

	protected $table = 'tbl_collect';

	protected $primaryKey = 'collect_id';

	const CREATED_AT = NULL;
	const UPDATED_AT = NULL;
	protected $dateFormat = 'Y-m-d H:i:s';

	protected $fillable = [
		'product_id',
		'setting_product_size_id',
		'collect_quantity',
		'company_id',
		'company_land_id',
		'collect_date',
		'collect_code',
		'collect_status',
        'collect_remark',
		'user_id',
		'sync_id',
        'collect_created',
        'collect_updated',
	];

	public static function get_records($search)
	{
		// $company_id = auth()->user()->company_id ?? null;


		$query = Collect::query()->with(['product', 'setting_product_size'])
                                 ->whereHas('company', function($q){
                                    $q->where('is_display', '=', 1);
                                 });
		if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
			$ids = array();
			foreach(auth()->user()->user_company as $key => $user_company){
				// $company->where('company_id', $user_company->company_id);
				$ids[$key] = $user_company->company_id;
				// dd($ids[$key]);
			}
			$query->whereIn('company_id', $ids);
		}else if(auth()->user()->company_id != 0){
			 $company_id = auth()->user()->company_id;
		}
        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->where('collect_code', 'like', '%' . $freetext . '%');
				$q->orWhereHas('company_land', function ($q2) use ($freetext) {
                    $q2->where('company_land_name', 'like', '%' . $freetext . '%');
                    $q2->orWhere('company_land_code', 'like', '%' . $freetext . '%');
				});
                $q->orWhereHas('company', function ($q2) use ($freetext) {
                    $q2->where('company_name', 'like', '%' . $freetext . '%');
                    $q2->orWhere('company_code', 'like', '%' . $freetext . '%');
                    $q2->orWhere('company_reg_no', 'like', '%' . $freetext . '%');
                    $q2->orWhere('company_email', 'like', '%' . $freetext . '%');
					$q2->orWhere('company_phone', 'like', '%' . $freetext . '%');
					$q2->orWhere('company_address', 'like', '%' . $freetext . '%');
                });
            });
        }

        if (@$search['collect_from']) {
            $query->whereDate('collect_date', '>=', DATE($search['collect_from']) . ' 00:00:00');
        }

        if (@$search['collect_to']) {
            $query->whereDate('collect_date', '<=', DATE($search['collect_to']) . ' 23:59:59');
        }

        if (@$search['company_id']) {
            $query->where('company_id', $search['company_id']);
        }

        if (@$search['company_land_id']) {
            $query->where('company_land_id', $search['company_land_id']);
        }

        if(@$search['product_category_id']) {
            $product_category_id = $search['product_category_id'];

            $query->whereHas('product', function($q) use($product_category_id){
                $q->where('product_category_id', $product_category_id);
            });
        }

        if (@$search['product_id']) {
			$query->where('product_id', $search['product_id']);
        }

        if (@$search['product_size_id']) {
			$query->where('setting_product_size_id', $search['product_size_id']);
        }

		if (@$search['collect_status']) {
			$query->where('collect_status', $search['collect_status']);
        }

        if (@$search['user_id']) {
            $query->where('user_id', $search['user_id']);
        }

        if (@$company_id && $company_id != 0) {
            $query->where('company_id', $company_id);
        }

        $query->orderBy('collect_created', 'desc')->where('collect_status', '!=', 'deleted');

        $result = $query->paginate(15);

        return $result;
	}

	public static function get_count($sync_id)
	{
		$result = 0;

		$query = Collect::query()
				->selectRaw('COUNT(collect_id) as collect_count')
				->where('sync_id', $sync_id)
				->groupBy('sync_id')
				->first();

		if(!empty($query))
		{
			$result = $query->collect_count;
		}

		return $result;
	}

	public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function company_land()
    {
        return $this->belongsTo(CompanyLand::class, 'company_land_id');
    }

	public function product()
	{
		return $this->belongsTo(Product::class, 'product_id');
	}

	public function setting_product_size()
	{
		return $this->belongsTo(SettingSize::class, 'setting_product_size_id');
	}

	public static function get_collect_code_from_do($company_id, $company_land_id, $product_id, $setting_product_size_id, $date)
	{
		$query = Collect::query();
		$query->where('company_id', $company_id);
		$query->where('company_land_id', $company_land_id);
		$query->where('product_id', $product_id);
		$query->where('setting_product_size_id', $setting_product_size_id);
		// $query->where('collect_quantity', $quantity);
		$query->whereDate('collect_date', date($date));

		$result = $query->first();
		// Log::info($result->collect_code);
		return $result;
	}
}
