<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Resource\Address;
use Coinbase\Wallet\Resource\Account;
use Illuminate\Support\Facades\Session;

use Image;
use PragmaRX\Google2FA\Google2FA;

use App\Model\User;
use App\Model\Money;
use App\Model\Wallet;
use App\Model\Investment;
use App\Model\Profile;
use App\Model\GoogleAuth;

use DB;
use Mail;


class MoneyController extends Controller{

	
	public $successStatus = 200;
	public $fee = 0.005;
	public $channel = -344301959;
	public $keyHash	 = 'DAFCOCoorgsafwva'; 
	
	
    public function coinbase(){
        $apiKey = 'E08pbjcG026NoOFA';
        $apiSecret = 'SMKMp5kkGaFyDyjcaBX9S4nlu0rfhqTd';

        $configuration = Configuration::apiKey($apiKey, $apiSecret);
        $client = Client::create($configuration);

        return $client;
    } 
	
    public function PostWithdraw(Request $req){
	    include(app_path() . '/functions/xxtea.php');
		//return response(base64_encode(xxtea_encrypt(json_encode(array('coin'=>1, 'address'=>'123123', 'amount'=>0.1)),$this->keyHash)), 200);
	    $user = Auth::user();
	    
		//check spam
        $checkSpam = DB::table('string_token')->where('User', $user->User_ID)->where('Token', $data->CodeSpam)->first();
        
        if($checkSpam == null){
            //khoong toonf taij
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Misconduct!')),$this->keyHash)), 200);
        }
        else{
            DB::table('string_token')->where('User', $user->User_ID)->where('Token', $data->CodeSpam)->delete();
	        $RandomToken = Money::RandomToken($user->User_ID);
        }
        

	    $data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));
		
		if(!$req->data || $data == ''){
			return response()->json(array('status'=>false, 'message'=>'Miss Data'), 200);
		}
	    
	    if($user->User_Level == 4 || $user->User_Level == 5){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Error! Please Contact Admin!')),$this->keyHash)), 200);
		}
		
		if(!$data->address){
			return response()->json(array('status'=>false, 'message'=>'Please enter your address'), 200);
		}
	    // check balance
	    $balance = Money::getBalance($user->User_ID);
	    
	    $amountFee = $req->amount*$this->fee;
		$Rate = 0;
		if($data->coin == 2){
	    	$price = $this->coinbase()->getBuyPrice('ETH-USD');
			$Rate = $price->getAmount();
			$balanceCoin = $balance->ETH;
		}
/*
		if($req->coin == 3){
			$result = json_decode(file_get_contents('https://cryptoofyou.com/api/v1/prices/usd'));
			$Rate = $result->ADC;
			$balanceCoin = $balance->ADC;
		}
*/
		if($data->coin == 1){
	    	$price = $this->coinbase()->getBuyPrice('BTC-USD');
			$Rate = $price->getAmount();
			$balanceCoin = $balance->BTC;
	    }
/*
		if($req->coin == 4){
			$Rate = 0.1;
			$balanceCoin = $balance->USDA;
	    }
*/
		if($data->coin == 6){
	    	$price = $this->coinbase()->getBuyPrice('BCH-USD');
			$Rate = $price->getAmount();
			$balanceCoin = $balance->BCH;
	    }
		if($data->coin == 7){
	    	$price = $this->coinbase()->getBuyPrice('LTC-USD');
			$Rate = $price->getAmount();
			$balanceCoin = $balance->LTC;
	    }
		if($data->coin == 9){
			$trxPrice = json_decode(file_get_contents('https://api.binance.com/api/v1/ticker/price?symbol=TRXUSDT'));
			$Rate = $trxPrice->price;
			$balanceCoin = $balance->TRX;
	    }
	    if(($data->amount+$amountFee) > $balanceCoin){
		    return response()->json(array('status'=>false, 'message'=>'Your balance is not enough!'), 200);
	    }


		if($user->User_AuthStatus == 1){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'message'=>'Please enter OTP')),$this->keyHash)), 200);
		}else{
			$otp = rand(10000000,99999999);
			//update OTP
			User::where('User_ID', $user->User_ID)->update(['User_OTP'=>$otp]);
			
			
			// gửi mail cho người chơi config
			$data = array('User_ID'=>$user->User_ID,'otp'=>$otp);
			Mail::send('Mail.Withdraw', $data, function($msg) use ($user){
	            $msg->from('do-not-reply@gpgtoken.org','GPG wallet');
	            $msg->to($user->User_Email)->subject('Confirm Withdraw');
	        });
	        
	        return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'message'=>'Please check OTP in your Email')),$this->keyHash)), 200);	
		}

    }
    
    public function postConfirmWithdraw(Request $req){
	    include(app_path() . '/functions/xxtea.php');
	    //return response(base64_encode(xxtea_encrypt(json_encode(array('coin'=>1, 'address'=>'123123', 'amount'=>0.1, 'otp'=>'306312')),$this->keyHash)), 200);
	    $user = Auth::user();
        
	    $data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));
		
		if(!$req->data || $data == ''){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Data')),$this->keyHash)), 200);
		}
	    
		if(!$data->address){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Please enter your address')),$this->keyHash)), 200);
		}
	    
		if(!$data->from){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Wallet')),$this->keyHash)), 200);
		}
	    
		if(!$data->to){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Coin Withdrawl')),$this->keyHash)), 200);
		}
	    
		if(!$data->amount || $data->amount < 0){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Amount')),$this->keyHash)), 200);
		}
	    
		if(!$data->otp){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Authenticator Code')),$this->keyHash)), 200);
		}
	    
	    /*
if($user->User_Level == 4 || $user->User_Level == 5){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Error! Please Contact Admin!')),$this->keyHash)), 200);
		}
*/
		// xac thuc KYC
		$checkProfile = Profile::where('Profile_User', $user->User_ID)->first();

		if(!$checkProfile || $checkProfile->Profile_Status != 1){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Your Profile KYC Is Unverify!')),$this->keyHash)), 200);
		}
		
	    /*
$checkAuth = GoogleAuth::where('google2fa_User', $user->User_ID)->first();
	    if($checkAuth){
			$google2fa = app('pragmarx.google2fa');
			$valid = $google2fa->verifyKey($checkAuth->google2fa_Secret, "$data->otp");
			
			if(!$valid){
				return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'OTP is wrongs')),$this->keyHash)), 200);
			}
		}else{
		    return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Please Enable Authenticator')),$this->keyHash)), 200);
		}
*/
		$amount = $data->amount;
		$address = $data->address;
		$from = $data->from;
		$to = $data->to;
// 		var_dump($amount, $address, $from, $to);exit;
		if($from != 5 && $from != 8){
		    return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Error! Unable Withdraw From Wallet')),$this->keyHash)), 200);
		}
		if($from == 5){
			if($to != 1 && $to != 2){
		    	return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Error! Just Withdraw BTC Or ETH With USDX')),$this->keyHash)), 200);
			}
		    $amountFee = $amount*$this->fee;
		}else{
			//đã đầu tư mới đc rút SOX
			$checkInvest = Investment::where('investment_User', $user->User_ID)->where('investment_Status', '<>', -1)->first();
			if(!$checkInvest){
		    	return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Please Invest $200 to Withdraw')),$this->keyHash)), 200);
			}

			if($to != 8){
		    	return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Error! Just Withdraw SOX With SOX')),$this->keyHash)), 200);
			}
		    $amountFee = $amount*0.002;
		}
		//Các loại rate
        $arr_Rate = [
            1 => 'BTC',
            2 => 'ETH',
            8 => 'SOX',
            5 => 'USD'
        ];
	    // check balance
	    $currency = $arr_Rate[$from];
	    $balance = Money::getBalance($user->User_ID)->$currency;
		if($amount+$amountFee > $balance){
	    	return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Error! Your Balance Isn\'t Enough')),$this->keyHash)), 200);
		}
		$RateFrom = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy($arr_Rate[$from]);
		$RateTo = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy($arr_Rate[$to]);
		$CurrentAmount = $amount*$RateFrom/$RateTo;
	    // đặt lệnh rút
		$money = new Money();
		$money->Money_User = $user->User_ID;
		$money->Money_USDT = -$amount;
		$money->Money_USDTFee = $amountFee;
		$money->Money_Time = time();
		$money->Money_MoneyAction = 2;
		$money->Money_MoneyStatus = 1;
		$money->Money_Comment = 'Withdraw to Address '.$data->address;
		$money->Money_Address = $address;
		$money->Money_CurrentAmount = $CurrentAmount;
		$money->Money_Currency = $from;
		$money->Money_Rate = $RateFrom;
		$money->save();
				
		/*
$Currency = $arr_Rate[$from];
	    
		//Gửi telegram thông báo User verify
		$message = "Withdraw to Address: $money->Money_Address\n"
				. "<b>User ID: </b>\n"
				. "$money->Money_User\n"
				. "<b>User Email: </b>\n"
				. "$user->User_Email\n"
				. "<b>Amount: </b>\n"
				. $money->Money_USDT." ".$Currency."\n"
				. "<b>Submit Withdraw Time: </b>\n"
				. date('d-m-Y H:i:s',time());
*/
		
		
		$balanceNew = Money::getBalance($user->User_ID);

		return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'message'=>'Withdraw success!', 'balance'=>$balanceNew)),$this->keyHash)), 200);
    }
    
    public function PostTransfer(Request $req){ 
	    include(app_path() . '/functions/xxtea.php');
// 		return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Please comeback later!')),$this->keyHash)), 200);
	    $user = Auth::user();
	    $data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));
		
		if(!$req->data || $data == ''){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Data')),$this->keyHash)), 200);

		}
	    
	    if($user->User_Level == 4){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Error! Please Contact Admin!')),$this->keyHash)), 200);
		}
		
		if($user->User_Email == ''){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Please update your email in profile')),$this->keyHash)), 200);
		}
	    $userGiveMoney = User::getInfo($data->member);
	    if(!$userGiveMoney){
		    return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'User is not exits')),$this->keyHash)), 200);
	    }
	    
	    $checkUser = $this->checkTreeAPI($userGiveMoney->User_ID);
	    if($checkUser === false){
		    return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Error! Contact Admin')),$this->keyHash)), 200);
	    }
	    
	    
		$ArrCoin = array(1=>'BTC',2=>'ETH',5=>'USD',6=>'BCH',7=>'LTC',8=>'GPG', 9=>'TRX');
	    // check balance
	    $balance = Money::getBalance($user->User_ID); 
	    $fee = 0;
	    $coin = $ArrCoin[$data->coin];
	    $amountFee = $data->amount*$fee;
	    if(($data->amount+$amountFee) > $balance->$coin){
		    return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Your balance is not enough!')),$this->keyHash)), 200);
	    }
	    
		if($user->User_AuthStatus == 1){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'message'=>'Please enter OTP')),$this->keyHash)), 200);
		}else{
			$otp = rand(10000000,99999999);
			//update OTP
			User::where('User_ID', $user->User_ID)->update(['User_OTP'=>$otp]);
			// gửi mail cho người chơi config
			$data = array('User_ID'=>$user->User_ID,'otp'=>$otp);
			Mail::send('Mail.Transfer', $data, function($msg) use ($user){
	            $msg->from('do-not-reply@gpgtoken.org','GPG wallet');
	            $msg->to($user->User_Email)->subject('Confirm Transfer');
	        });
	        return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'message'=>'Please check OTP in your Email')),$this->keyHash)), 200);
		}
		
		
	    
    }
    
    public function postConfirmTransfer(Request $req){
	    include(app_path() . '/functions/xxtea.php');
// 	    return response(base64_encode(xxtea_encrypt(json_encode(array('coin'=>1, 'member'=>888888, 'amount'=>0.1, 'otp'=>448674)),$this->keyHash)), 200);
	    $user = Auth::user();
	    $data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));
		
		if(!$req->data || $data == ''){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Data')),$this->keyHash)), 200);
		}
		if(!$data->member){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss User ID Give Coin')),$this->keyHash)), 200);
		}
		if(!$data->coin){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Coin')),$this->keyHash)), 200);
		}
		if(!$data->amount || $data->amount <= 0){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Amount')),$this->keyHash)), 200);
		}
	    
	    if($user->User_Level == 4){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Error! Please Contact Admin!')),$this->keyHash)), 200);
		}
	    
	    $checkAuth = GoogleAuth::where('google2fa_User', $user->User_ID)->first();
	    if($checkAuth){
			$google2fa = app('pragmarx.google2fa');
			$valid = $google2fa->verifyKey($checkAuth->google2fa_Secret, $data->otp);
			
			if(!$valid){
				return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'OTP is wrongs')),$this->keyHash)), 200);
			}
		}else{
		    return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Please Enable Authenticator')),$this->keyHash)), 200);
		}
		
	    $userGiveMoney = User::getInfo($data->member);
	    if(!$userGiveMoney){
		    return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'User is not exits')),$this->keyHash)), 200);
	    }
	    //check user nằm trong hệ thống bị chặn hay ko 
	    
	    /*
$checkUser = $this->checkTreeAPI($userGiveMoney->User_ID);
	    if($checkUser === false){
		    return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Error! Contact Admin')),$this->keyHash)), 200);
	    }
*/
	    
		//Các loại rate
        $arr_Rate = [
            1 => 'BTC',
            2 => 'ETH',
            8 => 'SOX',
            5 => 'USD'
        ];
	    // check balance
	    $currency = $arr_Rate[$from];
	    $balance = Money::getBalance($user->User_ID)->$currency;
	    $fee = 0;
	    $amountFee = $data->amount*$fee;
	    if(($data->amount+$amountFee) > $balance->$currency){
		    return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Your balance is not enough!')),$this->keyHash)), 200);
	    }
	    
	    // trừ tiền người chuyển
	    $money = new Money();
		$money->Money_User = $user->User_ID;
		$money->Money_USDT = -$data->amount;
		$money->Money_USDTFee = $amountFee;
		$money->Money_Time = time();
		$money->Money_Comment = 'Transfer to ID:'.$userGiveMoney->User_ID;
		$money->Money_MoneyAction = 9; 
		$money->Money_MoneyStatus = 1;
		$money->Money_Currency = $data->coin; 
		$money->Money_Rate = 0; 
		$money->save();
	    
	    // cộng tiền cho người nhận
		$money = new Money();
		$money->Money_User = $userGiveMoney->User_ID;
		$money->Money_USDT = $data->amount;
		$money->Money_USDTFee = 0;
		$money->Money_Time = time();
		$money->Money_Comment = 'Give from ID:'.$user->User_ID;
		$money->Money_MoneyAction = 9; 
		$money->Money_MoneyStatus = 1;
		$money->Money_Currency = $data->coin; 
		$money->Money_Rate = 0; 
		$money->save();
		
	    $newBalance = Money::getBalance($user->User_ID);
	    return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'messages'=>'Transfer Success', 'balance'=>$newBalance)),$this->keyHash)), 200);
    
    }

    public function postSwap(Request $req){
	    include(app_path() . '/functions/xxtea.php');
	    $user = Auth::user();
	    $data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));
		
		if(!$req->data || $data == ''){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'messages'=>'Miss Data')),$this->keyHash)), 200);
		}
	    $coin = $data->coin;
        if($coin == 1){
            $price = $this->coinbase()->getBuyPrice('BTC-USD');
            $rate = $price->getAmount();
		}elseif($coin == 2){
            $price = $this->coinbase()->getBuyPrice('ETH-USD');
            $rate = $price->getAmount();
		}elseif($coin == 9){
			$trxPrice = json_decode(file_get_contents('https://api.binance.com/api/v1/ticker/price?symbol=TRXUSDT'));
			$rate = $trxPrice->price;
		}
		// GPG
	    /*
$rateGPG = DB::table('changes')->where('Changes_Time', date('Y-m-d'))->first()->Changes_Price;
	    
		$priceGPG = $req->amount*$rateGPG / $rate;
		$fee = $priceGPG * 0.02;
		$balance = $priceGPG + $fee;
*/
		// USD
		$rateGPG = 1;
	    
		$priceGPG = $data->amount*$rateGPG / $rate;
		$fee = $priceGPG * 0.02;
		$balance = $priceGPG + $fee;
		$arrayReturn = array('AmountUSD'=>$data->amount, 'CoinNeed'=>$balance);
		
		
		return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'data'=>$arrayReturn)),$this->keyHash)), 200);
		
    }
    
    public function postConfirmSwap(Request $req){
	    include(app_path() . '/functions/xxtea.php');
// 	    echo base64_encode(xxtea_encrypt(json_encode(array('coin_from'=>8,'coin_to'=>1, 'amount'=>1000)),$this->keyHash));exit;
	    $user = Auth::user();

	    $data = json_decode(xxtea_decrypt(base64_decode($req->data),$this->keyHash));


		if(!$req->data || $data == ''){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Miss Data')),$this->keyHash)), 200);
		}
	    $coinFrom = $data->coin_from;
	    $coin = $data->coin_to;
	    
	    if($coin != 1 && $coin != 2 && $coin != 9){
		    return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Coin must be in (BTC, ETH, TRX)')),$this->keyHash)), 200);
	    }
	    if($coinFrom != 5 && $coinFrom != 8){
		    return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Coin must be GPG')),$this->keyHash)), 200);
	    }

        if($coin == 1){
	        $base = 'BTC';
            $price = $this->coinbase()->getBuyPrice('BTC-USD');
            $rate = $price->getAmount();
		}elseif($coin == 2){
	        $base = 'ETH';
            $price = $this->coinbase()->getBuyPrice('ETH-USD');
            $rate = $price->getAmount();
		}elseif($coin == 9){
	        $base = 'TRX';
			$trxPrice = json_decode(file_get_contents('https://api.binance.com/api/v1/ticker/price?symbol=TRXUSDT'));
			$rate = $trxPrice->price;
		}

		if($coinFrom == 5){
	        $baseFrom = 'USD';
            $rateFrom = 1;
		}elseif($coinFrom == 8){
	        $baseFrom = 'GPG';
	        $tokenPrice = DB::table('changes')->where('Changes_Time', '<=', date('Y-m-d'))->where('Changes_Hour', '<=', date('H'))->orderBy('Changes_Time', 'DESC')->first();
            $rateFrom = $tokenPrice->Changes_Price;
		}
		
		$fee = (($data->amount*$rateFrom)/$rate) * 0.02;

		// kiểm tra balance đủ ko
		$balanceCoin = Money::getBalance($user->User_ID)->$baseFrom;

		if($balanceCoin < ($data->amount)){
			return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>false, 'message'=>'Your balance is not enough!')),$this->keyHash)), 200);
		}

		
	  	// đặt lệnh thành công: cộng USD cho thành viên và trư số coin đã giao dịch
	  	$data = array(
		    array(
		    	'Money_User'=>$user->User_ID, 
		    	'Money_USDT'=> round(($data->amount*$rateFrom/$rate),8), 
		    	'Money_USDTFee'=>round($fee,8), 
		    	'Money_Time'=>time(), 
		    	'Money_Comment'=>'Swap Coin From '.$baseFrom, 
		    	'Money_MoneyAction'=>14,
		    	'Money_MoneyStatus'=>1,
		    	'Money_Currency'=>$coin,
		    	'Money_Rate'=>$rate
		    ),
		    array(
			    'Money_User'=>$user->User_ID, 
		    	'Money_USDT'=> -$data->amount, 
		    	'Money_USDTFee'=>0, 
		    	'Money_Time'=>time(), 
		    	'Money_Comment'=>'Swap Coin from '.$baseFrom.' to '.$base, 
		    	'Money_MoneyAction'=>14,
		    	'Money_MoneyStatus'=>1,
		    	'Money_Currency'=>$coinFrom,
		    	'Money_Rate'=>$rateFrom
		    )
		);

		Money::insert($data);
		$balance = Money::getBalance($user->User_ID);
		
		$balanceArray = array(
					'GPG'=> $balance->GPG+0,
					'BTC'=> $balance->BTC+0,
					'ETH'=> $balance->ETH+0
		);
		return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'message'=>'Swap Success!', 'balance'=>$balanceArray)),$this->keyHash)), 200);
    }
    
    public static function checkTreeAPI($idCheck = null){
	    $idRoot = 815224391604;
        $user = Auth::user();
        $arrParent = explode(',', $user->User_Tree);
        $arrParent = array_reverse($arrParent);
	    if(array_search($idRoot, $arrParent) !== false){
			$userCheck = User::find($idCheck);
	        $arrParent = explode(',', $userCheck->User_Tree);
	        $arrParent = array_reverse($arrParent);
		    if(array_search($idRoot, $arrParent) !== false){
			    return true;
		    }
	        return false;
	    }
	    return true;
    }
    
}