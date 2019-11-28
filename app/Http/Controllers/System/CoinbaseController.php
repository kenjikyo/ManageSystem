<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Resource\Address;
use Coinbase\Wallet\Resource\Account;
use Coinbase\Wallet\Enum\CurrencyCode;
use Coinbase\Wallet\Resource\Transaction;
use Coinbase\Wallet\Value\Money as CB_Money;
use Coinbase\Wallet\Enum\Param;
use DB;

use Sop\CryptoTypes\Asymmetric\EC\ECPublicKey;
use Sop\CryptoTypes\Asymmetric\EC\ECPrivateKey;
use Sop\CryptoEncoding\PEM;
use kornrunner\Keccak;

use PayusAPI\Http\Client as PayusClient;
use PayusAPI\Resources\Payus;

use GuzzleHttp\Client as G_Client;

use App\Model\Wallet;
class CoinbaseController extends Controller{
	public $access_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcHBfaWQiOiI1ZGM1MzNhZWQ0NWMwNDJmZTdhY2FlYWQiLCJhcGlfa2V5IjoiWlczTjlLRjVRR00zTks0TkZNTktKQTlMVjZGTFNLNkk3RiIsInVzZXJfaWQiOiI1ZGM1MzI0ZWQ0NWMwNDJmZTdhY2FlODYiLCJpYXQiOjE1NzMyMDQ5MTN9.RdPKuEYcurqtQpNBE38lxTdDqXgbjOZqBNYexRBRVQI';
    
	public static function coinbase(){
        $apiKey = 'BZwOpLqyp92A75oM';
        $apiSecret = '7UDOIAtJjobFYYonAcxD6YE7rSkqyHTa';

        $configuration = Configuration::apiKey($apiKey, $apiSecret);
        $client = Client::create($configuration);

        return $client;
    }

	public function Payus(){
		$access_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcHBfaWQiOiI1ZGM1MzNhZWQ0NWMwNDJmZTdhY2FlYWQiLCJhcGlfa2V5IjoiWlczTjlLRjVRR00zTks0TkZNTktKQTlMVjZGTFNLNkk3RiIsInVzZXJfaWQiOiI1ZGM1MzI0ZWQ0NWMwNDJmZTdhY2FlODYiLCJpYXQiOjE1NzMyMDQ5MTN9.RdPKuEYcurqtQpNBE38lxTdDqXgbjOZqBNYexRBRVQI';
		
	    $client = new PayusClient(['access_token' => $access_token]);
		$payus = new Payus($client);
		
		return $payus;
	}
	
    public static function coinRateBuy($system = null){
	    if($system == 'ETH' || $system == 'BTC'){
		    $coin[$system] = self::coinbase()->getBuyPrice($system.'-USD')->getAmount();
	    }elseif($system == 'SOX'){

			$sox = DB::table('changes')->orderBy('Changes_ID', 'DESC')->first();
			$coin['SOX'] = $sox->Changes_Price;
			
			

		    // $client = new G_Client();
		    // $response = $client->request('GET', 'https://worldex-core.azurewebsites.net/frontapi/api/Transaction/GetMarketCap/SOX_USDX')->getBody()->getContents();
			// $coin['SOX'] = json_decode($response)->response->LastPrice;
			
		}else{
		    $coin['BTC'] = self::coinbase()->getBuyPrice('BTC-USD')->getAmount();
			$coin['ETH'] = self::coinbase()->getBuyPrice('ETH-USD')->getAmount();
			
			$sox = DB::table('changes')->orderBy('Changes_ID', 'DESC')->first();
			$coin['SOX'] = $sox->Changes_Price;


		    // $client = new G_Client();
		    // $response = $client->request('GET', 'https://worldex-core.azurewebsites.net/frontapi/api/Transaction/GetMarketCap/SOX_USDX')->getBody()->getContents();
			// $coin['SOX'] = json_decode($response)->response->LastPrice;
	    }
	   
	    $coin['USD'] = 1;

	    if($system){
		    return $coin[$system];
		}
		
	    return $coin;
    }
    
    
    public static function getAccountTransactions($symbol){
	    $account = self::coinbase()->getAccount($symbol);
        $transactions = self::coinbase()->getAccountTransactions($account, [
		    Param::LIMIT => 20,
		]);

	    return $transactions;
    }
    
    
    public static function getAccountDeposit($symbol){
	    $account = self::coinbase()->getAccount($symbol);
	    $transactions = self::coinbase()->getAccountDeposit($account);
	    return $transactions;
    }
    
    public function getCoinbase(Request $req){
		
		if(!$req->Coin){
		    $coin = 'BTC';
	    }else{
		    $coin = $req->Coin;
	    }
	    $account = $this->coinbase()->getAccount($coin);
	    $balance = $account->getbalance()->getamount();

		
        $transactions = $this->coinbase()->getAccountTransactions($account, [
		]);
		
		$excel = array();
		$i = 0;
		foreach($transactions as $v){
			if($i==0){
				$plus = 0;
			}else{
				$plus = $transactions[$i-1]->getamount()->getamount();
			}
			if($v->getdescription() != null){
				$getdescription = $v->getdescription();
				
			}else{
				$getdescription = 'User Deposit';
			}
			array_push($excel, array(
				$i+1,
				$v->getcreatedAt()->format('Y-m-d H:i:s'),
				number_format($balance + $plus, 8),
				$v->getamount()->getamount(),
				$v->getnetwork()->gethash(),
				$getdescription
			));
			$i++;
		}
		if(Input::get('export')){
			if(Session('user')->User_Level != 1 && Session('user')->User_Level != 2){
				dd('stop');
			}
			$history = $excel;

			$listHistory = array();
			
			//xuất excel
			$listHistoryExcel[] = array('ID','Time', 'Balance', 'Amount','Description','Transaction ID');
			$i = 1;
			
			foreach ($history as $d)
			{
				$listHistoryExcel[$i][0] = $d[0];
				$listHistoryExcel[$i][1] = $d[1];
				$listHistoryExcel[$i][2] = $d[2];
				$listHistoryExcel[$i][3] = $d[3];
				$listHistoryExcel[$i][4] = $d[5];
				$listHistoryExcel[$i][5] = $d[4];
				$i++;
			}
			Excel::create('Transaction-'.$coin.''.date('YmdHis'), function($excel) use ($listHistoryExcel, $coin) {
				$excel->setTitle('Transaction-'.$coin.''.date('YmdHis'));
				$excel->setCreator('Transaction-'.$coin.''.date('YmdHis'))->setCompany('SBANK');
				$excel->setDescription('Transaction-'.$coin.''.date('YmdHis'));
				$excel->sheet('sheet1', function ($sheet) use ($listHistoryExcel) {
					$sheet->fromArray($listHistoryExcel, null, 'A1', false, false);
				});
			})->download('xls');
		}
		return view('System.Admin.Admin-Coinbase');
	}
	public function checkWallet($coin){
		$user = Session::get('user') ?? Auth::user();
		// thông tin coin
		$coinInfo = DB::table('currency')->where('Currency_ID', $coin)->where('Currency_Active', 1)->first();

		if($coinInfo){

			$address = DB::table('address')->where('Address_User', $user->User_ID)->where('Address_Currency', $coin)->where('Address_IsUse', 0)->first();
			if($address){
				$addressArray = array(
                    'name'=>$coinInfo->Currency_Symbol,
                    
					'address'=>$address->Address_Address,
					'Qr'=>'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl='.( $coin == 1 ? 'bitcoin:' : '' ).''.$address->Address_Address.'&choe=UTF-8'
				);
				return $addressArray; 
			}
			return null;
		}
		return null;
	}
	public function getAddress(Request $req){
        $user = Session::get('user') ?? Auth::user();
        switch ($req->coin) {
			case 1:
		    	// btc
				$addressArray = $this->checkWallet(1);
				if($addressArray){
					return response()->json($addressArray, 200); 
				}else{
					$account = $this->coinbase()->getAccount('BTC');
		            $address = new Address([
		            	'name' => 'New Address BTC of ID:'.$user->User_ID
		            ]);
		            $info = $this->coinbase()->createAccountAddress($account, $address);
		
		            $btcAddress = $info->getaddress();
		
		            $addressArray = array(
						'name'=>'BTC',
						'address'=>$btcAddress,
						'Qr'=>'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=bitcoin:'.$btcAddress.'&choe=UTF-8'
					);
		            
		            // Thêm địa chỉ ví vào DB
		            $wallet = new Wallet();
		            $wallet->Address_Currency = 1;
		            $wallet->Address_Address = $btcAddress;
		            $wallet->Address_User = $user->User_ID;
		            $wallet->Address_IsUse = 0;
		            $wallet->Address_Comment = 'Create new address';
		            $wallet->save();
		            return response()->json($addressArray, 200);
				}
		        break;
		    case 2:
		        // eth
		        $addressArray = $this->checkWallet(2);
				if($addressArray){
					return response()->json($addressArray, 200); 
				}else{
					$account = $this->coinbase()->getAccount('ETH');

					
		            $address = new Address([
		            	'name' => 'New Address ETH of ID:'.$user->User_ID
					]);
					
					
		            $info = $this->coinbase()->createAccountAddress($account, $address);
		
		            $ethAddress = $info->getaddress();
		            $addressArray = array(
						'name'=>'ETH',
						'address'=>$ethAddress,
						'Qr'=>'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl='.$ethAddress.'&choe=UTF-8'
					);
					
		            // Thêm địa chỉ ví vào DB
		            $wallet = new Wallet();
		            $wallet->Address_Currency = 2;
		            $wallet->Address_Address = $ethAddress;
		            $wallet->Address_User = $user->User_ID;
		            $wallet->Address_IsUse = 0;
		            $wallet->Address_Comment = 'Create new address';
					$wallet->save();
		            return response()->json($addressArray, 200);
				}
		        
		        break;
		    
		    
		    
		    case 8:
		        // SOX
		        $addressArray = $this->checkWallet(8);
				if($addressArray){
					return response()->json($addressArray, 200); 
				}else{
					
/*
					$client = new PayusClient(['access_token' => $this->access_token]);

					$payus = new Payus($client);
*/
					//tạo ví 
// 					$response = $payus->generateAddress(['coin_code' => 'SOX']);
					$response = file_get_contents('http://174.138.27.227:8190/wallet/generateaddress');

					$data = json_decode($response);
					$wallet_address = $data->address;
					$wallet_private_key = $data->privateKey;
					$wallet_hexAddress = $data->hexAddress;
		            $addressArray = array(
						'name'=>'SOX',
						'address'=>$wallet_address,
						'Qr'=>'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl='.$wallet_address.'&choe=UTF-8'
					);
					$wallet = new Wallet();
		            $wallet->Address_Currency = 8;
		            $wallet->Address_Address = $wallet_address;
		            $wallet->Address_User = $user->User_ID;
		            $wallet->Address_IsUse = 0;
		            $wallet->Address_PrivateKey = $wallet_private_key;
		            $wallet->Address_HexAddress = $wallet_hexAddress;
		            $wallet->Address_Comment = 'Create new address';
		            $wallet->save();
		            return response()->json($addressArray, 200);
				}
		        break;
		    
		}
    }
}
