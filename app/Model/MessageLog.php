<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MessageLog extends Model
{
    protected $table = 'tbl_message_log';
    protected $primaryKey = 'message_log_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'message_log_created';
    const UPDATED_AT = null;

    protected $fillable = [
        'message_log_id',
        'user_id',
        'customer_id',
        'message_template_id',
        'message_log_contents',
        'message_log_status',
        'message_log_created',
        'message_log_slug',
        'message_log_ref_id',
    ];


    //REPORTING
    public static function get_by_month($search){

        $result = array();

        $query = MessageLog::query();
        $query->selectRaw('MONTH( tbl_message_log.message_log_created ) AS month,
                            tbl_message_log.message_template_id as id, tbl_company.company_id as company, tbl_product.product_id as product,
                            count( tbl_message_log.message_log_id ) AS result')
            ->join('tbl_message_template','tbl_message_log.message_template_id','tbl_message_template.message_template_id')
            ->join('tbl_user','tbl_user.user_id','tbl_message_log.user_id')
            ->join('tbl_company','tbl_company.company_id','tbl_company.company_id')
            ->join('tbl_product','tbl_product.product_id','tbl_product.product_id')
            ->join('tbl_company_land','tbl_company_land.company_land_id','tbl_company_land.company_land_id')
            ->where('tbl_message_template.is_reporting',1)
            ->where('tbl_message_template.is_deleted',0)
            ->where('tbl_message_template.message_template_status',1);

            if (isset($search['message_template_id']) && $search['message_template_id'] >0) {
                $query->where('tbl_message_log.message_template_id',$search['message_template_id']);
            }

            if (isset($search['year']) && $search['year'] >0) {
                $query->whereYear('tbl_message_log.message_log_created', '=', $search['year']);
            }

            if (isset($search['month']) && $search['month'] >0) {
                $query->whereMonth('tbl_message_log.message_log_created', '=', $search['month']);
            }

            if (isset($search['day']) && $search['day'] >0) {
                $query->whereDay('tbl_message_log.message_log_created', '=', $search['day']);
            }

            if (isset($search['company_cb_id'])) {
                $query->whereIn('tbl_company.company_id', $search['company_cb_id']);
            }

            if (isset($search['company_land_cb_id'])) {
                $query->whereIn('tbl_company_land.company_land_id', $search['company_land_cb_id']);
            }

            if(isset($search['product_cb_id'])) {
                $query->whereIn('tbl_product.product_id', $search['product_cb_id']);
            }

            if (isset($search['customer_id'])) {
                $query->where('tbl_message_log.customer_id', $search['customer_id']);
            }

            $query->groupByRaw('MONTH( tbl_message_log.message_log_created ),tbl_message_log.message_template_id');

            $temp = $query->get();

            foreach ($temp as $rows) {
                $result[$rows->id][$rows->month] = $rows->result;
            }

        return $result;
    }

    public static function get_by_day($search){

        $result = array();

        $query = MessageLog::query();
        $query->selectRaw('DAY( tbl_message_log.message_log_created ) AS day,
                            tbl_message_log.message_template_id as id,
                            count( tbl_message_log.message_log_id ) AS result')
            ->join('tbl_message_template','tbl_message_log.message_template_id','tbl_message_template.message_template_id')
            ->join('tbl_user','tbl_user.user_id','tbl_message_log.user_id')
            ->where('tbl_message_template.is_reporting',1)
            ->where('tbl_message_template.is_deleted',0)
            ->where('tbl_message_template.message_template_status',1);

            if (isset($search['message_template_id']) && $search['message_template_id'] >0) {
                $query->where('tbl_message_log.message_template_id',$search['message_template_id']);
            }

            if (isset($search['year']) && $search['year'] >0) {
                $query->whereYear('tbl_message_log.message_log_created', '=', $search['year']);
            }

            if (isset($search['month']) && $search['month'] >0) {
                $query->whereMonth('tbl_message_log.message_log_created', '=', $search['month']);
            }

            if (isset($search['day']) && $search['day'] >0) {
                $query->whereDay('tbl_message_log.message_log_created', '=', $search['day']);
            }

            $query->groupByRaw('DAY( tbl_message_log.message_log_created ),tbl_message_log.message_template_id');

            $temp = $query->get();

            foreach ($temp as $rows) {
                $result[$rows->id][$rows->day] = $rows->result;
            }

        return $result;
    }

    public static function get_by_user($search){
        $result = array();

        $query = MessageLog::query();
        $query->selectRaw('tbl_message_log.user_id,
                        tbl_message_log.customer_id,
                        tbl_message_log.message_template_id,
                        tbl_message_log.message_log_slug,
                        tbl_message_log.message_log_ref_id,
                        count( tbl_message_log.message_log_id ) AS result,
                        GROUP_CONCAT(tbl_message_log.message_log_created ORDER BY tbl_message_log.message_log_created DESC) as dates')
            ->join('tbl_message_template','tbl_message_log.message_template_id','tbl_message_template.message_template_id')
            ->where('tbl_message_template.is_reporting',1)
            ->where('tbl_message_template.is_deleted',0)
            ->where('tbl_message_template.message_template_status',1);

            if (isset($search['message_template_id']) && $search['message_template_id'] >0) {
                $query->where('tbl_message_log.message_template_id',$search['message_template_id']);
            }

            if (isset($search['year']) && $search['year'] >0) {
                $query->whereYear('tbl_message_log.message_log_created', '=', $search['year']);
            }

            if (isset($search['month']) && $search['month'] >0) {
                $query->whereMonth('tbl_message_log.message_log_created', '=', $search['month']);
            }

            if (isset($search['day']) && $search['day'] >0) {
                $query->whereDay('tbl_message_log.message_log_created', '=', $search['day']);
            }

            $query->groupByRaw('tbl_message_log.user_id, tbl_message_log.message_template_id, tbl_message_log.message_log_ref_id');
            $result = $query->get();
        return $result;

    }

    public function user(){
        return $this->hasOne('App\Model\User', 'user_id','user_id');
    }

    public function customer(){
        return $this->hasOne('App\Model\Customer', 'customer_id','customer_id');
    }

    public function message_template(){
        return $this->hasOne('App\Model\MessageTemplate', 'message_template_id','message_template_id');
    }

    public function message_template_involve(){
        return $this->hasOne('App\Model\MessageTemplateInvolve', 'message_template_involve_slug','message_log_slug');
    }

    public function delivery_order(){
        return $this->hasOne('App\Model\DeliveryOrder', 'delivery_order_id','message_log_ref_id');
    }

    public function invoice(){
        return $this->hasOne('App\Model\Invoice', 'invoice_id','message_log_ref_id');
    }

}
