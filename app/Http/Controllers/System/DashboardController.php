<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Money;
use App\Model\Investment;
use GuzzleHttp\Client;
use Session;
class DashboardController extends Controller
{


    public function getDashboard()
    {
        $RandomToken = Money::RandomToken();
        $user = Session('user');
        $balance = Money::getBalance(Session('user')->User_ID);
        $history_invest = Investment::join('package', 'package_ID', 'investment_Package')->join('currency', 'Currency_ID' ,'investment_Currency')->where('investment_User', $user->User_ID )->where('investment_Status','<>', -1)->orderBy('investment_ID', 'DESC')->get();
        return view('System.Dashboard.Index', compact('balance', 'history_invest', 'RandomToken'));
    }
}
