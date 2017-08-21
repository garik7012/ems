<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailStat extends Model
{
    public $timestamps = false;

    public static function logEmail($ent_id, $user_id, $from_email, $to_email, $subject, $data)
    {
        $log = new self();
        $log->enterprise_id = $ent_id;
        $log->user_id = $user_id;
        $log->from_email = $from_email;
        $log->to_email = $to_email;
        $log->subject = $subject;
        $log->data = $data;
        $log->created_at = date('Y-m-d H:i:s');
        $log->save();
    }
}
