<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CompanyExpenseLand extends Model
{
    protected $table = 'tbl_company_expense_land';
    protected $primaryKey = 'company_expense_land_id';
    // protected $dateFormat = 'Y-m-d H:i:s';
    // const CREATED_AT = 'company_expense_log_created';
    // const UPDATED_AT = null;
    public $timestamps = false;

    protected $fillable = [
        'company_expense_land_id',
        'company_expense_id',
        'company_land_id',
        'company_expense_land_total_tree',
        'company_expense_land_total_price'
    ];

    public function company_land()
    {
        return $this->belongsTo(CompanyLand::class, 'company_land_id');
    }
}
