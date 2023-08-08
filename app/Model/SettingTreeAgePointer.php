<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SettingTreeAgePointer extends Model
{
    protected $table ='tbl_setting_tree_age_pointer';

    protected $primaryKey = 'setting_tree_age_pointer_id';

    const CREATED_AT = 'setting_tree_age_pointer_created';
    const UPDATED_AT = 'setting_tree_age_pointer_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'product_id',
        'setting_tree_age_id',
        'setting_tree_age_pointer_value',
    ];

    public function setting_tree_age()
    {
        return $this->belongsTo(SettingTreeAge::class, 'setting_tree_age_id', 'setting_tree_age_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
