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

use App\Model\User;
use App\Model\Money;
use App\Model\Wallet;
use App\Model\Investment;
use DB;
use Mail;
use App\Http\Controllers\System\CoinbaseController;

class InvestmentController extends Controller
{


	public $successStatus = 200;
	public $fee = 0.003;
	public $keyHash	 = 'DAFCOCoorgsafwva';

	public function coinbase()
	{
		$apiKey = 'E08pbjcG026NoOFA';
		$apiSecret = 'SMKMp5kkGaFyDyjcaBX9S4nlu0rfhqTd';

		$configuration = Configuration::apiKey($apiKey, $apiSecret);
		$client = Client::create($configuration);

		return $client;
	}

	public function postRefundInvest(Request $req)
	{
		include(app_path() . '/functions/xxtea.php');

		$data = json_decode(xxtea_decrypt(base64_decode($req->data), $this->keyHash));

		if (!$req->data || $data == '') {
			return response(base64_encode(xxtea_encrypt(json_encode(array('status' => false, 'message' => 'Miss Data')), $this->keyHash)), 200);
		}

		if (!$data->id) {
			return response(base64_encode(xxtea_encrypt(json_encode(array('status' => false, 'message' => 'Miss Amount USD')), $this->keyHash)), 200);
		}

		if (!$data->type) {
			return response(base64_encode(xxtea_encrypt(json_encode(array('status' => false, 'message' => 'Miss Coin')), $this->keyHash)), 200);
		}
		$user = Auth::user();

		//refund
		$refund = Investment::where('investment_ID', $data->id)->where('investment_User', $user->User_ID)->where('investment_Status', 0)->first();
		//         var_dump($data, $refund);exit;
		if (!$refund) {
			return response(base64_encode(xxtea_encrypt(json_encode(array('status' => false, 'message' => 'Error! Investment ID Not Found Or Confirmed!')), $this->keyHash)), 200);
		}
		// REINVEST
		if ($data->type == 2) {
			$refund->investment_Status = 1;
			$refund->investment_ReInvest = 1;
			$refund->investment_TimeOld = $refund->investment_Time;
			$refund->investment_Time = time();
			$refund->save();
			return response(base64_encode(xxtea_encrypt(json_encode(array('status' => true, 'message' => 'ReInvest complete')), $this->keyHash)), 200);
		}
		//REFUND INVESTMENT
		//cập nhật status
		$refund->investment_Status = 2;
		$refund->save();
		//Package Time
		$fee_refund = DB::table('package_time')->where('time_Month', $refund->investment_Package_Time)->value('time_Fee');
		if (!$fee_refund) {
			return response(base64_encode(xxtea_encrypt(json_encode(array('status' => false, 'message' => 'Error! Please Contact Admin!')), $this->keyHash)), 200);
		}
		//Rate
		$rateSOX = CoinbaseController::coinRateBuy('SOX');
		//Tiến hành rút gốc
		//Cộng tiền
		$moneyArray = array(
			'Money_User' => $refund->investment_User,
			'Money_USDT' => $refund->investment_Amount,
			'Money_USDTFee' => ($refund->investment_Amount * $fee_refund),
			'Money_Time' => time(),
			'Money_Comment' => 'Refund Investment ' . $refund->investment_Amount . ' SOX ' . $refund->investment_Package_Time . ' Months',
			'Money_MoneyAction' => 8,
			'Money_MoneyStatus' => 1,
			'Money_Rate' => $rateSOX,
			'Money_CurrentAmount' => $refund->investment_Amount,
			'Money_Currency' => $refund->investment_Currency
		);
		DB::table('money')->insert($moneyArray);

		$balance = Money::getBalance($user->User_ID);
		return response(base64_encode(xxtea_encrypt(json_encode(array('status' => true, 'message' => 'Refund Investment complete', 'balance' => $balance)), $this->keyHash)), 200);
	}

	public function getPackage(Request $req)
	{
		include(app_path() . '/functions/xxtea.php');

		$user = Auth::user();
		$RandomToken = Money::RandomTokenAPI($user->User_ID);
		$package = DB::table('investment')
			->join('currency', 'Currency_ID','investment_Currency')
			->where('investment_User', $user->User_ID)
			->get();
		
		$arrayReturn = array();
		$arr_month = [
			9 => 10,
			12 => 8,
			18 => 6,
			24 => 4,
			36 => 1
		];
		$totalPresent = 0;
		foreach ($package as $v) {
			$amount_USD = $v->investment_Amount * $v->investment_Rate;
			$check_package = DB::table('package')->where([
				['package_Min', '<=', $amount_USD],
				['package_Max', '>', $amount_USD]
			])->first();
			// $t =  date('Y-m-d H:i:s',strtotime('-30 minutes', time()));
			// print_r($check_package->package_Interest);
			// exit;
			$timestamp = strtotime('+' . $v->investment_Package_Time . ' month', $v->investment_Time) - time();
			if ($v->investment_Status == 1) {
				$totalPresent += $amount_USD;
			}
			$pecent = 0;
			$arrayReturn[] = array(
				'id' => $v->investment_ID,
				'amount' => $v->investment_Amount,
				'symbol' => $v->Currency_Symbol,
				'insurran' => $v->investment_Insurrance,
				'rate' => $v->investment_Rate + 0,
				'time' => date('Y-m-d', $v->investment_Time),
				'expiry_date' => $timestamp,
				'status' => $v->investment_Status,
				'fee' => $pecent,
				'interest'	=> $check_package->package_Interest * 100,
			);
		}
		return response(base64_encode(xxtea_encrypt(json_encode(array('status' => true, 'data' => $arrayReturn, 'array_month' => $arr_month, 'TotalInvest' => $totalPresent)), $this->keyHash)), 200);
	}

	public function postInvestment(Request $req)
	{

		include(app_path() . '/functions/xxtea.php');

		// echo urlencode(base64_encode(xxtea_encrypt(json_encode(array('amount' => 200, 'coin'=>5,'month'=>9)), $this->keyHash)));

		// exit;

		$data = json_decode(xxtea_decrypt(base64_decode($req->data), $this->keyHash));

		if (!$req->data || $data == '') {
			return response(base64_encode(xxtea_encrypt(json_encode(array('status' => false, 'message' => 'Miss Data')), $this->keyHash)), 200);
		}

		if (!isset($data->amount)) {
			return response(base64_encode(xxtea_encrypt(json_encode(array('status' => false, 'message' => 'Miss Amount USD')), $this->keyHash)), 200);
		}

		if (!isset($data->coin)) {
			return response(base64_encode(xxtea_encrypt(json_encode(array('status' => false, 'message' => 'Miss Coin')), $this->keyHash)), 200);
		}

		$user = Auth::user();
		$arrCoin = [5 => 'USD', 8 => 'SOX'];
		//Check cố định Amount
		if ($data->amount <= 0) {
			return response(base64_encode(xxtea_encrypt(json_encode(array('status' => false, 'message' => 'Investment amount invalid!')), $this->keyHash)), 200);
		}
		if (!isset($arrCoin[$data->coin])) {
			return response(base64_encode(xxtea_encrypt(json_encode(array('status' => false, 'message' => 'Invalid currency!')), $this->keyHash)), 200);
		}
		//RATE
		$rate = CoinbaseController::coinRateBuy();
		//Balance
		$balance = Money::getBalanceXXX($user->User_ID, $data->coin);
		if ($data->coin == 8) {
			//currency == 8(SOX)
			$amount = $data->amount / $rate['SOX'];
			if ($amount > $balance) {
				return response(base64_encode(xxtea_encrypt(json_encode(array('status' => false, 'message' => 'Your balance is not enough!')), $this->keyHash)), 200);
			}
		} else {
			//currency == 5(USDX)
			$amount = $data->amount;
			if ($amount > $balance) {
				return response(base64_encode(xxtea_encrypt(json_encode(array('status' => false, 'message' => 'Your balance is not enough!')), $this->keyHash)), 200);
			}
		}
		//Min and Max của gói đầu tư
		$package_info = DB::table('package')->where('package_Min', '<=', $data->amount)->where('package_Max', '>', $data->amount)->where('package_Status', 1)->first();

		if (!$package_info) {
			if ($data->amount < 200) {
				return response(base64_encode(xxtea_encrypt(json_encode(array('status' => false, 'message' => 'Min invest $200!')), $this->keyHash)), 200);
			}

			return response(base64_encode(xxtea_encrypt(json_encode(array('status' => false, 'message' => 'PACKAGE Is Null!')), $this->keyHash)), 200);
		}
		// thông tin thòi gian gói hết hạn
		$package_time_info = DB::table('package_time')->where('time_Month', $data->month)->where('time_Status', 1)->first();

		if (!$package_time_info) {
			return response(base64_encode(xxtea_encrypt(json_encode(array('status' => false, 'message' => 'PACKAGE TIME Is Null!')), $this->keyHash)), 200);
		}
		//Get RATE balance
		$name_coin = $arrCoin[$data->coin];

		//Trừ tiền 
		$moneyArray = array(
			'Money_User' => $user->User_ID,
			'Money_USDT' => -$amount,
			'Money_USDTFee' => 0,
			'Money_Time' => time(),
			'Money_Comment' => 'Investment ' . $amount . ' ' . $arrCoin[$data->coin] . ' ' . $data->month . ' Months',
			'Money_MoneyAction' => 3,
			'Money_MoneyStatus' => 1,
			'Money_Rate' => $rate[$name_coin],
			'Money_CurrentAmount' => $amount,
			'Money_Currency' => $data->coin
		);
		//Invest
		$invest = array(
			'investment_User' => $user->User_ID,
			'investment_Amount' => $data->coin == 8 ? $amount : $amount / $rate['SOX'],
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
		app('App\Http\Controllers\System\InvestmentController')->checkToLevel($user->User_ID);
		//checkDirectCom
		app('App\Http\Controllers\System\InvestmentController')->checkDirectCom($user, $data->amount);

		$balance = Money::getBalance($user->User_ID);
		$arrBalance= ['USDX' => $balance->USD , 'SOX' => $balance->SOX ];
		// var_dump($arrBalance);
		// exit;
		return response(base64_encode(xxtea_encrypt(json_encode(array('status' => true, 'message' => 'Investment complete', 'balance' => $arrBalance)), $this->keyHash)), 200);
	}
}
