<?php

namespace App\Model;

use Carbon\Carbon;
use App\Model\ClaimItem;
use Illuminate\Http\Request;
use App\Model\CompanyExpense;
use App\Model\SettingExpense;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class CompanyExpenseItem extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $table = 'tbl_company_expense_item';
    protected $primaryKey = 'company_expense_item_id';
    const CREATED_AT = 'company_expense_item_created';
    const UPDATED_AT = 'company_expense_item_updated';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $monthFormat = 'm';
    protected $yearFormat = 'Y';

    protected $fillable = [
        'company_expense_id', 'setting_expense_id', 'company_expense_item_detail',
        'company_expense_item_unit', 'company_expense_item_unit_price', 'company_expense_item_total',
        'company_expense_item_created', 'company_expense_item_updated', 'is_claim',
        'company_expense_item_average_price_per_tree', 'supplier_id', 'remark'
    ];

    public function company_expense()
    {
        return $this->belongsTo(CompanyExpense::class, 'company_expense_id');
    }

    public function expense()
    {
        return $this->belongsTo(SettingExpense::class, 'setting_expense_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('company_expense_item_media')
            // ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('full')
                    // ->format('jpg')
                    ->apply();
                $this->addMediaConversion('thumb')
                    ->format('jpg')
                    ->crop('crop-center', 300, 300)
                    ->apply();
            });
    }

    public static function get_records($search)
    {
        $query = CompanyExpenseItem::query();


        $query = $query->paginate(10);

        return $query;
    }

    public static function get_expense_item_by_id($id)
    {
        $query = CompanyExpenseItem::find($id);

    }

    public static function get_ce_item_by_ce_item_id($ce_item_id)
    {
        $query = CompanyExpenseItem::query()
            ->where('company_expense_item_id', $ce_item_id)->get();

        $query->map(function($q) {
            if($q->hasMedia('company_expense_item_media')){
                $q->media_url = $q->getFirstMediaUrl('company_expense_item_media');
            }
        });
        return $query;

    }

    public static function get_img_expense_item_by_id($expense_id)
    {
        $query = CompanyExpenseItem::query()
            ->where('company_expense_item_id',$expense_id)->get();

        $media_url = [];

        $query->map(function($q) {
            if($q->hasMedia('company_expense_item_media')){
                foreach($q->getMedia('company_expense_item_media') as $mkey => $media){
                    $media_url[$mkey] = $media->getUrl();
                }
                $q->media_url = $media_url;
            }
        });
        return $query;

    }

    public static function get_company_expense_item_by_claim($claim){
        $company_expense_item_ids = ClaimItem::where([
            'claim_item_type' => 'company_expense_item_id',
            'is_deleted' => 0
        ])->whereHas('Claim',function ($query) use ($claim){
            $query->where('is_deleted',0);
            $query->where('claim_start_date',$claim->claim_start_date);
            $query->where('worker_id',$claim->worker_id);
        })
        ->pluck('claim_item_type_value');

        $month_data = Carbon::parse($claim->claim_start_date)->format('m');
        $year_data = Carbon::parse($claim->claim_start_date)->format('Y');
        $month = ltrim($month_data, "0");
        // dd($month);

        $query = CompanyExpenseItem::with('company_expense', 'expense')
            ->whereHas('company_expense', function($q) use($claim, $month, $year_data){
                $q->where('worker_id', $claim->worker_id)
                    ->where('company_id',$claim->company_id)
                    ->where('company_expense_status','completed')
                    ->where('company_expense_month', $month)
                    ->where('company_expense_year', $year_data);
                });

            if($company_expense_item_ids){
                $query->whereNotIn('company_expense_item_id',$company_expense_item_ids);
            }

            $query->where('is_claim', 1)
            ->where('claim_remaining_amount', '>', 0);

        return $query->get();
    }

}
