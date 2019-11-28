<?php

namespace App\Http\Controllers\System;

use App\Model\Investment;
use App\Model\Money;
use App\Model\Log;
use App\Model\User;
use GuzzleHttp\Client;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
class InvestmentController extends Controller
{
    public function getInvestment()
    {
        
        $RandomToken = Money::RandomToken();
        $user = session('user');
        $balance = Money::getBalance(Session('user')->User_ID);
        $package_time = DB::table('package_time')->where('time_Status', 1)->get();
        $rate = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy();
        $history_invest = Investment::join('package', 'package_ID', 'investment_Package')->join('currency', 'Currency_ID' ,'investment_Currency')->where('investment_User', $user->User_ID )->where('investment_Status','<>', -1)->orderBy('investment_ID', 'DESC')->get();
        
        return view('System.Investment.Index', compact('balance', 'package_time', 'rate', 'RandomToken', 'history_invest'));
    }

    
    public function getInfo_Package($id){
        $package_info = DB::table('package')->where('package_ID', $id)->first();
        if(!$package_info){
            exit();
        }
        return response()->json($package_info);
    }
    public function postInvestment(Request $req)
    {
        
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
        $req->validate([
            'currency' => 'required',
            'investment_amount_USDX' => 'required|numeric|min:0',
            'investment_amount_SOX' => 'required|numeric|min:0',
            'investment_month' => 'required'
        ]);
        $arrCoin = [ 5 => 'USD', 8 => 'SOX'];
        //Check cố định investment_amount_USDX
        if($req->investment_amount_USDX <= 0){
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Investment amount invalid']);
        }
        if(!isset($arrCoin[$req->currency])){
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Invalid currency']);
        }
        //RATE
        $rate = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy();
        //Balance
        $balance = Money::getBalanceXXX($user->User_ID, $req->currency);
        if($req->currency == 8){
            //currency == 8(SOX)
            $amount = $req->investment_amount_USDX / $rate['SOX'];
            if($amount > $balance){
                return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Your balance is not enough']);
            }
        }
        else{
            //currency == 5(USDX)
            $amount = $req->investment_amount_USDX;
            if($amount > $balance){
                return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Your balance is not enough']);
            }
        }
        //Min and Max của gói đầu tư
        $package_info = DB::table('package')->where('package_Min', '<=', $req->investment_amount_USDX)->where('package_Max', '>', $req->investment_amount_USDX)->where('package_Status', 1)->first();
        
        if(!$package_info){
            if($req->investment_amount_USDX < 200){
                return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Min invest $200!']);
            }

            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'PACKAGE Null Null!']);
        }
        // thông tin thòi gian gói hết hạn
        $package_time_info = DB::table('package_time')->where('time_Month', $req->investment_month)->where('time_Status', 1)->first();

        if(!$package_time_info){
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'PACKAGE TIME Null Null!']);
        }
        //Get RATE balance
        $name_coin = $arrCoin[$req->currency];

        //Trừ tiền 
	    $moneyArray = array(
		    'Money_User' => $user->User_ID,
		    'Money_USDT' => -$amount,
		    'Money_USDTFee' => 0,
		    'Money_Time' => time(),
			'Money_Comment' => 'Investment '.$amount.' '.$arrCoin[$req->currency]. ' '.$req->investment_month.' Months',
			'Money_MoneyAction' => 3,
			'Money_MoneyStatus' => 1,
            'Money_Rate' => $rate[$name_coin],
            'Money_CurrentAmount' => $amount,
			'Money_Currency' => $req->currency
        );
        //Invest
        $invest = array(
		    'investment_User' => $user->User_ID,
            'investment_Amount' => $req->currency == 8 ? $amount : $amount / $rate['SOX'],
            'investment_Package' => $package_info->package_ID,
            'investment_Package_Time' => $package_time_info->time_Month,
		    'investment_Rate' => $rate['SOX'],
		    'investment_Currency' => 8,
		    'investment_Time' => time(),
		    'investment_Status' => 1
	    );
	    // thêm dữ liệu
	    DB::table('investment')->insert($invest);
        DB::table('money')->insert($moneyArray);
        //Update Level
        $this->checkToLevel($user->User_ID);
        //checkDirectCom
        $this->checkDirectCom($user, $req->investment_amount_USDX);
        return redirect()->route('system.getInvestment')->with(['flash_level'=>'success','flash_message'=>'Investment complete']);  
    }
    
    public function checkDirectCom($user, $amountUSD){
	    
    	$arrParent = explode(',', $user->User_Tree);
		$arrParent = array_reverse($arrParent);
		$arrPercent = [1=>0.05, 2=>0.03, 3=>0.02, 4=>0.01, 5=>0.008, 6=>0.005, 11=>0.003, 21=>0.002, 31=>0.001];
		$currency = 8;
		$rateCurrency = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy('SOX');
		$amountCom = $amountUSD/$rateCurrency;
		if(count($arrParent) > 50){
			$count = 51;
		}else{
			$count = count($arrParent);
		}
		for($i=1;$i<$count;$i++){
			if(!isset($arrParent[$i])){
				continue;
			}
			$getInfo = User::find($arrParent[$i]);
			if(!$getInfo){
				continue;
			}
			if($i>=3){
				$checkInvest = Investment::where('investment_User', $getInfo->User_ID)->where('investment_Status', 1)->first();
				if(!$checkInvest){
					continue;
				}
			}
			//F30-51 0.1%
			if($i >= 31){
				$percent = $arrPercent[31];
			}elseif($i >= 21){
				$percent = $arrPercent[21];				
			}elseif($i >= 11){
				$percent = $arrPercent[11];				
			}elseif($i >= 6){
				$percent = $arrPercent[6];				
			}else{
				$percent = $arrPercent[$i];
			}

			$directCom = array(
				'Money_User' => $getInfo->User_ID,
				'Money_USDT' => $amountCom*$percent,
				'Money_USDTFee' => 0,
				'Money_Time' => time(),
				'Money_Comment' => 'Commission From F'.$i.' User ID: '.$user->User_ID.'',
				'Money_MoneyAction' => 5,
				'Money_MoneyStatus' => 1,
				'Money_Currency' => $currency,
				'Money_Rate' => $rateCurrency
			);
			$money = Money::insert($directCom);
		}
    }

    public function getHistoryInvestment()
    {
        $RandomToken = Money::RandomToken();
        $user = session('user');
        $history_invest = Investment::join('package', 'package_ID', 'investment_Package')
        ->join('currency', 'Currency_ID' ,'investment_Currency')
        ->where('investment_User', $user->User_ID )
        ->where('investment_Status', '<>', -1)
        ->orderBy('investment_ID', 'DESC')->paginate(15);
        return view('System.History.Investment-History', compact('history_invest', 'RandomToken'));
    }
    
    //get investment statistic before invest
    public function postInvestmentStatistic(Request $request) {
        if (!$request->investment_amount || !is_numeric($request->investment_amount) || $request->investment_amount < 0) {
            return response()->json(['status' => 'error', 'message' => 'Investment amount invalid'], 200);
        }

        $user =  session('user');
        $coinAmount = Money::getBalance()->TRUST;
        if ($request->investment_amount > $coinAmount) {
            return response()->json(['status' => 'error', 'message' => 'Not enough coin'], 200);
        }
        if ($request->investment_amount < 1000) {
            $beforeInvestmentAmount = User::join('investment', 'User_ID', 'investment.investment_User')
                ->select('investment.investment_Amount')
                ->where('investment_Status', 1)
                ->where('User_ID', $user->User_ID)
                ->sum('investment.investment_Amount');
            if ($beforeInvestmentAmount < 1000) {
                return response()->json(['status' => 'error', 'message' => 'Minimum investment of 1,000'], 200);
            }

        }
        $moneyRate = $this->getHttp("https://trustexc.com/api/ticker")[0]->price_usd;
        $investmentStatisticData = [
            'investment_amount_statistic' => $request->investment_amount,
            'investment_total_profit' => $request->investment_amount * 1.5,
            'investment_interest_daily' => $request->investment_amount * 1.5 * 0.004,
            'investment_money_rate' => $moneyRate
        ];
        return response()->json(['data' => $investmentStatisticData, 'status' => 'success'], 200);

    }
    protected function getHttp($url)
    {
        $client = new Client();
        $response = $client->get($url);
        return json_decode($response->getBody());
    }

    public function checkToLevel($userID)
    {
        $userTree = User::where('User_ID', $userID)->value('User_Tree');
        $usersArray = explode(",", $userTree);
        $usersArray = array_reverse($usersArray);
        unset($usersArray[0]);
        foreach ($usersArray as $user) {
            $this->checkLevel($user);
        }
    }

    public function checkLevel($userID) {


        $list_tree = User::where('User_ID', $userID)->value('User_Tree');

        $total_sales_branch = User::join('investment', 'investment_User', 'User_ID')->where('User_Tree', 'LIKE', $list_tree.',%')->where('investment_Status', 1)->sum(DB::raw('investment_Amount*investment_Rate'));
        if($total_sales_branch < 200000){
        	return false;
        }
        $userAgencyLevel = 1;
        $agency_level_S1 = User::where('User_Tree', 'LIKE', $list_tree.',%')->where('User_Agency_Level', '>=', 1)->get()->count();
        if($agency_level_S1 >= 3){
            $userAgencyLevel = 2;
        }

        $agency_level_S2 = User::where('User_Tree', 'LIKE', $list_tree.',%')->where('User_Agency_Level', '>=', 2)->get()->count();
        if($agency_level_S2 >= 3){
            $userAgencyLevel = 3;
        }

        $agency_level_S3 = User::where('User_Tree', 'LIKE', $list_tree.',%')->where('User_Agency_Level', '>=', 3)->get()->count();
        if($agency_level_S3 >= 3){
            $userAgencyLevel = 4;
        }
        $agency_level_S4 = User::where('User_Tree', 'LIKE', $list_tree.',%')->where('User_Agency_Level', '>=', 4)->get()->count();
        if($agency_level_S4 >= 3){
            $userAgencyLevel = 5;
        }
        $agency_level_S5 = User::where('User_Tree', 'LIKE', $list_tree.',%')->where('User_Agency_Level', '>=', 5)->get()->count();
        if($agency_level_S5 >= 3){
            $userAgencyLevel = 6;
        }
        $agency_level_S6 = User::where('User_Tree', 'LIKE', $list_tree.',%')->where('User_Agency_Level', '>=', 6)->get()->count();
        if($agency_level_S6 >= 3){
            $userAgencyLevel = 7;
        }
        $agency_level_S7 = User::where('User_Tree', 'LIKE', $list_tree.',%')->where('User_Agency_Level', '>=', 7)->get()->count();
        if($agency_level_S7 >= 3){
            $userAgencyLevel = 8;
        }
		
        //update level
        $levelCurrent = User::where('User_ID', $userID)->value('User_Agency_Level');
        if($userAgencyLevel != $levelCurrent){

	        Log::insertLog($userID, 'Level Up Affiliate', '000000',  "Up to Level $userAgencyLevel");
	        User::where('User_ID', $userID)->update(['User_Agency_Level'=> $userAgencyLevel]);
		}
        

    }

    
    public function getTreeInvest($userID, $timeCheck) {
        $userTree = User::where('User_ID', $userID)->value('User_Tree');
        $amount = Investment::join('users', 'Investment_User', 'users.User_ID')
            ->where('users.User_Tree', 'like', "$userTree,%")
            ->where('investment_Time', '>=', $timeCheck)
            ->where('investment_Status', 1)
            ->selectRaw('investment_Amount*investment_Rate as a')->get()->sum('a');
        return $amount;

    }

    public function postActionRefund(Request $req, $id){
        //check spam
        $checkSpam = DB::table('string_token')->where('User', Session('user')->User_ID)->where('Token', $req->CodeSpam)->first();
        
        
        if($checkSpam == null){
            //khoong toonf taij
            return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Misconduct!']);
        }
        else{
            DB::table('string_token')->where('User', Session('user')->User_ID)->delete();
        }
        

        //refund
        $refund = Investment::where('investment_ID', $id)->where('investment_User', Session('user')->User_ID)->where('investment_Status', 0)->first();
        if(!$refund){
            return redirect()->back()->with(['flash_level'=>'error','flash_message'=>'Investment Null']);
        }
        //cập nhật status
        $refund->investment_Status = 2;
        $refund->save();
        //Package Time
        $fee_refund = DB::table('package_time')->where('time_Month', $refund->investment_Package_Time)->value('time_Fee');
        if(!$fee_refund){
            return redirect()->back()->with(['flash_level'=>'error','flash_message'=>'Error Error']);
        }
        //Rate
        $rateSOX = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy('SOX');
        //Tiến hành rút gốc
        //Cộng tiền
        $moneyArray = array(
		    'Money_User' => $refund->investment_User,
		    'Money_USDT' => $refund->investment_Amount,
		    'Money_USDTFee' => ($refund->investment_Amount * $fee_refund),
		    'Money_Time' => time(),
			'Money_Comment' => 'Refund Investment '.$refund->investment_Amount. ' SOX '.$refund->investment_Package_Time.' Months',
			'Money_MoneyAction' => 8,
			'Money_MoneyStatus' => 1,
            'Money_Rate' => $rateSOX,
            'Money_CurrentAmount' => $refund->investment_Amount,
			'Money_Currency' => $refund->investment_Currency
        );
        DB::table('money')->insert($moneyArray);
        return redirect()->back()->with(['flash_level'=>'success','flash_message'=>'Refund Investment Success!']);
    }
    public function postActionReinvestment(Request $req, $id){
        //check spam
        $checkSpam = DB::table('string_token')->where('User', Session('user')->User_ID)->where('Token', $req->CodeSpam)->first();
        
        
        if($checkSpam == null){
            //khoong toonf taij
            return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Misconduct!']);
        }
        else{
            DB::table('string_token')->where('User', Session('user')->User_ID)->delete();
        }


        $re_Invest = Investment::where('investment_ID', $id)->where('investment_User', Session('user')->User_ID)->where('investment_Status', 0)->first();
        
        if(!$re_Invest){
            return redirect()->back()->with(['flash_level'=>'error','flash_message'=>'Investment Null']);
        }
        //Rate
        $rateSOX = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy('SOX');
        //cập nhật status
        $re_Invest->investment_Status = 1;
		$re_Invest->investment_ReInvest = 1;
        $re_Invest->investment_TimeOld = $re_Invest->investment_Time;
        $re_Invest->investment_Time = time();
        $re_Invest->save();
        return redirect()->back()->with(['flash_level'=>'success','flash_message'=>'ReInvestment Success!']);
    }
    

}