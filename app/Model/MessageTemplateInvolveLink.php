<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MessageTemplateInvolveLink extends Model
{
    protected $table = 'tbl_message_template_involve_link';
    protected $primaryKey = 'message_template_involve_link_id';
    public $timestamps = false;

    protected $fillable = [
        	'message_template_involve_id',
            'message_template_id'
    ];

    public function message_template(){
        return $this->hasMany('App\Model\MessageTemplate', 'message_template_id','message_template_id' );
    }

    public function message_template_involve(){
        return $this->belongsTo('App\Model\MessageTemplateInvolve', 'message_template_involve_id','message_template_involve_id' );
    }
}
