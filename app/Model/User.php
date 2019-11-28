<?php

namespace App\Model;

use Laravel\Passport\HasApiTokens;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use DB;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'User_Name', 'User_Email', 'User_Password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'User_Password', 'User_Token', 'User_OTP', 'remember_token', 'User_Log'
    ];
    
    protected $primaryKey = 'User_ID';
    
    public $timestamps = false;
    
	public function Invest(){
		return $this->hasMany('App\Model\Investment', 'investment_User')->where('investment_Status', 1);
	}
	
	public function Level(){
		return $this->belongsTo('App\Model\UserLevel', 'User_Agency_Level');
	}
	
    public function getAuthPassword(){
	    return $this->User_Password;
	}
	
	public static function getInfo($userID){
		$result = DB::table('users')
                        ->where('User_ID',$userID)
                        ->first();
        return $result;
	}
	
	public static function InsertRow($UserID, $username ,$password, $passwordUnHash, $parents = 0, $tree){
		$user = new User();
		
		$user->User_ID = $UserID; 
		$user->User_Name = $username; 
		$user->User_Password = $password; 
		$user->User_PasswordUnHash = $passwordUnHash; 
		$user->User_Parent = $parents; 
		$user->User_RegisteredDatetime = date('Y-m-d H:i:s'); 
		$user->User_Level = 0;
		$user->User_Status = 1;
		$user->User_Tree = $tree.','.$UserID;
		if($user->save()){
			return true;
		}
		return false;
	}
	
	public static function getF1($userID){
		$userList = User::where('User_Parent', $userID)->select('User_ID', 'User_Level','User_Agency_Level')->get();
		return $userList->toArray();
	}
    
    
}
