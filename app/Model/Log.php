<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Log extends Model{
    protected $table = "log";

    public $timestamps = true;

    const CREATED_AT = 'Log_CreatedAt';
	const UPDATED_AT = 'Log_UpdatedAt';

    public static function insertLog($user, $action, $amount, $comment){
	    $result = new Log;
	    $result->Log_User = $user;
	    $result->Log_Action = $action;
	    $result->Log_Amount = $amount;
	    $result->Log_Comment = $comment;
	    $result->Log_Status = 1;
	    $result->save();
        return $result;
    }
}
