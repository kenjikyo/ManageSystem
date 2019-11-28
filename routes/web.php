<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test', 'TestController@getTest');

Route::get('/', 'System\IndexController@index')->name('getIndex');

Route::get('login', 'Auth\LoginController@getLogin')->name('getLogin');
Route::post('login', 'Auth\LoginController@postLogin')->name('postLogin');

Route::get('register', 'Auth\RegisterController@getRegister')->name('getRegister');
Route::post('/register', 'Auth\RegisterController@postRegister')->name('postRegister');

Route::get('active', 'Auth\RegisterController@getActive')->name('getActiveMail');
Route::post('loginCheckOTP','Auth\LoginController@postLoginCheckOTP')->name('postLoginCheckOTP');
Route::get('forgot-password', 'Auth\ForgotPasswordController@getForgotPassword')->name('getForgotPassword');
Route::post('forgot-password', 'Auth\ForgotPasswordController@postForgotPassword')->name('postForgotPassword');
Route::get('active-forgot-password', 'Auth\ForgotPasswordController@activePass')->name('activePass');
//Logout
Route::get('logout', 'Auth\LoginController@getLogout')->name('getLogout');

Route::group(['prefix' => 'system', 'middleware' => 'login'], function () {
    Route::get('/', 'System\DashboardController@getDashboard')->name('Dashboard');
    //member
    Route::group(['prefix' => 'member'], function () {
        Route::get('add', 'System\UserController@getAdd')->name('system.user.getAdd');
		Route::get('list', 'System\UserController@getList')->name('system.user.getList');
        Route::post('member-add', 'Auth\RegisterController@postMemberAdd')->name('system.user.postMemberAdd');
        Route::get('tree', 'System\UserController@getTree')->name('system.user.getTree');


        Route::get('profile', 'System\UserController@getProfile')->name('getProfile');
        Route::post('profile', 'System\UserController@postProfile')->name('postProfile');
        Route::post('auth', 'System\UserController@postAuth')->name('postAuth');
        Route::post('change-password', 'Auth\ResetPasswordController@changePassword')->name('postChangePassword');
        Route::post('post-kyc', 'System\UserController@PostKYC')->name('system.user.PostKYC');
    });
    //wallet
    Route::group(['prefix' => 'wallet'], function () {
        Route::get('/', 'System\WalletController@getWallet')->name('system.getWallet');

        Route::get('deposit', 'System\WalletController@getDeposit')->name('system.getDeposit');

        Route::get('withdraw', 'System\WalletController@getWithdraw')->name('system.getWithdraw');
        Route::post('withdraw', 'System\WalletController@postWithdraw')->name('system.postWithdraw');
        Route::post('confirm-withdraw', 'System\WalletController@postWithdraw')->name('system.postConfirmWithdraw');

        Route::get('transfer', 'System\WalletController@getTransfer')->name('system.getTransfer');
        Route::post('transfer', 'System\WalletController@postTransfer')->name('system.postTransfer');
        Route::post('confirm-transfer', 'System\WalletController@postConfirmTranfer')->name('system.postConfirmTranfer');
    });
    //Invest
    Route::group(['prefix' => 'investment'], function () {
        Route::get('/', 'System\InvestmentController@getInvestment')->name('system.getInvestment');
        Route::post('investment', 'System\InvestmentController@postInvestment')->name('system.postInvestment');
        Route::get('cancel-investment/{id}', 'System\InvestmentController@getCancelInvestment')->name('system.getCancelInvestment');
        Route::get('get-investment', 'System\InvestmentController@getInvestmentByID')->name('system.getInvestmentByID');
        Route::get('cancel-investment', 'System\InvestmentController@CancelInvestmentByID')->name('system.CancelInvestmentByID');
        //Rufund or Reinvestment

        Route::put('action/refund/{id}', 'System\InvestmentController@postActionRefund')->name('postActionRefund');
        Route::put('action/reinvestment/{id}', 'System\InvestmentController@postActionReinvestment')->name('postActionReinvestment');
        //get info pakage
        Route::get('package/{id?}', 'System\InvestmentController@getInfo_Package')->name('getInfo_Package');

    });
    //Ticket
    Route::group(['prefix' => 'ticket'], function () {
        Route::get('/', 'System\TicketController@getTicket')->name('Ticket');
        Route::post('post-ticket', 'System\TicketController@postTicket')->name('postTicket');
        Route::get('destroy-ticket/{id}', 'System\TicketController@destroyTicket')->name('destroyTicket');
        Route::get('get-ticket-detail/{id}', 'System\TicketController@getTicketDetail')->name('getTicketDetail');
        Route::get('ticket-admin', 'System\TicketController@getTicketAdmin')->name('getTicketAdmin');
        Route::get('update-status/{id}', 'System\TicketController@getStatusTicketAdmin')->name('getStatusTicketAdmin');
    });
    //Json
    Route::group(['prefix'=>'json']	, function (){
		Route::get('getAddress', 'System\CoinbaseController@getAddress')->name('system.json.getAddress');
		Route::get('coinbase', 'System\JsonController@getCoinbase')->name('system.json.getCoinbase');
	});
    Route::group(['prefix'=>'ajax'], function (){
		Route::get('ajax-user', 'System\WalletController@getAjaxUser')->name('system.getAjaxUser');
	});
    //History
    Route::group(['prefix' => 'history'], function () {
        Route::get('wallet', 'System\WalletController@getHistoryWallet')->name('system.history.getHistoryWallett');
        Route::get('commission', 'System\CommissionController@getHistoryCommission')->name('system.history.getHistoryCommisson');
        Route::get('investment', 'System\InvestmentController@getHistoryInvestment')->name('system.history.getHistoryInvestment');
    });

    //Admin
    Route::group(['middleware'=>'check.permission','prefix'=>'admin'], function (){
        //Member
        Route::get('member', 'System\AdminController@getMemberListAdmin')->name('system.admin.getMemberListAdmin');
        Route::get('login/{id}', 'System\AdminController@getLoginByID')->name('system.admin.getLoginByID');
        Route::get('active-mail/{id}', 'System\AdminController@getActiveMail')->name('system.admin.getActiveMail');
        Route::post('edit-mail', 'System\AdminController@getEditMailByID')->name('system.admin.getEditMailByID');
        Route::get('disable-auth/{id}', 'System\AdminController@getDisableAuth')->name('system.admin.getDisableAuth');
        Route::get('edit-user/{id}', 'System\AdminController@getEditUser')->name('system.admin.getEditUser');

        //Wallet
        Route::get('wallet', 'System\AdminController@getWallet')->name('system.admin.getWallet');
        Route::get('interest', 'System\AdminController@getInterest')->name('system.admin.getInterest');
        Route::post('deposit', 'System\AdminController@postDepositAdmin')->name('system.admin.postDepositAdmin');

        Route::get('wallet/detail/{id}', 'System\AdminController@getWalletDetail')->name('system.admin.getWalletDetail');
        //Invest
        Route::get('investment', 'System\AdminController@getAdminInvestmentList')->name('system.admin.InvestmentList');
        Route::post('post-check-interest-list', 'System\AdminController@postCheckInterestList')->name('system.admin.postCheckInterestList');
        //statistical
        Route::get('statistical', 'System\AdminController@getStatistical')->name('system.admin.getStatistical');

        //coinbase
        Route::get('coinbase', 'System\CoinbaseController@getCoinbase')->name('system.admin.getCoinbase');
        //Profile
        Route::get('profile', 'System\AdminController@getProfile')->name('system.admin.getProfile');
        Route::post('confirm-profile', 'System\AdminController@confirmProfile')->name('system.admin.confirmProfile');
        //Log Mail
        Route::get('log-mail', 'System\AdminController@getLogMail')->name('system.admin.getLogMail');
        //Log SOX
        Route::get('log-sox', 'System\AdminController@getLogSOX')->name('system.admin.getLogSOX');
    });
});
Route::group(['prefix'=>'cron'], function () {
    Route::get('total-sales', 'Cron\CronController@totalSalesMonth')->name('cron.totalSalesMonth');
    Route::get('interest', 'Cron\CronController@payInterest')->name('cron.payInterest');
    Route::get('auto-pay', 'Cron\CronController@AutoPayInterest')->name('cron.AutoPayInterest');
    Route::get('deposit', 'Cron\CronController@getDeposit')->name('cron.getDeposit');
    Route::get('deposit-sox', 'Cron\CronController@getDepositSOX')->name('cron.getDepositSOX');
    Route::get('rate', 'Cron\CronController@getRateCoin')->name('cron.getRateCoin');
    Route::get('get-price', 'Cron\CronController@getPriceCoin')->name('cron.getPriceCoin');
    Route::get('set-price', 'Cron\CronController@setPriceCoin')->name('cron.setPriceCoin');

});

Route::get('test-bot', 'TestController@testTelegramBot');
