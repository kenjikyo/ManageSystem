<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Investment extends Model
{
    protected $table = "investment";

    protected $fillable = ['investment_ID','investment_User', 'investment_Amount','investment_Rate', 'investment_Hash', 'investment_Currency', 'investment_Time', 'investment_Status'];

    public $timestamps = false;

    protected $primaryKey = 'investment_ID';

}
