<?php

namespace App\Http\Controllers\System;

use App\Model\Money;
use App\Model\Profile;
use App\Model\User;
use App\Model\Wallet;
use App\Model\GoogleAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use vendor\project\StatusTest;
use GuzzleHttp\Client;
use App\Model\Log;
use DB;
use App\Jobs\SendTelegramJobs;
class WalletController extends Controller
{
    public $feeWithdraw = 0.005;
    public $feeTransfer = 0;
    public function getDeposit(){
		return view('System.Wallet.Deposit');
    }
    public function getTransfer(){
		$RandomToken = Money::RandomToken();
		$balance = Money::getBalance(Session('user')->User_ID);
		
		return view('System.Wallet.Transfer', compact('balance', 'RandomToken'));
	}
	public function postTransfer(Request $req){
		
        //check spam
        $checkSpam = DB::table('string_token')->where('User', Session('user')->User_ID)->where('Token', $req->CodeSpam)->first();
        
        
        if($checkSpam == null){
            //khoong toonf taij
            return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Misconduct!']);
        }
        else{
            DB::table('string_token')->where('User', Session('user')->User_ID)->delete();
        }

        $user = Session('user');
		
        $this->validate($req, [
            'userID' => 'required',
            'amount' => 'required|numeric|min:0',
            'otp' => 'required',
            'currency' => 'required'
		]);
        
        //Bảo mật
        $checkProfile = Profile::where('Profile_User', $user->User_ID)->first();

		if(!$checkProfile || $checkProfile->Profile_Status != 1){
		    return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Your Profile KYC Is Unverify!']);
		}
        $google2fa = app('pragmarx.google2fa');
        $AuthUser = GoogleAuth::select('google2fa_Secret')->where('google2fa_User', $user->User_ID)->first();
        if(!$AuthUser){
		    return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'User Unable Authenticator']);
        }
        $valid = $google2fa->verifyKey($AuthUser->google2fa_Secret, $req->otp);
        if(!$valid){
            return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Wrong code']);
        }
	    
		if(!$req->amount || $req->amount <= 0){
            return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Amount USD Invalid']);
        }
		
        //ID người nhận
        $transferUserID  = $req->userID;
        //Check User tồn tại được nhận tiền có tồn tại không???
        $checkUser = User::where('User_ID', $transferUserID)->first();
        if(!$checkUser){
            //ngươi nhận không tồn tại
            return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'The username is not valid!']);
        }
        
        //Check Array Coin
        $arrCoin = [
            5 => 'USD', 
            8 => 'SOX'
        ];
        if(!isset($arrCoin[$req->currency])){
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Invalid currency']);
        }
        //check balance
		$balance = Money::getBalanceXXX(Session('user')->User_ID, $req->currency);
        //Fee
        $amountFee = $req->amount* $this->feeTransfer;
        //check m\amount balance
        if($req->amount + $amountFee > $balance){
            return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Your balance is not enough!']);
        }

        $rate = 1;
        if($req->currency == 8){
            $rate = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy('SOX');
        }

        // trừ tiền người chuyển
	    $money = new Money();
		$money->Money_User = $user->User_ID;
		$money->Money_USDT = -$req->amount;
		$money->Money_USDTFee = $amountFee;
		$money->Money_Time = time();
		$money->Money_Comment = 'Transfer to UserID: '.$transferUserID;
		$money->Money_MoneyAction = 7;
		$money->Money_MoneyStatus = 1;
        $money->Money_Currency = $req->currency;
        $money->Money_CurrentAmount = $req->amount;
        $money->Money_Rate = $rate; 
        //Save
        $money->save();

        // cộng tiền cho người nhận
		$money = new Money();
		$money->Money_User = $transferUserID;
		$money->Money_USDT = $req->amount;
		$money->Money_USDTFee = 0;
		$money->Money_Time = time();
		$money->Money_Comment = 'Give from UserID: '.$user->User_ID;
		$money->Money_MoneyAction = 7;
        $money->Money_MoneyStatus = 1;
        $money->Money_Currency = $req->currency;
        $money->Money_CurrentAmount = $req->amount;
        $money->Money_Rate = $rate;
        $money->save();
		//Send mail job
		
        return redirect()->back()->with(['flash_level'=>'success', 'flash_message'=>'Transfer Success!']);
    }
    public function getHistoryWallet()
    {
	    $user = Session('user');
        $money = DB::table('money')
            ->join('moneyaction', 'Money_MoneyAction', 'MoneyAction_ID')
            ->join('currency', 'Money_Currency', 'Currency_ID')
            ->where('Money_MoneyStatus', '<>', -1)
            ->where('Money_User', $user->User_ID)
            ->orderByDesc('Money_ID')
            ->paginate(15);
        
        return view('System.History.Wallet-History', compact('money'));
    }
	
	public function getWithdraw(){
		$RandomToken = Money::RandomToken();
		$balance = Money::getBalance(Session('user')->User_ID);
        $rate = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy();

        $feeWithdraw = $this->feeWithdraw;
        
		return view('System.Wallet.Withdraw', compact('balance', 'RandomToken', 'rate', 'feeWithdraw'));
	}
    
    
    public function postWithdraw(Request $req){
	    
	    //check spam
        $checkSpam = DB::table('string_token')->where('User', Session('user')->User_ID)->where('Token', $req->CodeSpam)->first();
        
        
        if($checkSpam == null){
            //khoong toonf taij
            return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Misconduct!']);
        }
        else{
            DB::table('string_token')->where('User', Session('user')->User_ID)->delete();
        }

        //Validate
        $this->validate($req, [
		    'address' => 'required',
		    'otp' => 'required',
		    'amount' => 'required|numeric|min:0'
	    ]);
		
		$user = Session('user');
		
		if($user->User_Level == 4){
		    return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Your account can\'t use this function!']);
		}
        //Bảo mật
		$checkProfile = Profile::where('Profile_User', $user->User_ID)->first();

		if(!$checkProfile || $checkProfile->Profile_Status != 1){
		    return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Your Profile KYC Is Unverify!']);
		}
		$google2fa = app('pragmarx.google2fa');
        $AuthUser = GoogleAuth::select('google2fa_Secret')->where('google2fa_User', $user->User_ID)->first();
        if(!$AuthUser){
		    return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'User Unable Authenticator']);
        }
        $valid = $google2fa->verifyKey($AuthUser->google2fa_Secret, $req->otp);
        if(!$valid){
            return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Wrong code']);
        }
	    
		if(!$req->amount || $req->amount <= 0){
            return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Amount USD Invalid']);
		} 
		//sỐ TIỀN MUỐN RÚT
        $amount = $req->amount;
        //loại coin mà nó muốn nhận khi rút
        $coin_want_withdraw = $req->coin_want_withdraw;
        //Rút từ ví nào
        $form_wallet = $req->form_wallet;
        
        //Loại balance hiện có
        $arr_form_wallet = [
			5 => [
				'symbol' => 'USDX'
			],
			8 => [
				'symbol' => 'SOX'
			]
		];
		if(!isset($arr_form_wallet[$form_wallet])){
			return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Currency Invalid']);
        }
        //Rút về Ví coin nào???
        $arr_coin_want_withdraw = [
			1 => [
				'symbol' => 'BTC'
            ],
            2 => [
				'symbol' => 'ETH'
            ],
			8 => [
				'symbol' => 'SOX'
			]
		];
		if(!isset($arr_coin_want_withdraw[$coin_want_withdraw])){

			return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Currency Invalid']);
        }
        //Các loại rate
        $arr_Rate = [
            1 => 'BTC',
            2 => 'ETH',
            8 => 'SOX',
            5 => 'USD'
        ];
        $CurrentAmount_Temp = 0;
        if($form_wallet == 5){
            
            if($coin_want_withdraw == 1 || $coin_want_withdraw == 2){
                //balace USDX -> withdraw BTC, ETH
                //Lấy tỉ giá theo loại coin muốn rút về ví

                $rateCoin = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy($arr_Rate[$coin_want_withdraw]);
                $CurrentAmount_Temp = $amount/$rateCoin;
            }
            else{
                return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Error of withdrawal']);
            }
            
            
        }
        else{
            if($form_wallet == 8){
                if($coin_want_withdraw == 8){
                    //balace SOX -> withdraw SOX
                    $rate_coin_want_withdraw = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy($arr_Rate[$coin_want_withdraw]);
                    $rateCoin = $rate_coin_want_withdraw;
                    $CurrentAmount_Temp = $amount;
                }
                else{
                    //ERorr wwhen currency == BTC or ETH

                    return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Error of withdrawal']);
                }
            }

        }
		//đã đầu tư mới đc rút SOX
        if($form_wallet == 8){
			$checkInvest = Investment::where('investment_User', $user->User_ID)->where('investment_Status', '<>', -1)->first();
			if(!$checkInvest){
	            return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Please Invest $200 to Withdraw!']);
			}
        }
        
		$balance = Money::getBalanceXXX($user->User_ID, $form_wallet);
		$fee = $amount * ($form_wallet == 8 ? 0.002 : $this->feeWithdraw);
		if(($amount+$fee) > $balance){
            return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Your Balance Isn\'t Enough!']);
        }
	    // đặt lệnh rút
		$money = new Money();
		$money->Money_User = $user->User_ID;
		$money->Money_USDT = -$amount;
		$money->Money_USDTFee = $fee;
		$money->Money_Time = time();
		$money->Money_MoneyAction = 2; 
		$money->Money_MoneyStatus = 1;
		$money->Money_Comment = 'Withdraw to Address '.$req->address; 
		$money->Money_Address = $req->address; 
		$money->Money_CurrentAmount = $CurrentAmount_Temp;
		$money->Money_Currency = $coin_want_withdraw;
		$money->Money_Rate = $rateCoin;
		$money->save();
    
        

		//Chưa làm

		$message = "<b> $arr_Rate[$coin_want_withdraw] WITHDRAW </b>\n"
				. "ID: <b>$money->Money_User</b>\n"
				. "EMAIL: <b>$user->User_Email</b>\n"
				. "WALLET: <b>$req->address</b>\n"
				. "RATE: <b>$ $rateCoin</b>\n"
				. "COIN AMOUNT: <b>$CurrentAmount_Temp $arr_Rate[$coin_want_withdraw]</b>\n"
				. "USD AMOUNT: <b>$ ".($coin_want_withdraw == 8 ? ($amount*$rateCoin) : $amount)."</b>\n"
				. "<b>Submit Withdraw Time: </b>\n"
				. date('d-m-Y H:i:s',time());
				
		dispatch(new SendTelegramJobs($message, -325699550));
		
		
		
        return redirect()->back()->with(['flash_level'=>'success', 'flash_message'=>"Withdrawal Successfully!"]);
    }
    
    public function getAjaxUser(Request $req){
        //get address
        $userID = $req->userID;
        $result = User::where('User_ID', $userID)->first();
        if($result){
            return [
                'status' => true,
                'message' => 'Transfer to '.$result->User_Email,
                'class' => 'success'
            ];
        }
        else{
            return [
                'status' => true,
                'message' => 'Not found, please check again!',
                'class' => 'error'
            ];
        }

    
    }
}
