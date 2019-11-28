<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Model\Investment;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use App\Model\Log;
use App\Model\Money;
use App\Model\Wallet;
use PayusAPI\Http\Client as PayusClient;
use PayusAPI\Resources\Payus;
use IEXBase\TronAPI\Tron;
use Tuupola\Base58;
class TestController extends Controller
{
	public $keyHash	 = 'DAFCOCoorgsafwva'; 
	public $access_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcHBfaWQiOiI1ZGM1MzNhZWQ0NWMwNDJmZTdhY2FlYWQiLCJhcGlfa2V5IjoiWlczTjlLRjVRR00zTks0TkZNTktKQTlMVjZGTFNLNkk3RiIsInVzZXJfaWQiOiI1ZGM1MzI0ZWQ0NWMwNDJmZTdhY2FlODYiLCJpYXQiOjE1NzMyMDQ5MTN9.RdPKuEYcurqtQpNBE38lxTdDqXgbjOZqBNYexRBRVQI';
    public  function testTelegramBot() {
     return strtotime('today');
    }
    protected function getHttp($url)
    {

    }
    
    public function getTest(){
		include(app_path() . '/functions/xxtea.php');
		//reinvest
	    return response(base64_encode(xxtea_encrypt(json_encode(array('id'=>12, 'type'=>2)),$this->keyHash)), 200);
		//transfer
	    return response(base64_encode(xxtea_encrypt(json_encode(array('member'=>583608, 'coin'=>8, 'amount'=>7510.5271, 'otp'=>526803)),$this->keyHash)), 200);
		//invest
	    return response(base64_encode(xxtea_encrypt(json_encode(array('amount'=>1000, 'coin'=>8, 'month'=>12)),$this->keyHash)), 200);
		//withdraw
	    return response(base64_encode(xxtea_encrypt(json_encode(array('from'=>8, 'to'=>8, 'address'=>'testAPI', 'amount'=>100, 'otp'=>482309)),$this->keyHash)), 200);
// 		app('App\Http\Controllers\System\InvestmentController')->checkToLevel(637755);
 	    $string = 'Sfq1jcb5hiXEtRcESxFgkMfraJkKskfGvt';
		// hàm chuyển ví thành hexAddress
	    $abc = Wallet::base58check2HexString($string);
  		$client = new Client();
	    $response = $client->request('POST', 'http://174.138.27.227:8190/wallet/validateaddress', [
		    'json'    => ['address' => $abc],
	    ])->getBody()->getContents();
	    dd(json_decode($response)->result, json_decode($response)->message);
/*
		dd($abc);
*/
// 	    dd((bin2hex('SYTvsEHwtH3mESs1LEVRYwFRaMezEdLZTf')));
/*
	    $fullNode = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');
		$solidityNode = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');
		$eventServer = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');
		
		try {
		    $tron = new \IEXBase\TronAPI\Tron($fullNode, $solidityNode, $eventServer);
		} catch (\IEXBase\TronAPI\Exception\TronException $e) {
		    exit($e->getMessage());
		}
		
		//Generate Address
		dd($tron->isAddress('Sfq9jcb5hiXEtRcESxFgkMfraJkKskfGvt'));
*/

  		$client = new Client();
	    $response = $client->request('GET', 'https://sonicxscan.com/api/transaction?sort=-timestamp&count=true&limit=50&start=0', [
	    ])->getBody()->getContents();
/*
	    $response = $client->request('POST', 'http://174.138.27.227:8190/wallet/getaccount', [
		    'json'    => ['address' => '3f7a8a10dce30ec0be5589439ff55efc373ba49f71'],
	    ])->getBody()->getContents();
*/
	    dd(json_decode($response));

// 	    dd(date('t'));
	    $client = new PayusClient(['access_token' => $this->access_token]);

		$payus = new Payus($client);
		//tạo ví 
// 		$response = $payus->getMyAddresses(['coin'=>'SOX']);

// 		$response = $payus->payusModelApi(['app_id' => '5dc257e6bfd975792684341b', 'api_key' => 'SNTDDAJ8CJJ4FKLZUJYKKVMUOH649X3ECT']);
// 		dd($response);
		$response = $payus->getDepositTransactions(['coin_code' => 'SOX', 'limit'=>20, 'page'=>1, 'time'=>['start'=>1557324618, 'end'=>time()]]);
		dd($response);
	    dd(bcrypt(123456));
/*
	    $abc = json_decode(file_get_contents('https://trustexc.com/api/ticker'))[0]->price_usd;
	    echo ($abc);
	    dd(time());
*/
/*
	    $getCom = Money::join('users','Money_User', 'User_ID')->where('Money_User', 332767)->where('Money_MoneyStatus', '<>', -1)->where('Money_MoneyAction', 6)->where('Money_Time', '>=', strtotime('2019-10-17'))->where('Money_Time', '<', strtotime('2019-10-18'))->sum('Money_USDT');
	    $getInterest = Money::join('users','Money_User', 'User_ID')->whereRaw("User_Tree LIKE '%332767%'")->where('Money_User', '<>', 332767)->where('Money_MoneyStatus', '<>', -1)->where('Money_MoneyAction', 4)->where('Money_Time', '>=', strtotime('2019-10-17'))->where('Money_Time', '<', strtotime('2019-10-18'))->sum(DB::raw('Money_USDT+Money_USDTFee'));
*/
	    $getinvest = Money::join('users','Money_User', 'User_ID')/* ->where("User_Tree" ,"LIKE", "%332767%")->where('Money_User', '<>', 332767) */->where('Money_MoneyStatus', '<>', -1)->where('Money_MoneyAction', 3)/* ->where('Money_Time', '>', strtotime('2019-10-19')) */->where('Money_Time', '<', strtotime('2019-10-24'))->where('User_Level', 0)/* ->where('User_Agency_Level', '<', 1) */->selectRaw('SUM(`Money_USDT`) as totalInvest, Money_User')->value('totalInvest');
	    dd($getinvest);
	    
	    dd(time());
		$checkWallet = $this->checkWalletTRUST('Tg5HzGmEHJcV56h6thgdE9avD2N8WWg3h41');
		dd($checkWallet);
	    $userWrong = array();
	    foreach($getUserUpdatedWallet as $user){
		    $checkWallet = $this->checkWalletTRUST($user->User_WalletGTC);
			if($checkWallet === false){
				$userWrong[] = $user->User_ID;
			}
	    }
	    dd($userWrong);
    }
    
    public function checkWalletTRUST($address){
		$pattern = '/^[a-zA-Z0-9]{34}$/';
	    $first = substr($address,0,1);
	    if($first !== 'T'){
		    return false;
	    }
	    $leng = strlen($address);
	    if($leng != 34){
		    return false;
	    }

	    if (!preg_match($pattern, $address)) {
		    return false;
	    }
	    return true;
    }
}

