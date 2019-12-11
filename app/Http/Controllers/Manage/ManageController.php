<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Money;
use Validator;
use DB;
class ManageController extends Controller
{
    public function getFetchDataMoney($id){
        $row_has_exist = Money::where('Money_ID', $id)->first();
        $row_has_exist->Money_Time = Date('Y-m-d H:i:s', $row_has_exist->Money_Time);
        if(!$row_has_exist){
            return response()->json([
                'status' => 500
            ]);
        }
        else {
            $currency = DB::table('currency')->where('Currency_Active', 1)->get();
            $action = DB::table('moneyaction')->get();
            return response()->json([
                'status' => 200,
                'list' => $row_has_exist,
                'currency' => $currency,
                'action' => $action,
            ]);
        }
    }
    public function putEditDataMoney(Request $req, $id){
        
        $row_has_exist = Money::where('Money_ID', $id)->first();
        if(!$row_has_exist){
            return response()->json([
                'status' => 500
            ]);

        }
        $arg_log = $row_has_exist->toArray();
        $req->validate([
                // 'Money_User' =>  'required|numeric',
                'Money_USDT' =>  'required|numeric',
                'Money_USDTFee' =>  'required|numeric',
                // 'Money_SaleBinary' =>  'numeric',
                // 'Money_Investment' =>  'numeric',
                // 'Money_Borrow' =>  'numeric',
                'Money_Time' =>  'required|date',
                'Money_Comment' =>  'required',
                'Money_MoneyAction' =>  'required|numeric',
                'Money_MoneyStatus' =>  'required|numeric',
                // 'Money_Token' =>  'numeric',
                // 'Money_Address' =>  'required',
                'Money_Currency' =>  'required|numeric',
                'Money_CurrentAmount' =>  'required|numeric',
                'Money_Rate' =>  'required|numeric',
                'Money_Confirm' =>  'required|numeric',
                // 'Money_Confirm_Time' =>  'date',
                
            ]
        );
        // dd(response()->json($arg_log));

        //update row
        // $row_has_exist->Money_User = $req->Money_User;
        $row_has_exist->Money_USDT = $req->Money_USDT;
        $row_has_exist->Money_USDTFee = $req->Money_USDTFee;
        $row_has_exist->Money_SaleBinary = $req->Money_SaleBinary;
        $row_has_exist->Money_Investment = $req->Money_Investment;
        $row_has_exist->Money_Borrow = $req->Money_Borrow;
        $row_has_exist->Money_Time = strtotime($req->Money_Time);
        $row_has_exist->Money_Comment = $req->Money_Comment;
        $row_has_exist->Money_MoneyAction = $req->Money_MoneyAction;
        $row_has_exist->Money_MoneyStatus = $req->Money_MoneyStatus;
        $row_has_exist->Money_Token = $req->Money_Token;
        $row_has_exist->Money_Address = $req->Money_Address;
        $row_has_exist->Money_Currency = $req->Money_Currency;
        $row_has_exist->Money_CurrentAmount = $req->Money_CurrentAmount;
        $row_has_exist->Money_Rate = $req->Money_Rate;
        $row_has_exist->Money_Confirm = $req->Money_Confirm;
        $row_has_exist->Money_Confirm_Time = $row_has_exist->Money_Confirm_Time;
        $row_has_exist->save();
        //Write log

        $after_req = $req->toArray();
        unset($after_req['_method']);
        unset($after_req['_token']);

        $log = DB::table('log_money')->insert([
            'log_money_User' => Session('user')->User_ID,
            'log_money_Beforechange' => json_encode($arg_log),
            'log_money_Afterchange' => json_encode($after_req)
        ]);
        //mess
        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "#$id Data change success!"]);
    }
}
