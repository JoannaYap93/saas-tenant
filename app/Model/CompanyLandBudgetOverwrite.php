<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CompanyLandBudgetOverwrite extends Model
{
    protected $table = 'tbl_company_land_budget_overwrite';

    protected $primaryKey = 'company_land_budget_overwrite_id';

    const CREATED_AT = 'company_land_budget_overwrite_created';
    const UPDATED_AT = 'company_land_budget_overwrite_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
      'company_land_id', 'company_land_budget_overwrite_type', 'company_land_budget_overwrite_value',
      'company_id', 'company_land_budget_overwrite_type_id', 'user_id'
    ];

}
