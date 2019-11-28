<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Money extends Model
{
	protected $table = 'money';
	public $timestamps = false;

	protected $fillable = ['Money_ID', 'Money_Game', 'Money_User', 'Money_BetAction', 'Money_USDT', 'Money_USDT_Return', 'Money_USDTFee', 'Money_Time', 'Money_Comment', 'Money_MoneyAction', 'Money_MoneyStatus', 'Money_BinaryWeak', 'Money_Package', 'Money_TXID', 'Money_Address', 'Money_Currency', 'Money_Rate', 'Money_Confirm', 'Money_Active'];

	protected $primaryKey = 'Money_ID';

	public static function getBalance($user)
	{
		$result = DB::table('money')
			->where('Money_MoneyStatus', 1)
			->where('Money_User', $user)
			->selectRaw('
						COALESCE(SUM(IF(`Money_Currency` <> 8, `Money_USDT`-`Money_USDTFee`, 0)), 0) AS USD,
						COALESCE(SUM(IF(`Money_Currency` = 8, `Money_USDT`-`Money_USDTFee`, 0)), 0) AS SOX
								')
			->get();
		return $result[0];
	}
	public static function getBalanceXXX($user, $ID_CURRENCY)
	{
		$result = DB::table('money')
			->where('Money_MoneyStatus', 1)
			->where('Money_User', $user);
		if($ID_CURRENCY != 8){
			$result = $result->where('Money_Currency', '<>', 8);
		}
		else{
			$result = $result->where('Money_Currency', '=', 8);
		}
		$result = $result->sum(DB::raw('Money_USDT - Money_USDTFee'));
		return $result;
	}
	public static function getBalanceKyo($user)
	{
		$result = DB::table('money')
			->where('Money_MoneyStatus', 1)
			->where('Money_User', $user)
			->selectRaw('
	    						COALESCE(SUM(IF(`Money_Currency` = 1, `Money_USDT`-`Money_USDTFee`, 0)), 0) AS TRUST
					')->get();
		return $result[0];
	}
	//Check spam request

	public static function RandomToken()
	{
		$code = str_random(32) . '' . rand(10000000, 99999999);
		$CheckCode = DB::table('string_token')->where('Token', $code)->first();
		if (!$CheckCode) {
			//Xóa token của thằng đó đã tạo mà chưa dùng

			$minutest_30p = date('Y-m-d H:i:s',strtotime('-30 minutes', time()));

			$delete = DB::table('string_token')->where('CreateDate', '<=', $minutest_30p)->delete();

			
			//bắt đàu tạo token mới
			$createCode = DB::table('string_token')->insert([
				'Token' => $code,
				'User' => Session('user')->User_ID
			]);
			return $code;
		} else {
			return self::RandomToken();
		}
	}

	// check spam cho app
	public static function RandomTokenAPI($user)
	{
		$code = str_random(32) . '' . rand(10000000, 99999999);
		$CheckCode = DB::table('string_token')->where('Token', $code)->first();
		if (!$CheckCode) {
			//Xóa token của thằng đó đã tạo mà chưa dùng
			$minutest_30p = date('Y-m-d H:i:s',strtotime('-30 minutes', time()));

			$delete = DB::table('string_token')->where('CreateDate', '<=', $minutest_30p)->delete();

			//bắt đàu tạo token mới
			$createCode = DB::table('string_token')->insert([
				'Token' => $code,
				'User' => $user
			]);
			return $code;
		} else {
			return self::RandomTokenAPI($user);
		}
	}


	static function StatisticTotal($where)
	{
		$result = Money::join('users', 'Money_User', 'User_ID')->selectRaw('
			SUM(IF(`Money_Currency` = 8 ' . $where . ', (ROUND((`Money_USDT` - `Money_USDTFee`),8)), 0)) as BalanceSOX,
			SUM(IF(`Money_Currency` != 8 ' . $where . ', (ROUND((`Money_USDT` - `Money_USDTFee`),8)), 0)) as BalanceUSD,
			

			SUM(IF(`Money_Currency` = 1 AND `Money_MoneyAction` = 1 ' . $where . ', ROUND(`Money_USDT` / `Money_Rate`,6), 0)) as DepositBTC, 
			SUM(IF(`Money_Currency` = 2 AND `Money_MoneyAction` = 1 ' . $where . ', ROUND(`Money_USDT` / `Money_Rate`,6), 0)) as DepositETH,
			SUM(IF(`Money_Currency` = 5 AND `Money_MoneyAction` = 1 ' . $where . ', ROUND(`Money_USDT` / `Money_Rate`,6), 0)) as DepositUSD,
			SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 1 ' . $where . ', ROUND(`Money_USDT` / `Money_Rate`,6), 0)) as DepositSOX,



			SUM(IF(`Money_Currency` = 1 AND `Money_MoneyAction` = 2 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),6), 0)) as WithDrawBTC,
			SUM(IF(`Money_Currency` = 2 AND `Money_MoneyAction` = 2 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),6), 0)) as WithDrawETH,
			SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 2 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),6), 0)) as WithDrawSOX,
			
			SUM(IF(`Money_Currency` = 5 AND `Money_MoneyAction` = 7 AND `Money_Comment` LIKE "Give%" ' . $where . ', ROUND(`Money_USDT` - `Money_USDTFee`,6), 0)) as GiveUSD,
			SUM(IF(`Money_Currency` = 5 AND `Money_MoneyAction` = 7 AND `Money_Comment` LIKE "Transfer" ' . $where . ', ROUND(`Money_USDT` - `Money_USDTFee`,6), 0)) as TransferUSD,
			SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 7 AND `Money_Comment` LIKE "Give%" ' . $where . ', ROUND(`Money_USDT` - `Money_USDTFee`,6), 0)) as GiveSOX,
			SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 7 AND `Money_Comment` LIKE "Transfer" ' . $where . ', ROUND(`Money_USDT` - `Money_USDTFee`,6), 0)) as TransferSOX,
			
			SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 3 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),6), 0)) as InvestmentSOX,

			SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 4 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),6), 0)) as InterestSOX,
			
			SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 5 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),6), 0)) as DirectCommissionSOX,
			
			SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 6 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),6), 0)) as AffiliateCommissionSOX,
			
			SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 8 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),6), 0)) as RefundInvestmentSOX')
			->where('User_Status', 1);
		return $result;
	}

	public static function getStatistic($where)
	{

		$result = Money::join('users', 'Money_User', 'User_ID')
			->selectRaw('`Money_User`,
						
						SUM(IF(`Money_Currency` = 8 ' . $where . ', (ROUND((`Money_USDT` - `Money_USDTFee`),8)), 0)) as BalanceSOX,
						SUM(IF(`Money_Currency` != 8 ' . $where . ', (ROUND((`Money_USDT` - `Money_USDTFee`),8)), 0)) as BalanceUSD,
						

						SUM(IF(`Money_Currency` = 1 AND `Money_MoneyAction` = 1 ' . $where . ', ROUND(`Money_USDT` / `Money_Rate`,6), 0)) as DepositBTC, 
						SUM(IF(`Money_Currency` = 2 AND `Money_MoneyAction` = 1 ' . $where . ', ROUND(`Money_USDT` / `Money_Rate`,6), 0)) as DepositETH,
						SUM(IF(`Money_Currency` = 5 AND `Money_MoneyAction` = 1 ' . $where . ', ROUND(`Money_USDT` / `Money_Rate`,6), 0)) as DepositUSD,
						SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 1 ' . $where . ', ROUND(`Money_USDT` / `Money_Rate`,6), 0)) as DepositSOX,



						SUM(IF(`Money_Currency` = 1 AND `Money_MoneyAction` = 2 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),6), 0)) as WithDrawBTC,
						SUM(IF(`Money_Currency` = 2 AND `Money_MoneyAction` = 2 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),6), 0)) as WithDrawETH,
						SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 2 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),6), 0)) as WithDrawSOX,
						
						SUM(IF(`Money_Currency` = 5 AND `Money_MoneyAction` = 7 AND `Money_Comment` LIKE "Give%" ' . $where . ', ROUND(`Money_USDT` - `Money_USDTFee`,6), 0)) as GiveUSD,
						SUM(IF(`Money_Currency` = 5 AND `Money_MoneyAction` = 7 AND `Money_Comment` LIKE "Transfer" ' . $where . ', ROUND(`Money_USDT` - `Money_USDTFee`,6), 0)) as TransferUSD,
						SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 7 AND `Money_Comment` LIKE "Give%" ' . $where . ', ROUND(`Money_USDT` - `Money_USDTFee`,6), 0)) as GiveSOX,
						SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 7 AND `Money_Comment` LIKE "Transfer" ' . $where . ', ROUND(`Money_USDT` - `Money_USDTFee`,6), 0)) as TransferSOX,
						
						SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 3 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),6), 0)) as InvestmentSOX,

						SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 4 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),6), 0)) as InterestSOX,
						
						SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 5 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),6), 0)) as DirectCommissionSOX,
						
						SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 6 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),6), 0)) as AffiliateCommissionSOX,
						
						SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 8 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),6), 0)) as RefundInvestmentSOX')
			->where('Money_MoneyStatus', 1)
			->groupBy('Money_User');
		return $result;
	}
}
