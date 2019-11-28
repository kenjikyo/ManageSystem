<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Money;
use App\Model\Investment;
class IndexController extends Controller
{
    public function index()
    {
        return view('System.Index');
    }
}
