<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    protected $table = 'tbl_message_template';
    protected $primaryKey = 'message_template_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'message_template_created';
    const UPDATED_AT = 'message_template_updated';

    protected $fillable = [
        'message_template_name',
        'message_template_content',
        'message_template_mobile_no',
        'message_template_status',
        'is_deleted',
        'admin_id',
        'is_reporting'
    ];

    public static function get_records($search, $perpage)
    {
        $query = MessageTemplate::query();

        if (@$search['freetext']) {
            $query->Where('message_template_name', 'like', '%' . $search['message_template_name'] . '%');
        }

        if (@$search['message_template_involve_id']) {

            $query->whereHas('message_template_involve_link', function($q) use ($search)  {
                $q->where([
                    'message_template_involve_id' => $search['message_template_involve_id'],
                ]);
            });
        }

        $query->where('is_deleted',0)
              ->with('message_template_involve_link');

        $result = $query->orderBy('message_template_updated', 'desc')->paginate($perpage);
        return $result;
    }

    public static function get_by_id($message_template_id){
        $query = MessageTemplate::query();
        $query->with('message_template_involve_link')
            ->where('message_template_id',$message_template_id)
            ->where('is_deleted',0);
        $result = $query->first();
        return $result;
    }

    public static function get_by_ids($message_template_ids){
        $query = MessageTemplate::query();
        $query->whereIn('message_template_id',$message_template_ids)
            ->where('message_template_status',1)
            ->where('is_deleted',0);
        $result = $query->get();
        return $result;
    }

    public static function get_by_slug($slug){
        $query = MessageTemplate::query();
        $query->join('tbl_message_template_involve_link','tbl_message_template_involve_link.message_template_id','tbl_message_template.message_template_id')
            ->join('tbl_message_template_involve','tbl_message_template_involve.message_template_involve_id','tbl_message_template_involve_link.message_template_involve_id')
            ->where('tbl_message_template.is_deleted',0)
            ->where('tbl_message_template.message_template_status',1)
            ->where('tbl_message_template_involve.is_deleted',0)
            ->where('tbl_message_template_involve.message_template_involve_slug',$slug);
        $result = $query->get();
        return $result;
    }

    public static function get_reporting(){
        $query = MessageTemplate::query();
        $query->with('message_template_involve_link')
            ->where('message_template_status',1)
            ->where('is_reporting',1)
            ->where('is_deleted',0);
        $result = $query->get();
        return $result;
    }

    public function message_template_involve_link(){
        return $this->hasMany('App\Model\MessageTemplateInvolveLink', 'message_template_id','message_template_id' );
    }
}
