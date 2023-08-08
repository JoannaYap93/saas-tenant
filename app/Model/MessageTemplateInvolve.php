<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class MessageTemplateInvolve extends Model
{
    protected $table = 'tbl_message_template_involve';
    protected $primaryKey = 'message_template_involve_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'message_template_involve_created';
    const UPDATED_AT = 'message_template_involve_updated';

    protected $fillable = [
        'message_template_involve_type',
        'message_template_involve_slug',
        'is_deleted',
    ];

    public static function get_records($search, $perpage)
    {
        $query = MessageTemplateInvolve::query();

        if (@$search['freetext']) {
            $query->Where('message_template_involve_type', 'like', '%' . $search['freetext'] . '%');
        }

        $query->where('is_deleted',0);

        $result = $query->orderBy('message_template_involve_updated', 'desc')->paginate($perpage);
        return $result;
    }

    public static function get_by_id($message_template_involve_id){
        $query = MessageTemplateInvolve::query();
        $query->where('message_template_involve_id',$message_template_involve_id)
              ->where('is_deleted',0);
        $result = $query->first();
        return $result;
    }

    public static function get_as_selection()
    {
        $query = MessageTemplateInvolve::query();
        $result = $query->select('message_template_involve_id', 'message_template_involve_type')
            ->where('is_deleted',0)
            ->orderBy('message_template_involve_type', 'ASC')
            ->get();
        return $result;
    }

    public function message_template_involve_link(){
        return $this->hasMany('App\Model\MessageTemplateInvolveLink', 'message_template_involve_id','message_template_involve_id' );
    }
}
