<?php

namespace App\Http\Controllers\System;

use App\Model\Money;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommissionController extends Controller
{
        public function getHistoryCommission(){
            $user = session('user');
            $walletHistory = Money::join('moneyaction', 'Money_MoneyAction', 'moneyaction.MoneyAction_ID')
            ->whereIn('Money_MoneyStatus', [1, 2])
            ->where('Money_User', $user->User_ID)
            ->whereIn('Money_MoneyAction', [5,6])
            ->orderByDesc('Money_ID')
            ->select('Money_ID', 'Money_USDT', 'Money_USDTFee', 'moneyaction.MoneyAction_Name', 'Money_Rate','Money_Time', 'Money_Comment', 'Money_CurrentAmount')->orderBy('Money_Time', 'DESC')->paginate(25);
            return view('System.History.Commission-History', compact('walletHistory'));

        }
}
