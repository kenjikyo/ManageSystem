<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPassword;
use App\Model\Log;
use App\Model\User;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendMailJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Session;
class ForgotPasswordController extends Controller
{
    public function getForgotPassword()
    {
        return view('Auth.Forgot-Password');
    }

    public function postForgotPassword(Request $request)
    {
        
        if(!$request->Email){ 
			return redirect()->route('getForgotPass')->with(['flash_level'=>'error', 'flash_message'=>'Missing Email']);
        }
        include(app_path() . '/functions/xxtea.php');
		
        $result = User::where('User_Email', $request->Email)->first();
        if(!$result){
            return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Email Error!']);
        }
        $pass = $this->generateRandomString(6);
        
        $token = Crypt::encryptString($result->User_ID.':'.time().':'.$pass);

        $json = Crypt::decryptString($token);
        $json = explode(':', $json);
        
		$data = array('User_Email'=>$request->Email, 'pass'=>$pass,'token'=>$token);
        
        // gửi mail thông báo
//         dd($data);
        dispatch(new SendMailJobs('Forgot', $data, 'New Password!', $result->User_ID));

        return redirect()->route('getLogin')->with(['flash_level'=>'success', 'flash_message'=>'Please. check your email! We sent a new password to the email address you are register']);
    }

    public function activePass(Request $req){
        include(app_path() . '/functions/xxtea.php');
        $json = Crypt::decryptString($req->token);
        $json = explode(':', $json);
        if(time() - $json[1] > 300){
            return redirect()->route('getLogin')->with(['flash_level'=>'error', 'flash_message'=>'Token expires!']);
        }

        //update pass
        $pass = User::where('User_ID', $json[0])->first();
        $pass->User_Password =bcrypt($json[2]);
        $pass->save();

        Session::put('user', $pass);        
		return redirect()->route('Dashboard')->with(['flash_level'=>'success', 'flash_message'=>'Login Success!']);
    }
    public function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
}
