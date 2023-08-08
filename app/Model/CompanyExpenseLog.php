<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CompanyExpenseLog extends Model
{
    protected $table = 'tbl_company_expense_log';
    protected $primaryKey = 'company_expense_log_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'company_expense_log_created';
    const UPDATED_AT = null;

    protected $fillable = [
        'company_expense_id',
        'company_expense_log_created',
        'company_expense_log_description',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
