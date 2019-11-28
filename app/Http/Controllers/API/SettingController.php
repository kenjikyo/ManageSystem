<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

use DB;

class SettingController extends Controller{

	
	public $keyHash	 = 'DAFCOCoorgsafwva'; 
	
    
    public function getSetting(){

	    include(app_path() . '/functions/xxtea.php');
	    $arrayReturn = array(
		    'version' => 1,
		    'facebook' => 'https://www.facebook.com/abc/',
		    'telegram' => 'https://t.me/abc',
		    'twitter' => 'https://twitter.com/abc',
		    'download' => 'https://dafco.org/',
		    'fee-USDX' => 0.005,
		    'fee-SOX' => 0.002,
	    );
		return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'data'=>$arrayReturn)),$this->keyHash)), 200);
	    
    }
    
    public function getSlide(){

	    include(app_path() . '/functions/xxtea.php');
		$slide = array(
			array(
				'img'=>'https://dafco.org/bg-app.jpg',
				'link'=>null
			),
			array(
				'img'=>'https://dafco.org/assets/images/envi/hst-1.jpg',
				'link'=>null
			),
			array(
				'img'=>'https://dafco.org/assets/images/envi/hst-2.jpg',
				'link'=>null
			),
			array(
				'img'=>'https://dafco.org/assets/images/envi/hst-3.jpg',
				'link'=>null
			),
			array(
				'img'=>'https://dafco.org/assets/images/envi/hst-4.jpg',
				'link'=>null
			),
			array(
				'img'=>'https://dafco.org/assets/images/envi/hst-5.jpg',
				'link'=>null
			)
			
		);
		return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'data'=>$slide)),$this->keyHash)), 200);
	    
    }
    
    public function getListCoin(){
	    include(app_path() . '/functions/xxtea.php');
	    // cập nhật giá token bên app
		$tokenPrice = DB::table('changes')->where('Changes_Time', date('Y-m-d'))->where('Changes_Hour', '<=', date('H'))->orderBy('Changes_Hour', 'DESC')->first();
		if(!$tokenPrice){
			$getPrice = DB::table('changes')->where('Changes_Time', '<', date('Y-m-d'))->orderByDesc('Changes_Time')->first();
			$data = ['Changes_Price'=>$getPrice->Changes_Price, 'Changes_Time'=>date('Y-m-d'), 'Changes_Status'=>1 ];
			DB::table('changes')->insert($data);
			$tokenPrice = DB::table('changes')->where('Changes_Time', date('Y-m-d'))->first();
		}
		//end
	    $data = $this->getAPI(7200);
	    $i = 0;
	    foreach($data as $key=>$v){
			$img = "https://dafco.org/assets/images/coinname/".$v->symbol.".png";
		    if(file_exists(public_path(). "/assets/images/coinname/".$v->symbol.".png") === false){
				$img = "https://dafco.org/assets/images/coinname/GPG.png";
		    }
			$coinArr[$v->symbol] = array(
								'Name'=>$v->name,
								'Symbol'=>$v->symbol,
								'icon'=> $img,
								'Price'=>  $v->quotes->USD->price,
								'PecentPlus'=> $v->quotes->USD->percent_change_24h
							);
			$i++;
			if($i == 10){
				break;
			}
	    }
// 	    dd($coinArr);
		return response(base64_encode(xxtea_encrypt(json_encode(array('status'=>true, 'data'=>$coinArr)),$this->keyHash)), 200);
	    
    }
    public function getAPI($second = 0){
	    $result = '';
	    $continue = 0;
	    $fileList = glob('json/*');
	    $findExist = array_search("json/api.json",$fileList);
		$contentRead = file_get_contents(public_path('json/api.json'));
		$dataRead = json_decode($contentRead);
		$result = $dataRead->data;
		return $result;
	    if($findExist === false){

		    $jsonString = file_get_contents('https://api.coinmarketcap.com/v2/ticker');
			$data = json_decode($jsonString, true);
			$data['time'] = time();
			$newJsonString = json_encode($data, JSON_PRETTY_PRINT);
			file_put_contents(public_path('json/api.json'), stripslashes($newJsonString));
			$continue = 1;
	    }elseif($findExist !== false || $continue == 1){

			$contentRead = file_get_contents(public_path('json/api.json'));
			$dataRead = json_decode($contentRead);
			$curTime = (int)(time() - $dataRead->time);

			if($curTime > $second){
				$jsonString = file_get_contents('https://api.coinmarketcap.com/v2/ticker');
				$dataRead = json_decode($jsonString);
				$dataRead->time = time();
				$newJsonString = json_encode($dataRead, JSON_PRETTY_PRINT);
				file_put_contents(public_path('json/api.json'), stripslashes($newJsonString));
				$result = $newJsonString->data;
			}else {
				$result = $dataRead->data;
			}
	    }
	    return $result;
  	}
  	

}