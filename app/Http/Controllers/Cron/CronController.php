<?php

namespace App\Http\Controllers\Cron;

use App\Model\Money;
use App\Model\User;
use App\Model\Wallet;
use App\Model\Investment;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use DB;

use PayusAPI\Http\Client as PayusClient;
use PayusAPI\Resources\Payus;
use App\Jobs\SendTelegramJobs;
class CronController extends Controller
{
	public $bigAddress = 'SXVXhGaNrGXuEmhzX2vVq3itzk2P9syCd2';
	
    public function getDeposit(Request $req){
	    
	    $coin = DB::table('currency')->where('Currency_Symbol', $req->coin)->first();
	    if(!$coin){
		    dd('coin not exit');
	    }
	    $symbol = $coin->Currency_Symbol;
	    $blockcypher = 'https://api.blockcypher.com/v1/'.strtolower($symbol).'/main/txs/';
	    $transactions = app('App\Http\Controllers\System\CoinbaseController')->getAccountTransactions($symbol);
	    $rate = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy($symbol);
	    $priceCoin = $rate;
        foreach($transactions as $v){
	        if($v->getamount()->getamount() > 0){
				$hash = Money::where('Money_Address', $v->getnetwork()->gethash())->first();
				
				if(!$hash){
					$client = new \GuzzleHttp\Client();
					$res = $client->request('GET', $blockcypher.$v->getnetwork()->gethash());
					$response = $res->getBody(); 
					$json = json_decode($response);
					
					$addArray = array();
					
					foreach($json->addresses as $j){
						if($coin->Currency_Symbol == 'ETH'){
							$addArray[] = '0x'.$j;	
						}else{
							$addArray[] = $j;	
						}
					}
					
					$address = Wallet::join('users', 'users.User_ID', 'Address_User')->select('Address_User','User_Email')->whereIn('Address_Address', $addArray)->first();

					if($address){
                        $amount = $v->getamount()->getamount();

						$money = new Money();
						$money->Money_User = $address->Address_User;
						$money->Money_USDT = $amount*$priceCoin;
						$money->Money_Time = time();
						$money->Money_Comment = 'Deposit '.$amount.' '.$symbol;
						$money->Money_Currency = $coin->Currency_ID;
						$money->Money_MoneyAction = 1;
						$money->Money_Address = $v->getnetwork()->gethash();
						$money->Money_CurrentAmount = $amount;
						$money->Money_Rate = $priceCoin;
						$money->Money_MoneyStatus = 1;
                        $money->save();	
                        //deposit -354905750
                        
						// 	Gửi telegram thông báo User verify
						$message = "$address->User_Email Deposit $amount $symbol\n"
								. "<b>User ID: </b> "
								. "$address->Address_User\n"
								. "<b>Email: </b> "
								. "$address->User_Email\n"
								. "<b>Amount: </b> "
								. $amount." $symbol\n"
								. "<b>Rate: </b> "
								. "$ $priceCoin \n"
								. "<b>Submit Deposit Time: </b>\n"
								. date('d-m-Y H:i:s',time());
									
				        dispatch(new SendTelegramJobs($message, -396562973));
					}
					
				}   
		    }
        }
		echo 'check deposit success';exit;
    }
    
    public function getDepositSOX(){
// 	    $payus = app('App\Http\Controllers\System\CoinbaseController')->Payus();
/*
		$response = $payus->getDepositTransactions(['coin_code' => 'SOX', 'limit'=>20, 'page'=>1, 'time'=>['start'=>strtotime('2019-11-01'), 'end'=>time()]]);
		if(!$response || !isset($response->data) || $response->data->status != 'success'){
			dd('Payus Error!');
		}
		$transactions = $response->data->data->transactions;
*/
  		$client = new Client();
	    $response = $client->request('GET', 'https://sonicxscan.com/api/transaction?sort=-timestamp&count=true&limit=50&start=0', [
	    ])->getBody()->getContents();
	    $transactions = json_decode($response)->data;

	    $rate = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy('SOX');
	    $priceCoin = $rate;
		foreach($transactions as $v){
// 			dd(date('Y-m-d H:i:s', $v->timereceived));
// 			dd($transactions);
			$hash = Money::where('Money_Address', $v->hash)->first();
			if(!$hash){
				$address = $v->toAddress;
				$infoAddress = Wallet::join('users', 'users.User_ID', 'Address_User')->select('Address_User','User_Email')->where('Address_Address', $address)->first();

				if($infoAddress){
                    $amount = $v->contractData->amount/1000000;

					$money = new Money();
					$money->Money_User = $infoAddress->Address_User;
					$money->Money_USDT = $amount;
					$money->Money_Time = time();
					$money->Money_Comment = 'Deposit '.($amount+0).' SOX';
					$money->Money_Currency = 8;
					$money->Money_MoneyAction = 1;
					$money->Money_Address = $v->hash;
					$money->Money_CurrentAmount = $amount;
					$money->Money_Rate = $priceCoin;
					$money->Money_MoneyStatus = 1;
                    $money->save();	
                    //deposit -354905750
                    
					// 	Gửi telegram thông báo User verify
					$message = "$infoAddress->User_Email Deposit $amount SOX\n"
							. "<b>User ID: </b> "
							. "$infoAddress->Address_User\n"
							. "<b>Email: </b> "
							. "$infoAddress->User_Email\n"
							. "<b>Amount: </b> "
							. $amount." SOX\n"
							. "<b>Rate: </b> "
							. "$ $priceCoin \n"
							. "<b>Submit Deposit Time: </b>\n"
							. date('d-m-Y H:i:s',time());
								
			        dispatch(new SendTelegramJobs($message, -396562973));

				    $this->TransferToAddress($address);
				}
				
			} 
		}
		echo 'check deposit success';exit;
    }
    
    public function TransferToAddress($from, $amount = 0, $to = 'SXVXhGaNrGXuEmhzX2vVq3itzk2P9syCd2', $action = 'Send To Big Address'){
	    $checkAddressTo = Wallet::where('Address_Address', $to)->where('Address_Currency', 8)->first();
	    if($checkAddressTo){
		    $hexAddress = $checkAddressTo->Address_HexAddress;
	    }else{
		    $hexAddress = Wallet::base58check2HexString($to);
		    if(!$hexAddress){
				$dataLog = [
					'Log_Sox_From' => $from,
					'Log_Sox_To' => $to,
					'Log_Sox_Amount' => 0,
					'Log_Sox_Action' => $action,
					'Log_Sox_Comment' => 'Transfer From '.$from.' To '.$to,
					'Log_Sox_Error' => 'Error Hex Address',
					'Log_Sox_Time' => date('Y-m-d H:i:s'),
					'Log_Sox_Status' => 1
				];
				DB::table('log_sox')->insert($dataLog);
			    return false;
		    }
	    }
	    $checkAddress = Wallet::where('Address_Address', $from)->where('Address_Currency', 8)->first();

	    if(!$checkAddress){
			$dataLog = [
				'Log_Sox_From' => $from,
				'Log_Sox_To' => $to,
				'Log_Sox_Amount' => 0,
				'Log_Sox_Action' => $action,
				'Log_Sox_Comment' => 'Transfer From '.$from.' To '.$to,
				'Log_Sox_Error' => 'Address SOX Not found in Databse',
				'Log_Sox_Time' => date('Y-m-d H:i:s'),
				'Log_Sox_Status' => 1
			];
			DB::table('log_sox')->insert($dataLog);
		    return false;
	    }
  		$client = new Client();
	    $response = $client->request('POST', 'http://174.138.27.227:8190/wallet/getaccount', [
		    'json'    => ['address' => $checkAddress->Address_HexAddress],
	    ])->getBody()->getContents();

	    $data = json_decode($response);
	    if(!isset($data->balance) || $data->balance <= 0){
			$dataLog = [
				'Log_Sox_From' => $from,
				'Log_Sox_To' => $to,
				'Log_Sox_Amount' => 0,
				'Log_Sox_Action' => $action,
				'Log_Sox_Comment' => 'Transfer From '.$from.' To '.$to,
				'Log_Sox_Error' => 'Account Not Found Data',
				'Log_Sox_Time' => date('Y-m-d H:i:s'),
				'Log_Sox_Status' => 1
			];
			DB::table('log_sox')->insert($dataLog);
		    return false;
	    }
		$balance = round($amount > 0 ? $amount*1000000 : $data->balance);
		if($balance > $data->balance){
			$dataLog = [
				'Log_Sox_From' => $from,
				'Log_Sox_To' => $to,
				'Log_Sox_Amount' => $amount,
				'Log_Sox_Action' => $action,
				'Log_Sox_Comment' => 'Transfer From '.$from.' To '.$to,
				'Log_Sox_Error' => 'Balance Is Not Enough',
				'Log_Sox_Time' => date('Y-m-d H:i:s'),
				'Log_Sox_Status' => 1
			];
			DB::table('log_sox')->insert($dataLog);
			return false;
		}

  		$client = new Client();
	    $response = $client->request('POST', 'http://174.138.27.227:8190/wallet/easytransferbyprivate', [
		    'json'    => ['privateKey' => $checkAddress->Address_PrivateKey, 
		    			  'toAddress' => $hexAddress,
		    			  'amount' => $balance
		    			 ],
	    ])->getBody()->getContents();
	    $dataSend = json_decode($response);
	    if(isset($dataSend->result->result) && $dataSend->result->result == true){
			$dataLog = [
				'Log_Sox_From' => $checkAddress->Address_HexAddress,
				'Log_Sox_To' => $hexAddress,
				'Log_Sox_Amount' => $balance/1000000,
				'Log_Sox_Action' => $action,
				'Log_Sox_Hash' => $dataSend->transaction->txID,
				'Log_Sox_Comment' => 'Transfer '.($balance/1000000).' SOX From '.$from.' To '.$to,
				'Log_Sox_Time' => date('Y-m-d H:i:s'),
				'Log_Sox_Status' => 1
			];
			DB::table('log_sox')->insert($dataLog);
			return true;
	    }
		$message = hex2bin($dataSend->result->message);
		$dataLog = [
			'Log_Sox_From' => $checkAddress->Address_HexAddress,
			'Log_Sox_To' => $hexAddress,
			'Log_Sox_Amount' => $balance/1000000,
			'Log_Sox_Action' => $action,
			'Log_Sox_Comment' => 'Transfer '.($balance/1000000).' SOX From '.$from.' To '.$to,
			'Log_Sox_Error' => $message,
			'Log_Sox_Time' => date('Y-m-d H:i:s'),
			'Log_Sox_Status' => 1
		];
		DB::table('log_sox')->insert($dataLog);
	    return false;
    }
    
    public function payInterest()
    {
        $timeCurrent = date("H");
//        dd(date('Y-m-d H:i:s',strtotime('-2 hours 30 minutes')));
        if ($timeCurrent != "00") {
	        dd('time out');
        }
		$dateOfMonth = date('t');
		
        // nếu đã xuất hiện 1 lệnh bất kì thì ngưng trả lãi luôn
		$checkInterestToday = Money::where('Money_MoneyAction', 4)->where('Money_Time', '>', strtotime('today'))->first();
		if($checkInterestToday){
			echo 'paid interest today!'; exit;
		}

/*
        $investmentStatictis = Investment::selectRaw("investment_Rate, investment_Time, investment_ID, investment_User, (investment_Amount) as amount")
						                ->where('investment_Status', 1)
						                ->get();
*/
        $investmentStatictis = Investment::selectRaw("investment_Rate, investment_Time, investment_ID, investment_User, (investment_Amount*investment_Rate) as amountUSD")
						                ->where('investment_Status', 1)
						                ->get();

	    $rate = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy('SOX');
	    $moneyRate = $rate;
        foreach ($investmentStatictis as $investmentItem){
            if (time() - $investmentItem->investment_Time < 86400) {
                continue;
            }
            $userID = $investmentItem->investment_User;
            $checkIntested = Money::where('Money_User', $userID)->where('Money_Investment', $investmentItem->investment_ID)->where('Money_MoneyAction', 4)->where('Money_Time', '>', strtotime('today'))->first();
			
            if ($checkIntested) {
                continue;
            }
//             $percentMonth = DB::table('package')->whereRaw(($investmentItem->amount*$investmentItem->investment_Rate)." >= `package_Min` and ".($investmentItem->amount*$investmentItem->investment_Rate)." < `package_Max`")->value('package_Interest');
			$percentMonth = DB::table('package')->whereRaw(($investmentItem->amountUSD)." >= `package_Min` and ".($investmentItem->amountUSD)." < `package_Max`")->value('package_Interest');
            if(!$percentMonth){
                $percentMonth = 0.08;
            }
            $percent = $percentMonth / $dateOfMonth;
            
            $interestDaily = $percent * $investmentItem->amountUSD / $moneyRate;
            $userAgencyLevel = User::where('User_ID', $userID)->value('User_Agency_Level');
            
            $interestData = [
                'Money_User' => $userID,
                'Money_USDT' => $interestDaily,
                'Money_USDTFee' => 0,
                'Money_Investment' => $investmentItem->investment_ID,
                'Money_Time' => time(),
                'Money_Comment' => 'Daily Interest '.($investmentItem->amountUSD+0).' SOX',
                'Money_MoneyAction' => 4,
                //status 1 thì vào balance 2 thì chi trực tiếp 
                'Money_MoneyStatus' => 2,
                'Money_Currency' => 8,
                'Money_CurrentAmount' => $interestDaily,
                'Money_Rate' => $rate,
                'Money_Confirm' => 0
            ];

            Money::create($interestData);
			
			$this->checkInvestmentExpired($investmentItem->investment_ID);
			
            $this->payInterestToAgencyLevel($userID, $interestDaily, $userAgencyLevel, $rate);
        
        }
        echo 'pay daily interest success'; exit;
    }
    
    public function AutoPayInterest(){
	    $getInterest = Money::join('users', 'Money_User', 'User_ID')->where('Money_Time', '>', strtotime('today'))->where('Money_MoneyStatus', 2)->where('Money_MoneyAction', 4)->where('Money_Confirm', 0)->get();
	    $bigAddress = $this->bigAddress;
	    dd($getInterest,$bigAddress);
		foreach($getInterest as $interest){
			if($interest->User_Level == 0 ){
				if(!$interest->User_WalletAddress){
					continue;
				}
				$this->TransferToAddress($bigAddress, $interest->Money_USDT, $interest->User_WalletAddress, 'Send Interest');
			}
			$interest->Money_Confirm = 1;
			$interest->save();
		}
    }
    
    public function checkInvestmentExpired($id){
	    $investment = Investment::join('package_time', 'investment_Package_Time', 'time_Month')->where('investment_ID', $id)->first();
	    $time = strtotime('+'.$investment->time_Month.' month', $investment->investment_Time);
	    if(!$investment){
		    return false;
	    }

	    if(strtotime('+'.$investment->time_Month.' month', $investment->investment_Time) <= time()){
		    $investment->investment_Status = 2;
		    $investment->save();
		    return true;
	    }
	    return false;
    }

    public function payInterestToAgencyLevel($userID, $interestDaily, $userAgencyLevelParam, $rate)
    {
        $percenInteret = [0, 0.05, 0.07, 0.09, 0.11, 0.13, 0.15, 0.17, 0.2];
        $moneyRate = $rate;
        $userTree = User::where('User_ID', $userID)->value('User_Tree');
        $usersArray = explode(',', $userTree);
        $usersArray = array_reverse($usersArray);
        unset($usersArray[0]);
        foreach ($usersArray as $user) {
            $userAgencyLevel = User::where('User_ID', $user)->where('User_Agency_Level', '>=', 1)->value('User_Agency_Level');
            if (!$userAgencyLevel) {
	            continue;
            }
            if ($userAgencyLevel <= $userAgencyLevelParam) {
                continue;
            }
            $userAgencyLevelParam = $userAgencyLevel;
            $interestData = [
                'Money_User' => $user,
                'Money_USDT' => $interestDaily * $percenInteret[$userAgencyLevel],
                'Money_Time' => time(),
                'Money_MoneyAction' => 6,
                'Money_MoneyStatus' => 1,
                'Money_Currency' => 8,
                'Money_Comment' => "Affiliate Commission From User ID: $userID",
                'Money_CurrentAmount' => $interestDaily * $percenInteret[$userAgencyLevel],
                'Money_Rate' => floatval($moneyRate),
                'Money_Confirm' => 1
            ];
            Money::create($interestData);
        }
    }

    public function payWithdraws()
    {

        $withdrawList = Money::join('address', 'Money_User', 'address.Address_User')
            ->where('Money_MoneyAction', 2)
            ->where('Money_Confirm', 0)
            ->select('address.Address_Address AS fromAddress', 'Money_USDT', 'Money_Rate', 'Money_Address', 'Money_ID', 'Money_User')
            ->get();
        foreach ($withdrawList as $withdrawItem) {
            $withdrawAmount = abs($withdrawItem->Money_USDT);
            $walletBalance = $this->getHttp("http://trustexc.com/api/get_balance");
            if ($walletBalance < $withdrawAmount) {
                $currentTime = date('Y-m-d H:i:s');
                $message = "Not enough money to pay, please add more coins to the trust wallet\n".
                "Current coins amount: $walletBalance\n".
                "Date: $currentTime";
                $client = new \GuzzleHttp\Client(); //GuzzleHttp\Client
                $result = json_decode($client->request('POST', 'https://adcgame.club/api/sendMessage',[
                    'form_params' => [
                        'channel' => "-382331688",
                        'message' => $message
                    ]
                ])->getBody()->getContents());
                return 0;
            } else {
                $updateStatus = Money::where('Money_ID', $withdrawItem->Money_ID)
                    ->where('User_Level', 0)
                    ->update(['Money_Confirm' => 1]);
                if ($updateStatus) {
                    $url = "https://trustexc.com/api/send?coin=trust&from=TqAVWhNz24tD6ExjoEZx7FgskvNbg3NeQz&to=$withdrawItem->Money_Address&amount=$withdrawAmount";
                    $withdrawSendData = $this->getHttp($url);
                    if ($withdrawSendData->status == 1) {
                        $user = User::where('User_ID', $withdrawItem->Money_User)
                            ->select('User_ID', 'User_Name', 'User_Email')
                            ->first();
                        $data = array('name' => $user->User_Name, 'withdrawID' => $withdrawItem->Money_ID);
                        Mail::send('Mails.Withdraw-confirm', $data, function ($msg) use ($user) {
                            $msg->from('do-no-reply@trustcoinbox.com', 'TrustCoinBox');
                            $msg->to($user->User_Email)->subject('Withdrawal Successful');
                        });


                    } else {
                        Money::where('Money_ID', $withdrawItem->Money_ID)->update(['Money_Confirm' => -1]);
                    }
                }

            }

        }
	}
	public function getPriceCoin(){

		$rate = app('App\Http\Controllers\System\CoinbaseController')->coinRateBuy();
		return $rate;
	}

	
	public function setPriceCoin(){
		$client = new Client();
		$response = $client->request('GET', 'https://worldex-core.azurewebsites.net/frontapi/api/Transaction/GetMarketCap/SOX_USDX')->getBody()->getContents();
		$rate = json_decode($response)->response->LastPrice;
		if(!$rate){
			dd('Error Rate SOX');
		}
		$arg = [
			'Changes_Price' => $rate,
			'Changes_Time' => Date('y-m-d'),
			'Changes_Hour' => 0,
			'Changes_Status' => 1,
			'Log' => 1,
		];
		DB::table('changes')->insert($arg);
		dd('Success Rate SOX');
	}
	public function totalSalesMonth(){
		//find lv up 4
		$level_up_4 = User::where('User_Agency_Level','>=', 4)->select('User_ID', 'User_Tree', 'User_Agency_Level')->get();
		if(!$level_up_4){
			dd('No level found above 4');
		}
		foreach($level_up_4 as $item){
			
			//doanh so thang truoc
			$old_sales = DB::table('sales')->where('sales_User', $item->User_ID)->orderBy('sales_Date', 'DESC')->sum('sales_Sales');
			
			//doanh so thang hien tai
			$current_sales = \App\Model\Investment::join('users', 'User_ID', 'investment_User')->where('User_Tree', 'LIKE', $item->User_Tree.',%')->where('investment_Status', 1)->sum('investment_Amount');
			//bonus 30%

			$old_sales_if_up_30_percent = $old_sales + $old_sales * 0.3;

			if($old_sales_if_up_30_percent && $old_sales_if_up_30_percent >= $current_sales){
				if($item->User_Agency_Level == 4){
					$bonus_usd = 5000;
				}
				elseif($item->User_Agency_Level == 5){
					$bonus_usd = 10000;
				}
				elseif($item->User_Agency_Level == 6){
					$bonus_usd = 20000;
				}
				elseif($item->User_Agency_Level == 7){
					$bonus_usd = 50000;
				}
				elseif($item->User_Agency_Level == 8){
					$bonus_usd = 100000;
				}
				//Check duplicate
				$check_bonus_duplicate = Money::where('Money_User', $item->User_ID)->where('Money_MoneyStatus', 1)->where('Money_MoneyAction', 9)->where('Money_Time', '>=', strtotime("today"))->first();
				if(!$check_bonus_duplicate){
					//save
					$interestData = [
						'Money_User' => $item->User_ID,
						'Money_USDT' => $bonus_usd,
						'Money_Time' => time(),
						'Money_MoneyAction' => 9,
						'Money_MoneyStatus' => 1,
						'Money_Currency' => 5,
						'Money_Comment' => 'Bonus Level '.$item->User_Agency_Level,
						'Money_CurrentAmount' => $bonus_usd,
						'Money_Rate' => 1,
						'Money_Confirm' => 0
					];
					Money::create($interestData);
				}
			}
			//check duplicate
			$check_sales_duplicate = DB::table('sales')->where('sales_User', $item->User_ID)->where('sales_Status', 1)->where('sales_Date', Date('Y-m-d'))->first();
			if(!$check_sales_duplicate){
				$arg_insert = [
					'sales_Level' => $item->User_Agency_Level,
					'sales_User' => $item->User_ID,
					'sales_Sales' => $current_sales,
					'sales_Date' => Date('Y-m-d'),
					'sales_Status' => 1,
				];
				DB::table('sales')->insert($arg_insert);
			}
		}
		dd('done');

	}
}

