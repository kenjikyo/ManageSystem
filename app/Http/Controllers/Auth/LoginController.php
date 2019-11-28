<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login;
use App\Model\User;
use App\Model\GoogleAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use function Composer\Autoload\includeFile;

class LoginController extends Controller
{

    public function getLogin()
    {
        return view('Auth.Login');
    }

    public function postLogin(Login $request)
    {
        $loginUser = User::where('User_Email', $request->email)->first();
		if($loginUser->User_EmailActive != 1){
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Please check your email and active this account!']);
		}
        if (!Hash::check($request->password, $loginUser->User_Password)) {
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Password incorrect']);
        }
		$auth = GoogleAuth::where('google2fa_User',$loginUser->User_ID)->first();
		if($auth){
			Session::put('auth',$auth);
			$otp = true;
			return redirect()->route('getLogin')->with(['otp'=>$otp]);
		}

        Session::put('user', $loginUser);

        return redirect()->route('Dashboard')->with(['flash_level' => 'success', 'flash_message' => 'Login successfully']);

    }

    public function getLogout()
    {
        
        // dd(session('user'),session('userTemp'));
        if(session('userTemp')){
            $sessionOld = session('userTemp');
            // bỏ session củ
            Session::forget('user');
            Session::forget('userTemp');

            // tạo session mới
            Session::put('user', $sessionOld);

            return redirect()->route('Dashboard')->with(['flash_level'=>'success', 'flash_message'=>'Logout Success']);
        }

        Session::forget('user');
        return redirect()->route('getLogin');
    }

	public function postLoginCheckOTP(Request $request){
		$auth = Session('auth');
		$google2fa = app('pragmarx.google2fa');
		$valid = $google2fa->verifyKey($auth->google2fa_Secret, $request->otp);
		if($valid){
			$user = User::find($auth->google2fa_User);

		    Session::put('user', $user);

			return 1;
		}
		return 0;
	}
}
