<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Register;
use App\Model\Log;
use App\Model\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use App\Jobs\SendMailJobs;
class RegisterController extends Controller
{


    public function getRegister(Request $request)
    {
        return view('Auth.Register');
    }

    public function postRegister(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users,User_Email|max:255',
            'password' => 'required|min:6|max:255',
            'password_comfirm' => 'required|same:password'
        ]);
        $sponsor = 8812781365;
        if($request->sponser){
            $sponsor = $request->sponser;
        }

        $sponserInfo = User::where('User_ID', $sponsor)->first();

        if(!$sponserInfo){
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Sponsor ID does not exist']);
        }

        $userID = $this->RandomIDUser();

        $userTree = $sponserInfo->User_Tree . "," . $userID;
        //Tạo token cho mail
        $token = Crypt::encryptString($request->email.':'.time());

        $userData = [
            'User_ID' => $userID,
            'User_Email' => $request->email,
            'User_Parent' => $sponsor,
            'User_Tree' => $userTree,
            'User_Password' => bcrypt($request->password),
            'User_RegisteredDatetime' => date('Y-m-d H:i:s'),
            'User_Level' => 0,
            'User_Status' => 1,
            'User_EmailActive' => 0,
            'User_Agency_Level' => 0,
            'User_Token' => $token
        ];
        $insertUser = User::insert($userData);

        if (!$insertUser) {
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'There is an error, please contact admin']);
        }
        //dữ liệu gửi sang mailtemplate
        $data = array('User_ID' => $userID, 'User_Email'=> $request->email, 'token'=>$token);
        //Job

        dispatch(new SendMailJobs('Active', $data, 'Active Account!', $userID));

        return redirect()->route('getLogin')->with(['flash_level' => 'success', 'flash_message' => 'Registration successful, please check your email to confirm!']);
    }
    public function getActive(Request $req){

        $user = User::where('User_Token', $req->token)->first();
        if($user){
            if($user->User_EmailActive == 1){
                return redirect()->route('getLogin');
            }else {
                $user->User_EmailActive = 1;
                $user->save();
                return redirect()->route('getLogin')->with(['flash_level'=>'success', 'flash_message'=>'Activate Account Success!']);
            }

        }
        return redirect()->route('getLogin')->with(['flash_level'=>'error', 'flash_message'=>'Error!']);
    }

    public function PostMemberAdd(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,User_Email|max:255'
        ]);

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Email is wrong format!']);
        }

        $sponsor = session('user')->User_ID;
        $sponserInfo = User::where('User_ID', $sponsor)->first();
        $userID = $this->RandomIDUser();
        
        $userTree = $sponserInfo->User_Tree . "," . $userID;

        $password = $this->generateRandomString(10);

        $token = Crypt::encryptString($request->email.':'.time());
        
        $userData = [
            'User_ID' => $userID,
            'User_Email' => $request->email,
            'User_Parent' => $sponsor,
            'User_Tree' => $userTree,
            'User_Password' => Hash::make($password),
            'User_RegisteredDatetime' => date('Y-m-d H:i:s'),
            'User_EmailActive' => 0,
            'User_Agency_Level' => 0,
            'User_Level' => 0,
            'User_Status' => 1,
            'User_Token' => $token
        ];

        $insertUser = User::insert($userData);

        if (!$insertUser) {
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'There is an error, please contact admin']);
        }
        //dữ liệu gửi sang mailtemplate
        $data = array('password' => $password,'User_ID' => $userID, 'User_Email'=> $request->email, 'token'=>$token);
        //Job
        dispatch(new SendMailJobs('ADD_BINARY', $data, 'Active Account!', $userID));

        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Registration successful, please check your email to active user!']);
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function RandomIDUser()
    {
        $id = rand(1000000000, 9999999999);
        //TẠO RA ID RANĐOM
        $user = User::where('User_ID', $id)->first();
        //KIỂM TRA ID RANDOM ĐÃ CÓ TRONG USER CHƯA
        if (!$user) {
            return $id;
        } else {
            return $this->RandomIDUser();
        }
    }
}
