<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Http\Controllers\System\CoinbaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Resource\Address;
use Coinbase\Wallet\Resource\Account;

use Image;
use PragmaRX\Google2FA\Google2FA;

use Sop\CryptoTypes\Asymmetric\EC\ECPublicKey;
use Sop\CryptoTypes\Asymmetric\EC\ECPrivateKey;
use Sop\CryptoEncoding\PEM;
use kornrunner\Keccak;
use App\Jobs\SendMailJobs;
use App\Model\GoogleAuth;
use App\Model\User;
use App\Model\Money;
use App\Model\Wallet;
use App\Model\Investment;
use DB;
use Mail;

class UserController extends Controller{

	
	public $successStatus = 200;
	public $keyHash	 = 'DAFCOCoorgsafwva'; 
	
	public function coinbase(){ 
        $apiKey = 'E08pbjcG026NoOFA';
        $apiSecret = 'SMKMp5kkGaFyDyjcaBX9S4nlu0rfhqTd';

        $configuration = Configuration::apiKey($apiKey, $apiSecret);
        $client = Client::create($configuration);

        return $client;
    }
	
	public function postAuth(Request $req){
		include(app_path() . '/functions/xxtea.php');
		// echo urlencode(base64_encode(xxtea_encrypt(json_encode(array('isEnable'=>0)),$this->keyHash)));exit;
		$user = Auth::user();
		$data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));
		
		if(!$req->data || $data == ''){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'message'=>'Miss Data')),$this->keyHash)), 200);
		}

		if(property_exists($data, 'isEnable')){
			$Enable = $data->isEnable;
			if($Enable != 1 && $Enable != 0){
				return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'message'=>'Enable wrong')),$this->keyHash)), 200);
			}
		}else{
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'message'=>'Miss IsEnable')),$this->keyHash)), 200);
		}
		
		// kiểm tra member có Auth chưa
		
		$userInfo = User::select('User_Auth', 'User_AuthStatus')->where('User_ID', $user->User_ID)->first();
		
		$google2fa = app('pragmarx.google2fa');
		
		if($userInfo->User_AuthStatus == 0){
			
			//bất auth
			if($Enable==0){
				return response()->json(array('status'=>false), 200);
			}
			
			$secret = $google2fa->generateSecretKey();
			
			$google2fa->setAllowInsecureCallToGoogleApis(true);
        
			$qrCodeUrl = $google2fa->getQRCodeUrl(
			    "GPG Token",
			    $user->User_Email,
			    $secret
			);

			User::where('User_ID', $user->User_ID)->update(['User_Auth'=>$secret]);
			$data = array('secret'=>$secret, 'QrCode'=>$qrCodeUrl);
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'data'=>$data)),$this->keyHash)), 200);
			
		}else{
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Please contact admin!')),$this->keyHash)), 200);
		}
		
	}
	
	public function getAuth(){
		include(app_path() . '/functions/xxtea.php');
		$user = Auth::user();
		$google2fa = app('pragmarx.google2fa');
        //kiểm tra member có secret chưa?
        $auth = GoogleAuth::where('google2fa_User',$user->User_ID)->first();

        $Enable = false;
        if($auth == null){
            $secret = $google2fa->generateSecretKey();
        }else{
            $Enable = true;
            $secret = $auth->User_Auth;
        }
        $google2fa->setAllowInsecureCallToGoogleApis(true);

        $inlineUrl = $google2fa->getQRCodeUrl(
            "DAFCO",
            $user->User_Email,
            $secret
		);
		return response(base64_encode(xxtea_encrypt(json_encode(array('Enable' => $Enable, 'secret' => $secret , 'inlineUrl' => $inlineUrl)),$this->keyHash)), 200);
	}

	public function postConfirmAuth(Request $req){
		
		$user = Auth::user();
		include(app_path() . '/functions/xxtea.php');
		// echo urlencode(base64_encode(xxtea_encrypt(json_encode(array('secret'=>"TEKFDG3MNTPJJRYT",'authCode'=>"TEKFDG3MNTPJJRYT")),$this->keyHash)));exit;

		$data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));
		// print_r ($data);
		// exit;
		if(!$req->data || $data == ''){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Data')),$this->keyHash)), 200);
		}

		if(!$data->authCode){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Auth Code')),$this->keyHash)), 200);
		}
		
		$google2fa = app('pragmarx.google2fa');
		
		$auth = GoogleAuth::where('google2fa_User',$user->User_ID)->first();

		$authCode = $data->authCode."";

        if($auth == null){
			if(!$data->secret){
				return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Secret')),$this->keyHash)), 200);
			}
			$valid = $google2fa->verifyKey($data->secret, $authCode);
        }else{
            $valid = $google2fa->verifyKey($auth->google2fa_Secret, $authCode);
		}
		
		if($valid){
			if($auth){
                // xoá
				GoogleAuth::where('google2fa_User',$user->User_ID)->delete();
				return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'message'=>'Disable Authenticator')),$this->keyHash)), 200);
            }else{
                // Insert bảng google2fa
                $r = new GoogleAuth();
                $r->google2fa_User = $user->User_ID;
                $r->google2fa_Secret = $data->secret;
                $r->save();
				return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'message'=>'Enable Authenticator')),$this->keyHash)), 200);
            }
		}
		return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Code wrong')),$this->keyHash)), 200);
	}
	
	public function getCoin(){
		include(app_path() . '/functions/xxtea.php');
		$user = Auth::user();

	    $coin = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy();
	

		$balance = Money::getBalance($user->User_ID);
		$coinArray = array(
					'USDX'=>array(
						'id'=>5,
						'balance'=> $balance->USD+0,
						'Price'=> 1,
						'PecentPlus'=> 0,
						'Deposit'=>0,
						'Withdraw'=>[1,2],
						'Transfer'=>1,
						'Invest'=>1
					),
					'SOX'=>array(
						'id'=>8,
						'balance'=> $balance->SOX+0,
						'Price'=> $coin['SOX']+0,
						'PecentPlus'=> 0,
						'Deposit'=>1,
						'Withdraw'=>[8],
						'Transfer'=>1,
						'Invest'=>1
					),
					'BTC'=>array(
						'id'=>1,
						'balance'=> 0,
						'Price'=> $coin['BTC']+0,
						'PecentPlus'=>0,
						'Deposit'=>1,
						'Withdraw'=>[],
						'Transfer'=>0,
						'Invest'=>0
					),
					'ETH'=>array(
						'id'=>2,
						'balance'=> 0,
						'Price'=> $coin['ETH']+0,
						'PecentPlus'=> 0,
						'Deposit'=>1,
						'Withdraw'=>[],
						'Transfer'=>0,
						'Invest'=>0
					)
		);
		
		$arrayReturn = array('status'=>true, 'data'=>$coinArray);
		return response(base64_encode(xxtea_encrypt(json_encode($arrayReturn),$this->keyHash)), 200);
	}

	public function getDeposit($id){
		include(app_path() . '/functions/xxtea.php');
		$user = Auth::user();
		$Coinbase = new CoinbaseController();
		$req = new Request;
		$req->coin = $id;
		$check = $Coinbase->getAddress($req);
		$arrayReturn = array('status'=>true, 'data'=>$check);
		return response(base64_encode(xxtea_encrypt(json_encode($arrayReturn),$this->keyHash)), 200);
	}
	
	public function getBalance(){
		
		include(app_path() . '/functions/xxtea.php');
		$user = Auth::user();
		// lấy giá của coin
		$coinPrice = DB::table('rate')->orderBy('Rate_ID', 'DESC')->limit(2)->get();
		$coin = array('BTC'=>0, 'ETH'=>0, 'LTC'=>0, 'BCH'=>0, 'USD'=>1);
		
	    $coin['BTC'] = app('App\Http\Controllers\System\InvestmentController')->coinbase()->getBuyPrice('BTC-USD')->getAmount();
	    $coin['ETH'] = app('App\Http\Controllers\System\InvestmentController')->coinbase()->getBuyPrice('ETH-USD')->getAmount();
		
		// giá token
		
		$tokenPrice = DB::table('changes')->where('Changes_Time', '<=', date('Y-m-d'))->where('Changes_Hour', '<=', date('H'))->orderBy('Changes_Time', 'DESC')->limit(2)->get();

		$balance = Money::getBalance($user->User_ID);
		$balanceArray = array(
					'GPG'=> round($balance->GPG, 8)+0,
					'BTC'=> $balance->BTC+0,
					'ETH'=> $balance->ETH+0
		);
		
		$arrayReturn = array('status'=>true, 'data'=>$balanceArray);
		return response(base64_encode(xxtea_encrypt(json_encode($arrayReturn),$this->keyHash)), 200);
	}
	
	public function getInfo(){
		include(app_path() . '/functions/xxtea.php');
		$user = Auth::user();
		$wallet = 1;
		if($user->User_WalletAddress == null){
			$wallet = 0;
		}
		$check_auth = DB::table('users')->where('User_ID',$user->User_ID)->join('google2fa','google2fa.google2fa_User','users.User_ID')->first();
		$status_auth = 0;
		if($check_auth){
			$status_auth = 1;
		}
		$info = array(
					'ID'=>$user->User_ID,
					'Email'=>$user->User_Email,
					'Phone'=>$user->User_Phone,
					'RegisteredDatetime'=>$user->User_RegisteredDatetime,
					'Parent'=>$user->User_Parent,
					'Wallet'=>$wallet,
					'Auth'=> $status_auth,
					'PrivateKey'=>$user->User_PrivateKey,
					'WalletAddress'=>$user->User_WalletAddress
						);
		$info['Level'] = $user->User_Agency_Level;
		$info['LevelName'] = ($user->User_Agency_Level == 0) ? "Member" : "Star ".$user->User_Agency_Level;
		$info['LevelImage'] = 'http://dafco.org/test/public/dafco/assets/images/level/LEVEL_'.$user->User_Agency_Level.'.png';
		$getInvestFirst = Investment::where('investment_User', $user->User_ID)->where('investment_Status', 1)->orderBy('investment_ID')->first();
		$sales = 0;
		if($getInvestFirst){
			$sales = Investment::selectRaw('Sum(`investment_Amount`*`investment_Rate`) as SumInvest')
		    									->whereRaw('investment_User IN (SELECT User_ID FROM users WHERE User_Tree LIKE "'.$user->User_Tree.'%")')
		    									// ->where('investment_Time', '>=', $getInvestFirst->investment_Time)
		    									->where('investment_User', '<>', $user->User_ID)
		    									->where('investment_Status', 1)
		    									->first()->SumInvest;
		}
		$arrayReturn = array('status'=>true, 'data'=>array('info'=>$info, 'totalSales'=>number_format($sales, 2)));
		return response(base64_encode(xxtea_encrypt(json_encode($arrayReturn),$this->keyHash)), 200);

	}
	
	public function postMemberList(Request $req){ 
		
		include(app_path() . '/functions/xxtea.php');
		$limit = 30;
		$page = 1;
		$sort = 'asc';
		if($req->data){
			$data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));
			if(isset($data->limit)){
				$limit = $data->limit;
				if($limit<1){
					$limit = 30;
				}
			}
			
			
			if(isset($data->page)){
				$page = $data->page;
				if($page<1){
					$page = 1;
				}
			}
			if(property_exists($data, 'sort')){
				if($data->sort == 'desc'){
					$sort = 'desc';
				}
			}
		}
		
		
		$user = Auth::user();
		$dataReturn = array('status'=>true, 'link'=>'https://dafco.org/register?ref='.$user->User_ID, 'data'=>'', 'total'=>1, 'page'=>$page, 'limit'=>$limit, 'sort'=>$sort);
		
		$total = User::select('User_ID')
						->whereRaw('User_Tree LIKE "'.$user->User_Tree.',%"')
						->count();
						
		$dataReturn['total'] = round($total/$limit) == 0 ? 1 : round($total/$limit);
		
		$user_list = User::select('User_ID', 'User_Email', 'User_RegisteredDatetime', 'User_Parent', DB::raw("(CHAR_LENGTH(User_Tree)-CHAR_LENGTH(REPLACE(User_Tree, ',', '')))-" . substr_count($user->User_Tree, ',') . " AS f, User_Agency_Level, User_Tree"))
						->whereRaw('User_Tree LIKE "'.$user->User_Tree.',%"')
                        ->orderBy('f', $sort);
		// lấy tổng doanh số
		$totalSales = DB::table('investment')->join('users', 'investment_User', 'User_ID')
											->whereRaw('User_Tree LIKE "'.$user->User_Tree.'%" AND User_ID != '.$user->User_ID)
											->where('investment_Status', 1)
											->selectRaw('sum(investment_Amount*investment_Rate) as SumTotalSales');
											
		if($req->data){
			$data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));
			if(isset($data->user)){
				$getDataUser = User::where('User_ID', $data->user)->orWhere('User_Email', $data->user)->first();
				if($getDataUser){
					$totalSales = $totalSales->whereRaw('User_Tree LIKE "'.$getDataUser->User_Tree.'%"');
				}
			}
		}	
		$user_list = $user_list->get();	
		$totalSales = $totalSales->first();				
		$arrayReturn = array();
		foreach($user_list as $v){
			$TotalInvest = DB::table('investment')
								->where('investment_User', $v->User_ID)
								->select(DB::raw('SUM(investment_Amount * investment_Rate) as Sales'))->first()->Sales + 0;
								
			// lấy tổng doanh số
			$SalesMember = DB::table('investment')->join('users', 'investment_User', 'User_ID')
											->whereRaw('User_Tree LIKE "'.$v->User_Tree.'%" AND User_ID != '.$v->User_ID)
											->where('investment_Status', 1)
											->selectRaw('sum(investment_Amount*investment_Rate) as SumTotalSales')->first()->SumTotalSales + 0;
			$level = $v->User_Agency_Level == 0 ? "Member" : "Star ".$v->User_Agency_Level;
			
			$arrayReturn[] = array(
				'ID' => $v->User_ID,
				'Email' => $v->User_Email,
				'RegisteredDatetime' => $v->User_RegisteredDatetime,
				'Parent' => $v->User_Parent,
				'F' => $v->f,
				'TotalInvest' => '$'.round($TotalInvest,2),
				'TotalSales' => '$'.round($SalesMember, 2),
				'level' => $level
			);
		}				
		$dataReturn['totalSales'] = $totalSales->SumTotalSales == null ? 0 : round($totalSales->SumTotalSales, 2);
		
		$dataReturn['data'] = $arrayReturn;
		return response(base64_encode(xxtea_encrypt(json_encode($dataReturn),$this->keyHash)), 200);

	}
	
	
	public function postHistory(Request $req){
		include(app_path() . '/functions/xxtea.php');
		
		$user = Auth::user();
		$limit = 30;
		$page = 1;
		$sort = 'asc';
		//echo base64_encode(xxtea_encrypt(json_encode(array('page'=>1, 'limit'=>20,'action'=>9)),$this->keyHash));exit;
		$where = null;
		
		if($req->data){
			$data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));
			if(isset($data->limit)){
				$limit = $data->limit;
				if($limit<1){
					$limit = 30;
				}
			}
			
			if(isset($data->page)){
				$page = $data->page;
				if($page<1){
					$page = 1;
				}
			}
			if(property_exists($data, 'sort')){
				if($data->sort == 'desc'){
					$sort = 'desc';
				}
			}
			if(property_exists($data, 'action')){
				if($data->action == 9 || $data->action == 10){
					$where = ' AND (Money_MoneyAction = 9 OR Money_MoneyAction = 10)';
					
				}else{
					$where = ' AND Money_MoneyAction = '.$data->action;
				}
			}
		}
		
		
		
		$dataReturn = array('status'=>true, 'data'=>'', 'total'=>0, 'page'=>$page, 'limit'=>$limit, 'sort'=>$sort, 'Direct'=>0, 'Indirect'=>0, 'Affiliate'=>0);

		$total = DB::table('money')->select('Money_ID')
					->whereRaw('1 '.$where)
					->where('Money_User', $user->User_ID)->count();
					
		$dataReturn['total'] = round($total/$limit) == 0 ? 1 : round($total/$limit);
		
		$history = DB::table('money')->select('Money_ID', 'Money_USDT', 'Money_USDTFee', 'Money_Time', 'Money_Comment', 'MoneyAction_Name', 'Currency_Name', 'Currency_Symbol', 'Money_MoneyStatus', 'Money_MoneyAction', 'Money_Confirm', 'Money_Rate')
					->join('moneyaction', 'Money_MoneyAction', 'MoneyAction_ID')
					->join('currency', 'Currency_ID', 'Money_Currency')
					->where('Money_User', $user->User_ID)
					->whereRaw('1 '.$where)
					->skip(($page-1)*$limit)->take($limit)
					->orderBy('Money_ID', $sort)->get();
		$historyData = array();
		foreach($history as $v){
			
			if($v->Money_MoneyStatus == 1){
				if($v->Money_MoneyAction == 2 && $v->Money_Confirm == 0){
					$v->Money_MoneyStatus = "Processing";
				}else{
					$v->Money_MoneyStatus = "Success";
				}
			}else{
				$v->Money_MoneyStatus = "Cancel";
			}
			
			$historyData[] = array(
				'ID' => $v->Money_ID,
				'Amount' => $v->Money_USDT+0,
				'Fee' => $v->Money_USDTFee+0,
				'Time' => date('Y-m-d H:i:s', $v->Money_Time),
				'Comment' => $v->Money_Comment,
				'Rate' => $v->Money_Rate,
				'ActionName' => $v->MoneyAction_Name,
				'Currency' => $v->Currency_Name,
				'Symbol' => $v->Currency_Symbol,
				'Status' => $v->Money_MoneyStatus,
			);
		}
		$dataReturn['data'] = $historyData;
		
		$affiliate = 0;
		//bị ngược action
		$commission = DB::table('money')->where('Money_User', $user->User_ID)->whereIn('Money_MoneyAction', [4,6,7])->where('Money_MoneyStatus', 1)->selectRaw('Sum(Money_USDT) as amountUSD')->first();
		$Interest = DB::table('money')->where('Money_User', $user->User_ID)->where('Money_MoneyAction', 5)->where('Money_MoneyStatus', 1)->selectRaw('Sum(Money_USDT) as amountUSD')->first();
		$dataCom = 0;
		if($commission){
			$dataCom = $commission->amountUSD;
		}
		$dataInterest = 0;
		if($Interest){
			$dataInterest = $Interest->amountUSD;
		}
		$dataReturn['Commission'] = round($dataCom, 2);
		$dataReturn['Interest'] = round($dataInterest, 2);
		$dataReturn['Affiliate'] = round($affiliate, 2);

		return response(base64_encode(xxtea_encrypt(json_encode($dataReturn),$this->keyHash)), 200);
		
	}
	
	public function postEmail(Request $req){

		include(app_path() . '/functions/xxtea.php');
		$data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));
		
		if(!$req->data || $data == ''){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Data')),$this->keyHash)), 200);

		}

		if(!$data->email){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss email')),$this->keyHash)), 200);

		}
		
		// kiểm tra email có tồn tại hay không
		$email = DB::table('users')->where('User_Email', $data->email)->first();
		if(!$email){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Email not exits')),$this->keyHash)), 200);
		}
		
		// tạo token 
		$otpNumber = rand(10000000, 99999999);
		
		$otp = DB::table('users')->where('User_ID', $email->User_ID)->update(['User_OTP'=>$otpNumber]);
		
		// gửi token về email
		//dữ liệu gửi sang mailtemplate
        $data = array('User_ID'=>$email->User_ID, 'otp'=>$otpNumber);

        // gửi mail thông báo
        $data = array('User_ID' => $userID, 'User_Email'=> $request->email, 'token'=>$token);
        //Job

        dispatch(new SendMailJobs('Active', $data, 'Active Account!', $userID));
        
        return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'message'=>'Please check your email to Login')),$this->keyHash)), 200);
        
	}
    
	
	public function postLogin(Request $req){
		include(app_path() . '/functions/xxtea.php');
        // echo base64_encode(xxtea_encrypt(json_encode(array('email'=>'boss@gmail.com123', 'password'=>654321)),$this->keyHash));exit;
		$data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));
		if(!$req->data || $data == ''){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Data')),$this->keyHash)), 200);
		}

		if(!$data->email){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss email')),$this->keyHash)), 200);
		}
		
		if(!$data->password){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Password')),$this->keyHash)), 200);
		}
		$random = rand(1,100);
		$now = time()+$random;
        $user = User::where('User_Email', $data->email)->first();
        if(!$user){
            return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Email is\'nt exist!')),$this->keyHash)), 200);
        }
        if($user->User_EmailActive != 1){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Please check your email and active this account!')),$this->keyHash)), 200);
		}
		if($user){
			$user->User_Log = $now;
			$user->save();
        }
		if(Auth::attempt(['User_Email' => $data->email, 'password' => $data->password])){ 
			
            $user = Auth::user(); 
            
            $token = $user->createToken('DAFCO')->accessToken;
            
            $arrReturn = array('status'=>true, 'token'=>$token);
        
			return response(base64_encode(xxtea_encrypt(json_encode($arrReturn),$this->keyHash)), 200);
        }else{ 
	        return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false,'message' => 'Login information is incorrect')),$this->keyHash)), 200);

        }
    }
    
    public function postForgetPassword(Request $req){
		
		include(app_path() . '/functions/xxtea.php');
		// echo urlencode(base64_encode(xxtea_encrypt(json_encode(array('email'=>'skipro982301@gmail.com')),$this->keyHash)));exit;
		$data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));
		if(!$req->data || $data == ''){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Data')),$this->keyHash)), 200);
		}
		
		if(!$data->email){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss email')),$this->keyHash)), 200);
		}	
		
		$user = User::where('User_Email', $data->email)->first();

		if(!$user){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Email not exits')),$this->keyHash)), 200);

		}
		
		// gửi mail pass mới
		$passwordRan = $this->RandomString();
		$user->User_Password = Hash::make($passwordRan);
		$user->save();

		$token = Crypt::encryptString($user->User_ID.':'.time().':'.$passwordRan);

		$data = array('User_Email'=>$data->email, 'pass'=>$passwordRan,'token'=>$token);
        
        // gửi mail thông báo
        
		dispatch(new SendMailJobs('Forgot', $data, 'New Password!', $user->User_ID));
		
		return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'message'=>'Please check your email.')),$this->keyHash)), 200);
		
    }
    
    
    public function postRegister(Request $req){

	    include(app_path() . '/functions/xxtea.php');
	    
		// echo urlencode(base64_encode(xxtea_encrypt(json_encode(array('email'=>'skipro982301@gmail.com', 'password'=>'123456', 'password_confirm'=>'123456' ,'ponser'=>'')),$this->keyHash)));exit;
		$data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));
		// print_r ($data);
		// exit;
		if(!$req->data || $data == ''){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Data')),$this->keyHash)), 200);
		}
		
	   	if(!$data->email){
		   	return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss email')),$this->keyHash)), 200);
		}else{
			if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
				return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'wrong email format')),$this->keyHash)), 200);        
			}
			$checkEmail = User::where('User_Email', $data->email)->first();
			if($checkEmail){
				return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Email exits')),$this->keyHash)), 200);
			}
		}
		

        $parents = 8812781365;
		if(property_exists($data, 'ponser')){
			if($data->ponser){
				$parents = $data->ponser;
			} 
        }
        $InfoPonser = User::where('User_ID', $parents)->first();
		if(!$InfoPonser){
	        return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Ponser not exits')),$this->keyHash)), 200);
		}
		

		if(!$data->password || !$data->password_confirm){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss password and password confirm')),$this->keyHash)), 200);
		}
		elseif($data->password != $data->password_confirm){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Password confirm incorect')),$this->keyHash)), 200);
		}
		
        $password = Hash::make($data->password);

        
		$UserID = $this->RandonIDUser();
		
        //Tạo token cho mail
        $dataToken = array('user_id'=>$UserID, 'time'=>time());

		$token = encrypt(json_encode($dataToken));
		$level = 0;
	    $abc = (strstr($InfoPonser->User_Tree, '666666'));
	    if($abc != false){
		    $level = 5;
	    }
        $inserArray = array( 
	        'User_ID'=> $UserID,
	        'User_Email'=> $data->email,
	        'User_EmailActive'=> 0,
	        'User_Password'=> $password,
	        'User_RegisteredDatetime'=> date('Y-m-d H:i:s'),
	        'User_Parent'=> $parents,
	        'User_Tree'=> $InfoPonser->User_Tree.','.$UserID,
	        'User_Level'=> 0,
	        'User_Token'=>$token,
	        'User_Agency_Level'=> 0,
	        'User_Status'=> 1
        );

        $user = User::insert($inserArray);
        // gửi mail cho member mới
        
		try {
		    // gửi mail thông báo
            $data = array('User_ID' => $UserID, 'User_Email'=> $data->email, 'token'=>$token);
            //Job
            dispatch(new SendMailJobs('Active', $data, 'Active Account!', $UserID));
		} catch (Exception $e) {
		    return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'wrong email format')),$this->keyHash)), 200);        
		}      
        
        if($user){
	        return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'message'=>'Regiter complete. Please check email')),$this->keyHash)), 200);
        }
        return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Regiter false. Please contact admin')),$this->keyHash)), 200);        
    }
    
    public function postChangePassword(Request $req){

		include(app_path() . '/functions/xxtea.php');
		
		// echo urlencode(base64_encode(xxtea_encrypt(json_encode(array('password_old'=>'654321', 'password_new'=>'123456', 'password_new_confirm'=>'123456')),$this->keyHash)));exit;
	    $user = Auth::user();

		$data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));
		// print_r($req->data);
		// exit;
		if(!$req->data || $data == ''){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Data')),$this->keyHash)), 200);
		}
		
	   	if(!isset($data->password_old)){
		   	return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss old password')),$this->keyHash)), 200);
		}
		
		if(!isset($data->password_new)){
		   	return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss new password')),$this->keyHash)), 200);
		}

		if(!isset($data->password_new_confirm)){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss new password confirm')),$this->keyHash)), 200);
		}
		
		if($data->password_new != $data->password_new_confirm){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'New password confirm incorect')),$this->keyHash)), 200);
		}


		if(Hash::check($data->password_old, $user->User_Password)){
			
			$password = Hash::make($data->password_new); 

			$update = User::where('User_ID', $user->User_ID)->update(['User_Password'=>$password]);
			if($update){
				return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'message'=>'Update new password complete!')),$this->keyHash)), 200);  
			}else{
				return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Update new password faile!')),$this->keyHash)), 200); 
			}
			
		}else{
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Password is incorrect')),$this->keyHash)), 200);
		}
			
    }
    
    public function postAddMember(Request $req){
	    
	    include(app_path() . '/functions/xxtea.php');
	    $user = Auth::user();
// 		echo base64_encode(xxtea_encrypt(json_encode(array('email'=>'jgjgjgjgj')),$this->keyHash));exit;
		
		$data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));
		if(!$req->data || $data == ''){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Data')),$this->keyHash)), 200);
		}
		
	   	if(!$data->email){
		   	return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss email')),$this->keyHash)), 200);
		}else{
			$checkEmail = User::where('User_Email', $data->email)->first();
			if($checkEmail){
				return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Email exits')),$this->keyHash)), 200);
			}
		}
		if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Please fill email!')),$this->keyHash)), 200);
		}

		
        $password = Hash::make(123456);
        
        $UserID = $this->RandonIDUser();
        //Tạo token cho mail
        $dataToken = array('user_id'=>$UserID, 'time'=>time());

		$token = encrypt(json_encode($dataToken));
        $level = 0;
	    $abc = (strstr($user->User_Tree, '815224391604'));
	    if($abc != false){
		    $level = 5;
	    }
        $inserArray = array( 
	        'User_ID'=> $UserID,
	        'User_Email'=> $data->email,
	        'User_EmailActive'=> 0,
	        'User_Password'=> $password,
	        'User_RegisteredDatetime'=> date('Y-m-d H:i:s'),
	        'User_Parent'=> $user->User_ID,
	        'User_Tree'=> $user->User_Tree.','.$UserID,
	        'User_Level'=> $level,
	        'User_Token'=>$token,
	        'User_Agency_Level'=> 0,
	        'User_Status'=> 1
        );

        //echo '<>pre';print_r($inserArray);exit;

        $user = User::insert($inserArray);
        // gửi mail cho member mới
        
        //dữ liệu gửi sang mailtemplate
        $data = array('User_Email'=>$data->email, 'token'=>$token);

        // gửi mail thông báo
        Mail::send('Mail.Active', $data, function($msg) use ($data){
            $msg->from('do-not-reply@gpgtoken.org','GPG Token');
            $msg->to($data['User_Email'])->subject('Activate Account');
        });
        
        if($user){
	        return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'message'=>'Register complete. Please check email')),$this->keyHash)), 200);
        }
        return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Register false. Please contact admin')),$this->keyHash)), 200);        
    }
    
    public function getLogout(){
	    include(app_path() . '/functions/xxtea.php');
	    if(Auth::check()) {

	       	$accessToken = Auth::user()->token();
	       	
	        DB::table('oauth_refresh_tokens')
	            ->where('access_token_id', $accessToken->id)
	            ->update([
	                'revoked' => true
	            ]);
			
	        $accessToken->revoke();
			
	        return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'message'=>'Logout complete')),$this->keyHash)), 200);        
		   	
	    }else{
		    return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Logout faile')),$this->keyHash)), 200);
	    }
    }
    
    public function getStrinhCode(){
		include(app_path() . '/functions/xxtea.php');
	    $user = Auth::user();

	    if($user->User_WalletAddress == ''){
		    $string = DB::table('string_code')->inRandomOrder()->limit(12)->get();
		    $aa = '';
		    $arrayExactly = array();
		    foreach($string as $v){
			   $aa .= $v->string_code_String.'.';
			   $arrayExactly[] = $v->string_code_String;
		    }
		    $aa = substr($aa ,0,-1);
		    
		    User::where('User_ID', $user->User_ID)->update(['User_StringCode'=>$aa]);
		    return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'data'=>$arrayExactly)),$this->keyHash)), 200);
	    }
	    
	    return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false)),$this->keyHash)), 200);
	       
    }
    
    public function getCreateWallet(Request $req){

	    include(app_path() . '/functions/xxtea.php');
	    $user = Auth::user();
// 		echo base64_encode(xxtea_encrypt(json_encode(array('stringCode'=>'these.deposit.reinvest.group.in.many.brought.we.however.below.understand.back')),$this->keyHash));exit;
		
		$data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));
		if(!$req->data || $data == ''){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Data')),$this->keyHash)), 200);
		}
		
	   	if(!$data->stringCode){
		   	return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss email')),$this->keyHash)), 200);
		}

	   

	    $config = [
		    'private_key_type' => OPENSSL_KEYTYPE_EC,
		    'curve_name' => 'secp256k1'
		];
		$res = openssl_pkey_new($config);
		if (!$res) {
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'ERROR: Fail to generate private key.')),$this->keyHash)), 200);
		}
		// Generate Private Key
		openssl_pkey_export($res, $priv_key);
		// Get The Public Key
		$key_detail = openssl_pkey_get_details($res);
		$pub_key = $key_detail["key"];
		$priv_pem = PEM::fromString($priv_key);
		// Convert to Elliptic Curve Private Key Format
		$ec_priv_key = ECPrivateKey::fromPEM($priv_pem);
		// Then convert it to ASN1 Structure
		$ec_priv_seq = $ec_priv_key->toASN1();
		// Private Key & Public Key in HEX
		$priv_key_hex = bin2hex($ec_priv_seq->at(1)->asOctetString()->string());
		$priv_key_len = strlen($priv_key_hex) / 2;
		$pub_key_hex = bin2hex($ec_priv_seq->at(3)->asTagged()->asExplicit()->asBitString()->string());
		$pub_key_len = strlen($pub_key_hex) / 2;
		// Derive the Ethereum Address from public key
		// Every EC public key will always start with 0x04,
		// we need to remove the leading 0x04 in order to hash it correctly
		$pub_key_hex_2 = substr($pub_key_hex, 2);
		$pub_key_len_2 = strlen($pub_key_hex_2) / 2;
		// Hash time

		$hash = Keccak::hash(hex2bin($pub_key_hex_2), 256);
		// Ethereum address has 20 bytes length. (40 hex characters long)
		// We only need the last 20 bytes as Ethereum address
		$wallet_address = '0x' . substr($hash, -40);
		$wallet_private_key = '0x' . $priv_key_hex;
		
		// cập nhật ví
		User::where('User_ID', $user->User_ID)->update(['User_PrivateKey'=>$wallet_private_key, 'User_WalletAddress'=>$wallet_address]);
		
	    $return = array('PrivateKey'=>$wallet_private_key,'Address'=>$wallet_address);
	    
	    return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true,'data'=>$return)),$this->keyHash)), 200);
	       
    }
    
    public function RandonIDUser(){
	    
	    $id = rand(1000000000, 9999999999);
        //TẠO RA ID RANĐOM
        $user = User::where('User_ID', $id)->first();
        //KIỂM TRA ID RANDOM ĐÃ CÓ TRONG USER CHƯA
        if (!$user) {
            return $id;
        } else {
            return $this->RandonIDUser();
        }
    }
    
    function RandomString(){
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $length = 10;    
		return substr(str_shuffle($characters),1,$length);
	}
}